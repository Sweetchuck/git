<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GrepFiles;
use Sweetchuck\Git\OutcomeParser\GrepFilesParser;

/**
 * Acceptance tests for the GrepFiles command.
 *
 * These tests verify that the GrepFiles command correctly integrates with
 * the actual git grep command and produces the expected results.
 */
#[CoversClass(GrepFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GrepFilesParser::class)]
#[Group('command-git-grep')]
class GrepFilesTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'empty-repo' => [
                'expected' => [
                    'exitCode' => 1,
                    'artifacts' => [
                        'files' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
                'properties' => [
                    'expressions' => ['test'],
                ],
            ],
            'basic-search-single-file' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'README.md' => [
                                'lines' => [
                                    '1' => '# Test Project',
                                ],
                                'matches' => [
                                    '1' => 3,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['Test'],
                ],
            ],
            'search-multiple-files' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'file1.txt' => [
                                'lines' => [
                                    1 => 'This is a test file',
                                ],
                                'matches' => [
                                    1 => 11,
                                ],
                            ],
                            'file2.txt' => [
                                'lines' => [
                                    2 => 'Another test line',
                                ],
                                'matches' => [
                                    2 => 9,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'This is a test file',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => "Some content\nAnother test line\nMore content\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add test files"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['test'],
                ],
            ],
            'case-insensitive-search' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'case.txt' => [
                                'lines' => [
                                    '1' => 'TEST content',
                                    '2' => 'test content',
                                    '3' => 'Test content',
                                ],
                                'matches' => [
                                    '1' => 1,
                                    '2' => 1,
                                    '3' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/case.txt',
                        'content' => "TEST content\ntest content\nTest content\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add case.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add case test file"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['test'],
                    'ignoreCase' => true,
                ],
            ],
            'word-regexp-search' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'word.txt' => [
                                'lines' => [
                                    1 => 'password word boundary wordsmith',
                                ],
                                'matches' => [
                                    1 => 10,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/word.txt',
                        'content' => 'password word boundary wordsmith',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add word.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add word test file"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['word'],
                    'wordRegexp' => true,
                ],
            ],
            'files-with-matches-only' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'match1.txt',
                            'match2.txt',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/match1.txt',
                        'content' => 'This contains pattern',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/match2.txt',
                        'content' => 'Another pattern here',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/nomatch.txt',
                        'content' => 'No matching content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add pattern test files"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['pattern'],
                    'filesWithMatches' => true,
                ],
            ],
            'path-filter' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'src/main.php' => [
                                'lines' => [
                                    1 => '<?php echo "Hello World";',
                                ],
                                'matches' => [
                                    1 => 13,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/main.php',
                        'content' => '<?php echo "Hello World";',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/docs/readme.txt',
                        'content' => 'Hello documentation',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add source and docs"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['Hello'],
                    'paths' => ['src/'],
                ],
            ],
            'context-lines' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'context.txt' => [
                                'lines' => [
                                    '1' => 'line before',
                                    '2' => 'target line',
                                    '3' => 'line after',
                                ],
                                'matches' => [
                                    '2' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/context.txt',
                        'content' => "line before\ntarget line\nline after\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add context.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add context test file"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['target'],
                    'context' => 1,
                ],
            ],
            'no-matches-found' => [
                'expected' => [
                    'exitCode' => 1,
                    'artifacts' => [
                        'files' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/sample.txt',
                        'content' => 'This file contains no matching patterns',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add sample.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add sample file"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['nonexistent'],
                ],
            ],
            'max-count-limit' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'max-count.txt' => [
                                'lines' => [
                                    1 => 'match line 1',
                                    2 => 'match line 2',
                                ],
                                'matches' => [
                                    1 => 1,
                                    2 => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/max-count.txt',
                        'content' => "match line 1\nmatch line 2\nmatch line 3\nmatch line 4\n",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add max-count.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add max-count test file"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['match'],
                    'maxCount' => 2,
                ],
            ],
            'regex-pattern' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'regex.txt' => [
                                'lines' => [
                                    1 => 'test123',
                                    2 => 'test456',
                                    4 => 'test999',
                                ],
                                'matches' => [
                                    1 => 1,
                                    2 => 1,
                                    4 => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/regex.txt',
                        'content' => "test123\ntest456\nabc789\ntest999",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add regex.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add regex test file"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['test[0-9]+'],
                    'patternType' => 'extended',
                ],
            ],
            'subdirectory-search' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'subdir/nested.txt' => [
                                'lines' => [
                                    1 => 'nested content with target',
                                ],
                                'matches' => [
                                    1 => 21,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'mkdir -p {{ dirSafe }}/subdir',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/subdir/nested.txt',
                        'content' => 'nested content with target',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/root.txt',
                        'content' => 'root content without match',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add nested files"',
                    ],
                ],
                'properties' => [
                    'expressions' => ['target'],
                    'paths' => ['subdir/'],
                ],
            ],
            'untracked-files-search' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'files' => [
                            'untracked.txt' => [
                                'lines' => [
                                    1 => 'untracked search content',
                                ],
                                'matches' => [
                                    1 => 11,
                                ],
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/tracked.txt',
                        'content' => 'tracked content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add tracked.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add tracked file"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/untracked.txt',
                        'content' => 'untracked search content',
                    ],
                ],
                'properties' => [
                    'expressions' => ['search'],
                    'untracked' => true,
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new GrepFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
