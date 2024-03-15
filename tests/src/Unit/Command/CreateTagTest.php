<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CreateTag;

#[CoversClass(CreateTag::class)]
#[Group('command-git-tag')]
class CreateTagTest extends CommandTestBase
{

    protected function createCommand(): CreateTag
    {
        return new CreateTag();
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
                    'my-new-tag-01',
                ],
                'properties' => [
                    'name' => 'my-new-tag-01',
                ],
            ],
            'with-message' => [
                'expected' => [
                    'git',
                    'tag',
                    '--message=Release v1.0.0',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'message' => 'Release v1.0.0',
                ],
            ],
            'with-force' => [
                'expected' => [
                    'git',
                    'tag',
                    '--force',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'force' => true,
                ],
            ],
            'with-message-and-force' => [
                'expected' => [
                    'git',
                    'tag',
                    '--force',
                    '--message=Release v1.0.0',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'message' => 'Release v1.0.0',
                    'force' => true,
                ],
            ],
            'with-annotate' => [
                'expected' => [
                    'git',
                    'tag',
                    '--annotate',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'annotate' => true,
                ],
            ],
            'with-sign' => [
                'expected' => [
                    'git',
                    'tag',
                    '--sign',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'sign' => true,
                ],
            ],
            'with-no-sign' => [
                'expected' => [
                    'git',
                    'tag',
                    '--no-sign',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'noSign' => true,
                ],
            ],
            'with-local-user' => [
                'expected' => [
                    'git',
                    'tag',
                    '--local-user=john@example.com',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'localUser' => 'john@example.com',
                ],
            ],
            'with-all-options' => [
                'expected' => [
                    'git',
                    'tag',
                    '--annotate',
                    '--force',
                    '--local-user=john@example.com',
                    '--message=Release v1.0.0',
                    '--sign',
                    'v1.0.0',
                ],
                'properties' => [
                    'name' => 'v1.0.0',
                    'annotate' => true,
                    'message' => 'Release v1.0.0',
                    'sign' => true,
                    'localUser' => 'john@example.com',
                    'force' => true,
                ],
            ],
            'with-git-dir' => [
                'expected' => [
                    'git',
                    '--git-dir=/a/b/.git',
                    'tag',
                    'v1.0.0',
                ],
                'properties' => [
                    'gitDir' => '/a/b/.git',
                    'name' => 'v1.0.0',
                ],
            ],
        ];
    }
}
