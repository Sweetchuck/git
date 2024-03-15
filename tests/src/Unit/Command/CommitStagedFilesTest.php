<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CommitStagedFiles;

#[CoversClass(CommitStagedFiles::class)]
#[Group('command-git-commit')]
class CommitStagedFilesTest extends CommandTestBase
{
    protected function createCommand(): CommitStagedFiles
    {
        return new CommitStagedFiles();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'commit'],
                'properties' => [],
            ],
            'message' => [
                'expected' => ['git', 'commit', '--message=Fix bug'],
                'properties' => [
                    'message' => 'Fix bug',
                ],
            ],
            'author and date' => [
                'expected' => ['git', 'commit', '--author=Alice <alice@example.com>', '--date=2024-01-01'],
                'properties' => [
                    'author' => 'Alice <alice@example.com>',
                    'date' => '2024-01-01',
                ],
            ],
            'amend' => [
                'expected' => ['git', 'commit', '--amend'],
                'properties' => [
                    'amend' => true,
                ],
            ],
            'signoff' => [
                'expected' => ['git', 'commit', '--signoff'],
                'properties' => [
                    'signoff' => true,
                ],
            ],
            'no-verify' => [
                'expected' => ['git', 'commit', '--no-verify'],
                'properties' => [
                    'noVerify' => true,
                ],
            ],
            'allow-empty' => [
                'expected' => ['git', 'commit', '--allow-empty'],
                'properties' => [
                    'allowEmpty' => true,
                ],
            ],
            'edit' => [
                'expected' => ['git', 'commit', '--edit'],
                'properties' => [
                    'edit' => true,
                ],
            ],
            'file' => [
                'expected' => ['git', 'commit', '--file=commit-msg.txt'],
                'properties' => [
                    'file' => 'commit-msg.txt',
                ],
            ],
            'template' => [
                'expected' => ['git', 'commit', '--template=.gitmessage'],
                'properties' => [
                    'template' => '.gitmessage',
                ],
            ],
            'paths' => [
                'expected' => ['git', 'commit', '--', 'src/', 'tests/'],
                'properties' => [
                    'paths' => ['src/', 'tests/'],
                ],
            ],
            'multiple options' => [
                'expected' => [
                    'git',
                    'commit',
                    '--message=Initial commit',
                    '--author=John Doe <john@example.com>',
                    '--signoff',
                    '--no-verify',
                    '--',
                    'README.md',
                ],
                'properties' => [
                    'message' => 'Initial commit',
                    'author' => 'John Doe <john@example.com>',
                    'signoff' => true,
                    'noVerify' => true,
                    'paths' => ['README.md'],
                ],
            ],
        ];
    }
}
