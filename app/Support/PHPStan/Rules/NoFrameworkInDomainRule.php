<?php

namespace App\Support\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class NoFrameworkInDomainRule implements Rule
{
    public function getNodeType(): string
    {
        return Use_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $file = $scope->getFile();

        // Só aplica no domínio
        if (
            !str_contains($file, '/app/Core/')
            || !str_contains($file, '/Domain/')
        ) {
            return [];
        }

        $errors = [];

        foreach ($node->uses as $use) {
            $import = $use->name->toString();

            if (
                str_starts_with($import, 'Illuminate\\') ||
                str_starts_with($import, 'App\\Models\\') ||
                str_starts_with($import, 'App\\Http\\')
            ) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        '❌ Domain layer cannot depend on framework class: %s',
                        $import
                    )
                )->build();
            }
        }

        return $errors;
    }
}