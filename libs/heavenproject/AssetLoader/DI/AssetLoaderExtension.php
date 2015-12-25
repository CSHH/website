<?php

namespace HeavenProject\AssetLoader\DI;

use Nette\DI\CompilerExtension;

/**
 * Compiler extension for asset loader.
 */
class AssetLoaderExtension extends CompilerExtension
{
    /** @var array */
    public $defaults = [
        'wwwDir' => null, 'saveDir' => null,
    ];

    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);

        if (empty($config['wwwDir']) || empty($config['saveDir'])) {
            throw new AssetLoaderExtensionException('Configuration options for asset loader extension has not been set properly.');
        }

        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix('loaderHelper'))
            ->setClass('HeavenProject\AssetLoader\Helpers\Loader', ['wwwDir' => $config['wwwDir'], 'saveDir' => $config['saveDir']]
        );
    }
}
