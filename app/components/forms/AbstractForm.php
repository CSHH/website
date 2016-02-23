<?php

namespace App\Components\Forms;

use App\Model\Entities;
use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Reflection\ClassType;
use Tracy;

abstract class AbstractForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    protected $translator;

    public function __construct(ITranslator $translator)
    {
        parent::__construct();

        $this->translator = $translator;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $this->configure($form);

        $form->onSuccess[] = array($this, 'formSucceeded');

        return $form;
    }

    /**
     * @param Form $form
     * @param Entities\BaseEntity $item
     */
    protected function tryAutoFill(Form $form, Entities\BaseEntity $item = null)
    {
        if ($item) {
            $form->autoFill($item);
        }
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function log($message, $type = Tracy\Debugger::EXCEPTION)
    {
        Tracy\Debugger::barDump($message);
        Tracy\Debugger::log($message, $type);
    }

    /**
     * @param Form       $form
     * @param \Exception $e
     * @param string     $output
     */
    protected function addFormError(Form $form, \Exception $e, $output = null)
    {
        $msg = $e->getMessage();

        $this->log($msg);

        $form->addError(
            $output
                ? $this->translator->translate('locale.error.occurred')
                : $msg
        );
    }

    public function render()
    {
        $template = $this->getTemplate();

        $class = new ClassType($this);

        $template->setFile(dirname($class->fileName) . '/templates/' . $class->shortName . '.latte');

        $this->insideRender($template);

        $template->render();
    }

    /**
     * @param ITemplate $template
     */
    protected function insideRender(ITemplate $template)
    {
    }

    /**
     * @param Form $form
     */
    abstract protected function configure(Form $form);

    /**
     * @param Form $form
     */
    abstract public function formSucceeded(Form $form);
}
