<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\DeleteTag;

#[CoversClass(DeleteTag::class)]
#[Group('command-git-tag')]
class DeleteTagTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        return new DeleteTag();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'tag',
                    '--delete',
                ],
                'properties' => [],
            ],
            'single-tag' => [
                'expected' => [
                    'git',
                    'tag',
                    '--delete',
                    '--',
                    'v1.2.3',
                ],
                'properties' => [
                    'names' => ['v1.2.3'],
                ],
            ],
            'single-tag-bool' => [
                'expected' => [
                    'git',
                    'tag',
                    '--delete',
                    '--',
                    'v1.2.3',
                ],
                'properties' => [
                    'names' => ['v1.2.3' => true],
                ],
            ],
            'multiple-tags-strings' => [
                'expected' => [
                    'git',
                    'tag',
                    '--delete',
                    '--',
                    'v1.2.3',
                    'v2.0.0',
                ],
                'properties' => [
                    'names' => ['v1.2.3', 'v2.0.0'],
                ],
            ],
            'names-array-bool' => [
                'expected' => [
                    'git',
                    'tag',
                    '--delete',
                    '--',
                    'v1.2.3',
                    'v3.0.0',
                ],
                'properties' => [
                    'names' => [
                        'v1.2.3' => true,
                        'v2.0.0' => false,
                        'v3.0.0' => true,
                    ],
                ],
            ],
            'with-git-dir' => [
                'expected' => [
                    'git',
                    '--git-dir=/a/b/.git',
                    'tag',
                    '--delete',
                    '--',
                    'v1.2.3',
                ],
                'properties' => [
                    'gitDir' => '/a/b/.git',
                    'names' => ['v1.2.3'],
                ],
            ],
        ];
    }
}
