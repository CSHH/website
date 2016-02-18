<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\MappedSuperclass()
 */
abstract class BaseEntity extends Kdyby\Doctrine\Entities\BaseEntity
{
    use Identifier;

    /**
     * @param  array|\Traversable             $values
     * @throws Nette\InvalidArgumentException
     */
    public function setValues($values)
    {
        if ($values instanceof \Traversable) {
            $values = iterator_to_array($values);
        } elseif (!is_array($values)) {
            throw new InvalidArgumentException(sprintf('Parameter must be an array, %s given.', gettype($values)));
        }

        foreach ($values as $key => $value) {
            if ($key === 'languageContainer') {
                foreach ($value as $locale => $data) {
                    foreach ($data as $k => $v) {
                        $this->translate($locale)->{$k} = $v !== '' ? $v : null;
                    }
                }
            } else {
                $this->{$key} = $value !== '' ? $value : null;
            }
        }
    }
}
