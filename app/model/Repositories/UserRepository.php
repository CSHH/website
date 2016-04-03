<?php

namespace App\Model\Repositories;

use App\Model\Duplicities\DuplicityChecker;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions\ActivationLimitExpiredException;
use App\Model\Exceptions\UserNotFoundException;
use App\Model\Security\Authenticator;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette\Localization\ITranslator;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;
use Nette\Utils\Random;

class UserRepository extends BaseRepository
{
    use DuplicityChecker;

    /** @var ITranslator */
    private $translator;

    /** @var EntityManager */
    private $em;

    public function __construct(
        EntityDao $dao,
        ITranslator $translator,
        EntityManager $em
    ) {
        parent::__construct($dao);

        $this->translator = $translator;
        $this->em         = $em;
    }

    /**
     * @param  ArrayHash                             $values
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\UserEntity
     */
    public function createRegistration(ArrayHash $values)
    {
        $user = new Entities\UserEntity;
        $user->setValues($values);
        $pass = $values->password;

        $e = $this->isValueDuplicate($this->em, Entities\UserEntity::getClassName(), 'username', $user->username);
        if ($e) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.registration_username')
            );
        }

        $e = $this->isValueDuplicate($this->em, Entities\UserEntity::getClassName(), 'email', $user->email);
        if ($e && $this->isActivationLimitExpired($e->tokenCreatedAt)) {
            $this->delete($e);
        } elseif ($e) {
            throw new PossibleUniqueKeyDuplicationException(
                $this->translator->translate('locale.duplicity.registration_email')
            );
        }

        $time = new DateTime;

        $user->token          = $this->generateToken();
        $user->tokenCreatedAt = $time;
        $user->salt           = $this->generateSalt();

        $user->password = Passwords::hash($pass . $user->salt);

        $this->persistAndFlush($this->em, $user);

        return $user;
    }

    /**
     * @param  ArrayHash                             $values
     * @param  Entities\UserEntity                   $user
     * @throws PossibleUniqueKeyDuplicationException
     * @return Entities\UserEntity
     */
    public function updateProfileSettings(
        ArrayHash $values,
        Entities\UserEntity $user
    ) {
        $pass = $values->password;
        unset($values->password);

        $user->setValues($values);

        $e = $this->isValueDuplicate($this->em, Entities\UserEntity::getClassName(), 'email', $user->email);
        if ($e && $e->id !== $user->id) {
            throw new PossibleUniqueKeyDuplicationException($this->translator->translate('locale.duplicity.registration_email'));
        }

        if ($pass) {
            $user->salt     = $this->generateSalt();
            $user->password = Passwords::hash($pass . $user->salt);
        }

        $this->persistAndFlush($this->em, $user);

        return $user;
    }

    /**
     * @param Entities\UserEntity $e
     */
    public function delete(Entities\UserEntity $e)
    {
        $this->removeAndFlush($this->em, $e);
    }

    /**
     * @param  string                   $email
     * @return Entities\UserEntity|null
     */
    public function getByEmail($email)
    {
        return $this->dao->findOneBy(array('email' => $email));
    }

    /**
     * @param Entities\UserEntity $e
     * @param string              $password
     * @param bool                $setAuthenticated
     */
    public function updatePassword(
        Entities\UserEntity $e,
        $password,
        $setAuthenticated = false
    ) {
        $e->salt     = $this->generateSalt();
        $e->password = Passwords::hash($password . $e->salt);

        if ($setAuthenticated) {
            $e->isAuthenticated = true;
        }

        $this->persistAndFlush($this->em, $e);
    }

    /**
     * @return string
     */
    public function generateToken()
    {
        $users = $this->dao->findAll();
        $pairs = [];

        foreach ($users as $e) {
            $pairs[] = $e->token;
        }

        while (true) {
            $token = Random::generate(10, '0-9A-Za-z');
            $found = array_search($token, $pairs, true);

            if ($found === false) {
                return $token;
            }
        }
    }

    /**
     * @return string
     */
    public function generateSalt()
    {
        $users = $this->dao->findAll();
        $salts = [];

        foreach ($users as $e) {
            $salts[] = $e->salt;
        }

        while (true) {
            $salt  = Random::generate(10, '0-9A-Za-z');
            $found = array_search($salt, $salts, true);

            if ($found === false) {
                return $salt;
            }
        }
    }

    /**
     * @param  int                             $userId
     * @param  string                          $token
     * @param  bool                            $checkExpiration
     * @throws UserNotFoundException
     * @throws ActivationLimitExpiredException
     */
    public function unlock($userId, $token, $checkExpiration = true)
    {
        $user = $this->dao->findOneBy(['id' => $userId, 'token' => $token]);
        if (!$user) {
            throw new UserNotFoundException($this->translator->translate('locale.sign.account_not_found'));
        }

        if ($checkExpiration) {
            $expired = $this->isActivationLimitExpired($user->tokenCreatedAt);
            if ($expired) {
                $this->dao->delete($user);
                throw new ActivationLimitExpiredException($this->translator->translate('locale.sign.authentication_expired'));
            }
        }

        $user->token           = null;
        $user->tokenCreatedAt  = null;
        $user->isAuthenticated = true;

        $this->persistAndFlush($this->em, $user);
    }

    /**
     * @return bool
     */
    private function isActivationLimitExpired($tokenCreatedAt)
    {
        $current = new DateTime();
        $created = $tokenCreatedAt;

        $currentTimestamp = $current->getTimestamp();
        $createdTimestamp = $created->getTimestamp();

        $diff = $currentTimestamp - $createdTimestamp;

        if ($diff >= Authenticator::ACTIVATION_LIMIT_TIMESTAMP) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Entities\UserEntity
     * @return string
     */
    public function prepareNewToken(Entities\UserEntity $e)
    {
        $token             = $this->generateToken();
        $e->token          = $token;
        $e->tokenCreatedAt = new DateTime;

        $this->persistAndFlush($this->em, $e);

        return $token;
    }

    /**
     * @param  Entities\UserEntity             $e
     * @param  string                          $token
     * @throws UserNotFoundException
     * @throws ActivationLimitExpiredException
     */
    public function checkForTokenExpiration(Entities\UserEntity $e, $token)
    {
        if ($e->token !== $token) {
            throw new UserNotFoundException($this->translator->translate('locale.sign.incorrect_token'));
        }

        $current = new DateTime;
        $created = $e->tokenCreatedAt;

        $currentTimestamp = $current->getTimestamp();
        $createdTimestamp = $created->getTimestamp();

        $diff = $currentTimestamp - $createdTimestamp;
        if ($diff >= Authenticator::ACTIVATION_LIMIT_TIMESTAMP) {
            throw new ActivationLimitExpiredException($this->translator->translate('locale.sign.authentication_expired'));
        }
    }
}
