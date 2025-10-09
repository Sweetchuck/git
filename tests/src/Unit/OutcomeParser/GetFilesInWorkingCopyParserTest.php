<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParser\GetFilesInWorkingCopyParser;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(GetFilesInWorkingCopyParser::class)]
class GetFilesInWorkingCopyParserTest extends TestBase
{
    protected function createParser(): GetFilesInWorkingCopyParser
    {
        return new GetFilesInWorkingCopyParser();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                'expected' => [],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                    'options' => [],
                ],
            ],
            'error - valid stdOutput but invalid exitCode' => [
                'expected' => null,
                'args' => [
                    'exitCode' => 1,
                    'stdOutput' => "H i/lf    w/lf    attr/                 	.circleci/config.yml\0",
                    'stdError' => 'fatal: not a git repository',
                    'options' => [],
                ],
            ],
            'basic' => [
                'expected' => [
                    '.circleci/config.yml' => [
                        'status' => FileStatus::Tracked,
                        'statusChar' => 'H',
                        'path' => '.circleci/config.yml',
                        'attributes' => [
                            'i' => 'lf',
                            'w' => 'lf',
                            'attr' => '',
                        ],
                    ],
                    '.editorconfig' => [
                        'status' => FileStatus::Tracked,
                        'statusChar' => 'H',
                        'path' => '.editorconfig',
                        'attributes' => [
                            'i' => 'lf',
                            'w' => 'lf',
                            'attr' => '',
                        ],
                    ],
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => implode(
                        '',
                        [
                            "H i/lf    w/lf    attr/                 \t.circleci/config.yml\0",
                            "H i/lf    w/lf    attr/                 \t.editorconfig\0",
                        ],
                    ),
                    'stdError' => '',
                ],
            ],
            'multiple-status-types' => [
                'expected' => [
                    'tracked.php' => [
                        'status' => FileStatus::Tracked,
                        'statusChar' => 'H',
                        'path' => 'tracked.php',
                        'attributes' => [
                            'i' => 'lf',
                            'w' => 'lf',
                            'attr' => '',
                        ],
                    ],
                    'untracked.php' => [
                        'status' => FileStatus::Untracked,
                        'statusChar' => '?',
                        'path' => 'untracked.php',
                        'attributes' => [
                            'i' => 'lf',
                            'w' => 'lf',
                            'attr' => '',
                        ],
                    ],
                    'unmerged.php' => [
                        'status' => FileStatus::Unmerged,
                        'statusChar' => 'M',
                        'path' => 'unmerged.php',
                        'attributes' => [
                            'i' => 'lf',
                            'w' => 'lf',
                            'attr' => '',
                        ],
                    ],
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => implode(
                        '',
                        [
                            "H i/lf    w/lf    attr/                 \ttracked.php\0",
                            "? i/lf    w/lf    attr/                 \tuntracked.php\0",
                            "M i/lf    w/lf    attr/                 \tunmerged.php\0",
                        ],
                    ),
                    'stdError' => '',
                ],
            ],
        ];
    }

    /**
     * @param null|array<string, array<string, mixed>> $expected
     * @param array<string, mixed> $args
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(
        ?array $expected,
        array $args,
    ): void {
        $parser = $this->createParser();
        $actual = $parser->parse(
            $args['exitCode'],
            $args['stdOutput'],
            $args['stdError'],
            $args['options'] ?? [],
        );

        static::assertSame($expected, $actual);
    }
}
