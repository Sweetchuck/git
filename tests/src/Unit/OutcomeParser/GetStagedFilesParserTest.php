<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParser\GetChangedFilesParser;
use Sweetchuck\Git\Struct\ChangedFile;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(GetChangedFilesParser::class)]
class GetStagedFilesParserTest extends TestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                'expected' => [
                    'files' => [],
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                    'options' => [],
                ],
            ],
            'basic' => [
                'expected' => [
                    'files' => [
                        './a.php' => new ChangedFile(
                            './a.php',
                            FileStatus::Unmerged,
                        ),
                        './b.php' => new ChangedFile(
                            './b.php',
                            FileStatus::UnstagedRemoval,
                        ),
                    ],
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => implode('', [
                        "M\0a.php\0",
                        "R\0b.php\0",
                    ]),
                    'stdError' => '',
                    'options' => [],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $args
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(array $expected, array $args): void
    {
        $parser = new GetChangedFilesParser();
        static::assertEquals(
            $expected,
            $parser->parse(...$args),
        );
    }
}
