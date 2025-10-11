<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use Sweetchuck\Git\Tests\Acceptance\TestBase;
use Symfony\Component\Process\Process;

class CommandTestBase extends TestBase
{
    /**
     * Helper method to create expectation for string values that can vary.
     */
    protected static function expectString(): string
    {
        return '__EXPECT_STRING__';
    }

    /**
     * Helper method to create expectation for int values that can vary.
     */
    protected static function expectInt(): string
    {
        return '__EXPECT_INT__';
    }

    /**
     * @param array<array<string, mixed>> $steps
     */
    protected function executeSteps(string $dir, array $steps): static
    {
        $replacementPairs = [
            '{{ dir }}' => $dir,
            '{{ dirSafe }}' => escapeshellarg($dir),
        ];
        foreach ($steps as $step) {
            switch ($step['type']) {
                case 'exec':
                    $step += [
                        'expectedExitCode' => 0,
                    ];
                    $command = strtr($step['command'], $replacementPairs);
                    $process = new Process(['bash', '-c', $command]);
                    $process->run();
                    if ($process->getExitCode() !== $step['expectedExitCode']) {
                        throw new \RuntimeException(sprintf(
                            "Command failed: %s\nExit code: %d\nstdOutput: %s\nstdError: %s.",
                            $command,
                            $process->getExitCode(),
                            $process->getOutput(),
                            $process->getErrorOutput(),
                        ));
                    }

                    foreach (['expectedOutput', 'expectedError'] as $key) {
                        $output = $key === 'expectedOutput' ?
                            $process->getOutput()
                            : $process->getErrorOutput();

                        if (isset($step[$key])) {
                            if ($step[$key] === '') {
                                static::assertSame(
                                    $step[$key],
                                    trim($output),
                                    sprintf('Command "%s" %s mismatch', $command, $key)
                                );
                            } else {
                                static::assertStringContainsString(
                                    $step[$key],
                                    trim($output),
                                    sprintf('Command "%s" %s mismatch', $command, $key)
                                );
                            }
                        }
                    }
                    break;

                case 'createFile':
                    // Creates a file with the given content and creates the parent directories if they don't exist.
                    $path = strtr($step['path'], $replacementPairs);
                    $this->fs->dumpFile($path, $step['content']);
                    break;

                case 'touch':
                    $path = strtr($step['path'], $replacementPairs);
                    $this->fs->touch($path);
                    break;

                case 'isDir':
                    $step += ['expected' => true];
                    $path = strtr($step['path'], $replacementPairs);
                    static::assertSame(
                        $step['expected'],
                        is_dir($path),
                        "$path is a directory",
                    );
                    break;

                case 'isFile':
                    $step += ['expected' => true];
                    $path = strtr($step['path'], $replacementPairs);
                    static::assertSame(
                        $step['expected'],
                        is_file($path),
                        "$path is a file",
                    );
                    break;

                default:
                    throw new \RuntimeException(sprintf('Unknown step type "%s".', $step['type']));
            }
        }

        return $this;
    }
}
