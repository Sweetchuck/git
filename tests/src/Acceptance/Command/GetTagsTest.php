<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetTags;

#[CoversClass(GetTags::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-tag')]
class GetTagsTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [
                        'tags' => [],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                ],
            ],
            'single-tag' => [
                'expected' => [
                    'artifacts' => [
                        'tags' => [
                            'refs/tags/v1.0.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v1.0.0',
                                'refName.short' => 'v1.0.0',
                                'taggerDate' => null,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.0.0',
                    ],
                ],
                'properties' => [],
            ],
            'multiple-tags' => [
                'expected' => [
                    'artifacts' => [
                        'tags' => [
                            'refs/tags/v1.0.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v1.0.0',
                                'refName.short' => 'v1.0.0',
                                'taggerDate' => null,
                            ],
                            'refs/tags/v1.1.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v1.1.0',
                                'refName.short' => 'v1.1.0',
                                'taggerDate' => null,
                            ],
                            'refs/tags/v2.0.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v2.0.0',
                                'refName.short' => 'v2.0.0',
                                'taggerDate' => null,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.0.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && echo "New content" >> README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Update README"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.1.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && echo "Major version" >> README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Major version update"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v2.0.0',
                    ],
                ],
                'properties' => [],
            ],
            'with-points-at-option' => [
                'expected' => [
                    'artifacts' => [
                        'tags' => [
                            'refs/tags/v1.1.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v1.1.0',
                                'refName.short' => 'v1.1.0',
                                'taggerDate' => null,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.0.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && echo "New content" >> README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Update README"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.1.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && echo "Major version" >> README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Major version update"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v2.0.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout HEAD~1',
                    ],
                ],
                'properties' => [
                    'pointsAt' => 'HEAD',
                ],
            ],
            'with-contains-option' => [
                'expected' => [
                    'artifacts' => [
                        'tags' => [
                            'refs/tags/v1.1.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v1.1.0',
                                'refName.short' => 'v1.1.0',
                                'taggerDate' => null,
                            ],
                            'refs/tags/v2.0.0' => [
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v2.0.0',
                                'refName.short' => 'v2.0.0',
                                'taggerDate' => null,
                            ],
                        ],
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.0.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && echo "New content" >> README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Update README"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v1.1.0',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && echo "Major version" >> README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Major version update"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git tag v2.0.0',
                    ],
                ],
                'properties' => [
                    'contains' => [
                        'HEAD~1' => true,
                    ],
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

        $command = new GetTags();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame(
            array_keys($expected['artifacts']['tags']),
            array_keys($result->artifacts['tags']),
            'The tags are not the same',
        );

        foreach ($expected['artifacts']['tags'] as $tag => $tagData) {
            static::assertMatchesRegularExpression(
                '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} [+-]\d{4}$/',
                $result->artifacts['tags'][$tag]['creatorDate'],
            );
            static::assertMatchesRegularExpression(
                '/^[a-z0-9]{40}$/i',
                $result->artifacts['tags'][$tag]['objectName'],
            );

            unset(
                $result->artifacts['tags'][$tag]['creatorDate'],
                $result->artifacts['tags'][$tag]['objectName'],
            );
            static::assertSame(
                $tagData,
                $result->artifacts['tags'][$tag],
                "Tag $tag data is not the same",
            );
        }
    }
}
