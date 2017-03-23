<?php

return PhpCsFixer\Config::create()
    ->setRules([
        
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tmp')
            ->in(__DIR__ . '/app')
            ->in(__DIR__ . '/tests')
    );
