<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetFilesInTree;
use Sweetchuck\Git\ObjectType;
use Sweetchuck\Git\OutcomeParser\FormatParser;

#[CoversClass(GetFilesInTree::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(FormatParser::class)]
#[Group('command-git-ls-tree')]
class GetFilesInTreeTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init --initial-branch="main" {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'empty-tree' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit --allow-empty -m "Initial empty commit"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                ],
            ],
            'basic-files' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'file1.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'file1.txt',
                            ],
                            'file2.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'file2.txt',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'Content of file 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'Content of file 2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file1.txt file2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add test files"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                ],
            ],
            'with-directories-non-recursive' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'dir1' => [
                                'objectMode' => '040000',
                                'objectName' => self::expectString(),
                                'objectSize' => null,
                                'objectType' => ObjectType::Tree->value,
                                'path' => 'dir1',
                            ],
                            'dir2' => [
                                'objectMode' => '040000',
                                'objectName' => self::expectString(),
                                'objectSize' => null,
                                'objectType' => ObjectType::Tree->value,
                                'path' => 'dir2',
                            ],
                            'root-file.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'root-file.txt',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/root-file.txt',
                        'content' => 'Root file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir dir1 dir2',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir1/file1.txt',
                        'content' => 'Directory 1 file content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir2/file2.txt',
                        'content' => 'Directory 2 file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add files and directories"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                ],
            ],
            'with-directories-recursive' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'dir1/file1.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'dir1/file1.txt',
                            ],
                            'dir1/subdir/nested.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'dir1/subdir/nested.txt',
                            ],
                            'dir2/file2.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'dir2/file2.txt',
                            ],
                            'root-file.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'root-file.txt',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/root-file.txt',
                        'content' => 'Root file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir -p dir1/subdir dir2',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir1/file1.txt',
                        'content' => 'Directory 1 file content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir1/subdir/nested.txt',
                        'content' => 'Nested file content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/dir2/file2.txt',
                        'content' => 'Directory 2 file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add nested directory structure"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                    'recursive' => true,
                ],
            ],
            'with-path-filter' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'include/file1.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'include/file1.txt',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir include exclude',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/include/file1.txt',
                        'content' => 'Included file content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/exclude/file2.txt',
                        'content' => 'Excluded file content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add files in different directories"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                    'recursive' => true,
                    'paths' => ['include'],
                ],
            ],
            'specific-branch' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'branch-file.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'branch-file.txt',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/main-file.txt',
                        'content' => 'Main branch file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add main-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add main file"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout -b feature-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && rm main-file.txt',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/branch-file.txt',
                        'content' => 'Feature branch file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add -A',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Replace with branch file"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'feature-branch',
                ],
            ],
            'executable-file' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'script.sh' => [
                                'objectMode' => '100755',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'script.sh',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/script.sh',
                        'content' => "#!/bin/bash\necho 'Hello World'",
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && chmod +x script.sh',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add script.sh',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add executable script"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                ],
            ],
            'multiple-paths-filter' => [
                'expected' => [
                    'artifacts' => [
                        'paths' => [
                            'docs/readme.txt' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'docs/readme.txt',
                            ],
                            'src/main.php' => [
                                'objectMode' => '100644',
                                'objectName' => self::expectString(),
                                'objectSize' => self::expectInt(),
                                'objectType' => ObjectType::Blob->value,
                                'path' => 'src/main.php',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir src docs tests',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/main.php',
                        'content' => '<?php echo "Hello";',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/docs/readme.txt',
                        'content' => 'Documentation',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/tests/test.php',
                        'content' => '<?php // test',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add project structure"',
                    ],
                ],
                'properties' => [
                    'treeish' => 'HEAD',
                    'recursive' => true,
                    'paths' => ['src', 'docs'],
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

        $command = new GetFilesInTree();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)
            && array_key_exists('paths', $expected['artifacts'])
        ) {
            static::assertArrayHasKey('paths', $result->artifacts);
            $actualPaths = $result->artifacts['paths'];
            $expectedPaths = $expected['artifacts']['paths'];

            static::assertSameSize($expectedPaths, $actualPaths);

            foreach ($expectedPaths as $path => $expectedData) {
                static::assertArrayHasKey($path, $actualPaths);
                $actualData = $actualPaths[$path];

                foreach ($expectedData as $key => $expectedValue) {
                    static::assertArrayHasKey($key, $actualData);

                    switch ($expectedValue) {
                        case '__EXPECT_STRING__':
                            static::assertIsString($actualData[$key]);
                            static::assertNotEmpty($actualData[$key]);
                            break;

                        case '__EXPECT_INT__':
                            static::assertIsInt($actualData[$key]);
                            static::assertGreaterThanOrEqual(0, $actualData[$key]);
                            break;

                        default:
                            static::assertSame($expectedValue, $actualData[$key]);
                            break;
                    }
                }
            }
        }
    }
}
