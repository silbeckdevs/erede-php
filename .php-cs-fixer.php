<?php
/**
 * @see https://cs.symfony.com/doc/rules/index.html
 * @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/f65e6a20c9ef30f2fc93d8c3e1bf6aa3bd910192/src/RuleSet/Sets/PSR12Set.php
 * @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/f65e6a20c9ef30f2fc93d8c3e1bf6aa3bd910192/src/RuleSet/Sets/SymfonySet.php
 */
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setParallelConfig(\PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true, // Aplicar o padrão PSR-12
        '@Symfony' => true, // Aplicar o padrão Symfony
        'fully_qualified_strict_types' => false, // Garantir namespaces completos
        'array_syntax' => ['syntax' => 'short'], // Usar a sintaxe curta para arrays []
        'binary_operator_spaces' => ['default' => 'single_space'],
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => ['style' => 'post'],
        'class_attributes_separation' => true,
    ])
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setFinder($finder);
