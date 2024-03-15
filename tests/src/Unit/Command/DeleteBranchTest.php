<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\DeleteBranch;

#[CoversClass(DeleteBranch::class)]
#[Group('command-git-branch')]
class DeleteBranchTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        return new DeleteBranch();
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
                    'branch',
                    '--delete',
                ],
                'properties' => [],
            ],
            'single-branch' => [
                'expected' => [
                    'git',
                    'branch',
                    '--delete',
                    '--',
                    'feature-branch',
                ],
                'properties' => [
                    'names' => ['feature-branch'],
                ],
            ],
            'single-branch-bool' => [
                'expected' => [
                    'git',
                    'branch',
                    '--delete',
                    '--',
                    'feature-branch',
                ],
                'properties' => [
                    'names' => ['feature-branch' => true],
                ],
            ],
            'multiple-branches-strings' => [
                'expected' => [
                    'git',
                    'branch',
                    '--delete',
                    '--',
                    'feature-branch',
                    'old-branch',
                ],
                'properties' => [
                    'names' => ['feature-branch', 'old-branch'],
                ],
            ],
            'names-array-bool' => [
                'expected' => [
                    'git',
                    'branch',
                    '--delete',
                    '--',
                    'feature-branch',
                    'another-branch',
                ],
                'properties' => [
                    'names' => [
                        'feature-branch' => true,
                        'old-branch' => false,
                        'another-branch' => true,
                    ],
                ],
            ],
            'with-force-true' => [
                'expected' => [
                    'git',
                    'branch',
                    '-D',
                    '--',
                    'unmerged-branch',
                ],
                'properties' => [
                    'names' => ['unmerged-branch'],
                    'force' => true,
                ],
            ],
            'with-force-false' => [
                'expected' => [
                    'git',
                    'branch',
                    '--delete',
                    '--',
                    'unmerged-branch',
                ],
                'properties' => [
                    'names' => ['unmerged-branch'],
                    'force' => false,
                ],
            ],
            'with-git-dir' => [
                'expected' => [
                    'git',
                    '--git-dir=/a/b/.git',
                    'branch',
                    '--delete',
                    '--',
                    'feature-branch',
                ],
                'properties' => [
                    'gitDir' => '/a/b/.git',
                    'names' => ['feature-branch'],
                ],
            ],
            'with-force-and-git-dir' => [
                'expected' => [
                    'git',
                    '--git-dir=/a/b/.git',
                    'branch',
                    '-D',
                    '--',
                    'unmerged-branch',
                ],
                'properties' => [
                    'gitDir' => '/a/b/.git',
                    'names' => ['unmerged-branch'],
                    'force' => true,
                ],
            ],
        ];
    }
}
