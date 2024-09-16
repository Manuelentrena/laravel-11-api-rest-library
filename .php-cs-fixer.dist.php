<?php

use PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\CamelCapsFunctionNameSniff;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(['app', 'tests'])
;

return (new Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
