<?php

namespace Kunstmaan\CodeQuality\PhpCsFixer;

use PhpCsFixer\Finder;

class Config extends \PhpCsFixer\Config
{
    public function __construct()
    {
        parent::__construct('kunstmaan');

        $this
            ->setRules([
                '@Symfony' => true,
                'array_syntax' => ['syntax' => 'short'],
                'no_superfluous_phpdoc_tags' => true,
                'trailing_comma_in_multiline_array' => true,
                'yoda_style' => false,
                'ordered_imports' => ['sort_algorithm' => 'alpha'],
        ]);
    }

    public static function fromFolders($folders): self
    {
        $config = new static();

        return $config->setFinder(
            Finder::create()->in($folders)
        );
    }

    public function mergeRules(array $rules): self
    {
        return $this->setRules(array_merge(
            $this->getRules(),
            $rules
        ));
    }
}
