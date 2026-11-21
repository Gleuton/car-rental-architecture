<?php

declare(strict_types=1);

it('has a unit test at the mirrored tests/Unit path for every use case in app/Core', function () {
    $projectRoot = dirname(__DIR__, 3);

    $useCasePaths = [];
    foreach (glob($projectRoot.'/app/Core/*/Application/UseCases', GLOB_ONLYDIR) ?: [] as $useCasesDirectory) {
        $filesInDirectory = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($useCasesDirectory, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($filesInDirectory as $file) {
            if (str_ends_with($file->getFilename(), 'UseCase.php')) {
                $useCasePaths[] = substr($file->getPathname(), strlen($projectRoot.'/'));
            }
        }
    }

    $toMirroredTestPath = static fn (string $useCasePath): string => 'tests/Unit/'
        .substr($useCasePath, strlen('app/'), -strlen('.php'))
        .'Test.php';

    $doesNotExist = static fn (string $testPath): bool => ! is_file($projectRoot.'/'.$testPath);

    $missingTestPaths = array_values(array_filter(
        array_map($toMirroredTestPath, $useCasePaths),
        $doesNotExist,
    ));
    sort($missingTestPaths);

    expect($useCasePaths)->not->toBeEmpty()
        ->and($missingTestPaths)->toBe([], "Missing unit tests for use cases:\n".implode("\n", $missingTestPaths));
});
