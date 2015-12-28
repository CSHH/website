<?php

namespace HeavenProject\Thumbnailer\DI;

use Nette\DI\CompilerExtension;

/**
 * Thumb compiler extension.
 */
class ThumbExtension extends CompilerExtension
{
    /** @var array */
    public $defaults = [
        'wwwDir' => null, 'thumbDir' => null,
    ];

    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);

        if (empty($config['wwwDir']) || empty($config['thumbDir'])) {
            throw new ThumbExtensionException('Configuration options for the `thumb` latte filter extension has not been set properly.');
        }

        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix('thumb'))
            ->setClass('HeavenProject\Thumbnailer\Thumb')
            ->addSetup('setWwwDir', [$config['wwwDir']])
            ->addSetup('setThumbDir', [$config['thumbDir']]);
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();

        $latteFactory = $builder->hasDefinition('nette.latteFactory')
            ? $builder->getDefinition('nette.latteFactory')
            : $builder->getDefinition('nette.latte');

        $latteFactory
            ->addSetup('addFilter', [
                'thumb', [$this->prefix('@thumb'), 'thumbnalize'],
            ]);
    }
}
