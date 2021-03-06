<?php

namespace App\Forms;

use App\Entities;
use App\Exceptions;
use App\Repositories;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;

abstract class AbstractContentForm extends AbstractForm
{
    /** @var Repositories\TagRepository */
    protected $tagRepository;

    /** @var Entities\UserEntity */
    protected $user;

    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Entities\UserEntity $user
    ) {
        parent::__construct($translator);

        $this->tagRepository = $tagRepository;
        $this->user          = $user;
    }

    protected function configure(Form $form)
    {
        $form->addSubmit('submit', 'locale.form.save');
    }

    protected function addEditor(Form $form)
    {
        $form->addTextArea('text', 'locale.form.text')
            ->setRequired('locale.form.text_required');

        $js = <<<JS
for (var instanceName in CKEDITOR.instances) {
    var instance = CKEDITOR.instances[instanceName];
    var data = instance.getData();
    instance.element.setText(data);
}
JS;

        $form->getElementPrototype()->onsubmit($js);
    }

    /**
     * @param  Form                           $form
     * @throws Exceptions\MissingTagException
     * @return Entities\TagEntity|null
     */
    protected function getSelectedTag(Form $form)
    {
        $tagId = $form->getHttpData(Form::DATA_LINE, 'tagId');
        $tag   = $tagId ? $this->tagRepository->getById($tagId) : null;

        if (!$tag) {
            throw new Exceptions\MissingTagException(
                $this->translator->translate('locale.error.missing_tag')
            );
        }

        return $tag;
    }

    /**
     * @return array
     */
    protected function getTags()
    {
        $tags = [];
        foreach ($this->tagRepository->getAll() as $tag) {
            $tags[$tag->id] = $tag->name;
        }

        return $tags;
    }

    protected function insideRender(ITemplate $template)
    {
        $template->tags = $this->getTags();
    }
}
