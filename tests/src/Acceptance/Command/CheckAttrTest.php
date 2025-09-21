<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CheckAttr;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\OutcomeParser\CheckAttrParser;

#[CoversClass(CheckAttr::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(CheckAttrParser::class)]
#[Group('command-git-check-attr')]
class CheckAttrTest extends CommandTestBase
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

        $gitAttributesContent = <<<'TEXT'
            *.txt text eol=lf
            *.md linguist-documentation
            *.jpg binary
            secret.txt filter=secret

            TEXT;

        return [
            'basic attributes check' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'readme.txt' => [
                                'text' => 'set',
                                'eol' => 'lf',
                                'linguist-documentation' => 'unspecified',
                            ],
                            'doc.md' => [
                                'text' => 'unspecified',
                                'eol' => 'unspecified',
                                'linguist-documentation' => 'set',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => $gitAttributesContent,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/readme.txt',
                        'content' => 'This is a readme file',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/doc.md',
                        'content' => '# Documentation',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add . \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                ],
                'properties' => [
                    'attributes' => [
                        'text',
                        'eol',
                        'linguist-documentation'
                    ],
                    'paths' => [
                        'readme.txt',
                        'doc.md',
                    ],
                ],
            ],
            'single attribute check' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'readme.txt' => [
                                'text' => 'set',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => $gitAttributesContent,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/readme.txt',
                        'content' => 'This is a readme file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add . \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                ],
                'properties' => [
                    'attributes' => ['text'],
                    'paths' => ['readme.txt'],
                ],
            ],
            'all attributes check' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'readme.txt' => [
                                'text' => 'set',
                                'eol' => 'lf',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => $gitAttributesContent,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/readme.txt',
                        'content' => 'This is a readme file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add . \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                ],
                'properties' => [
                    'paths' => ['readme.txt'],
                ],
            ],
            'binary file check' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'image.jpg' => [
                                'binary' => 'set',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => $gitAttributesContent,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/image.jpg',
                        'content' => 'fake-jpeg-content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add . \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                ],
                'properties' => [
                    'attributes' => ['binary'],
                    'paths' => ['image.jpg'],
                ],
            ],
            'cached option' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'readme.txt' => [
                                'text' => 'set',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => $gitAttributesContent,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/readme.txt',
                        'content' => 'This is a readme file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add . \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                    // Modify .gitattributes after commit
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => '*.txt binary',
                    ],
                ],
                'properties' => [
                    'cached' => true,
                    'attributes' => ['text'],
                    'paths' => ['readme.txt'],
                ],
            ],
            'source option with specific tree' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'filePaths' => [
                            'readme.txt' => [
                                'text' => 'set',
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/.gitattributes',
                        'content' => $gitAttributesContent,
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/readme.txt',
                        'content' => 'This is a readme file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }} \
                            && git add . \
                            && git commit --message "Initial commit"
                            SHELL,
                    ],
                ],
                'properties' => [
                    'source' => 'HEAD',
                    'attributes' => ['text'],
                    'paths' => ['readme.txt'],
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
        ?array $expected,
        array $initSteps,
        array $properties = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new CheckAttr();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesSetAttributes(): array
    {
        return [
            'array with string keys' => [
                'expected' => ['text' => true, 'binary' => false],
                'attributes' => ['text' => true, 'binary' => false],
            ],
            'array with numeric keys' => [
                'expected' => ['text' => true, 'binary' => true],
                'attributes' => ['text', 'binary'],
            ],
        ];
    }

    /**
     * @param array<string, bool> $expected
     * @param array<string>|array<string, bool> $attributes
     */
    #[Test]
    #[DataProvider('casesSetAttributes')]
    public function testSetAttributes(array $expected, array $attributes): void
    {
        $command = new CheckAttr();
        $command->setAttributes($attributes);

        static::assertSame($expected, $command->getAttributes());
    }

    #[Test]
    public function testUpdateAttributes(): void
    {
        $command = new CheckAttr();
        $command->setAttributes(['text' => true, 'binary' => false]);
        $command->updateAttributes(['eol' => true, 'text' => false]);

        $expected = [
            'text' => false,
            'binary' => false,
            'eol' => true,
        ];

        static::assertSame($expected, $command->getAttributes());
    }

    #[Test]
    public function testAddAttribute(): void
    {
        $command = new CheckAttr();
        $command->addAttribute('text');
        $command->addAttribute('eol');

        $expected = [
            'text' => true,
            'eol' => true,
        ];

        static::assertSame($expected, $command->getAttributes());
    }

    #[Test]
    public function testRemoveAttribute(): void
    {
        $command = new CheckAttr();
        $command->setAttributes(['text', 'binary', 'eol']);
        $command->removeAttribute('binary');

        $expected = [
            'text' => true,
            'eol' => true,
        ];

        static::assertSame($expected, $command->getAttributes());
    }
}
