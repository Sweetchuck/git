<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\OutcomeParser\FormatParser;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormatParser::class)]
class FormatParserTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParseSuccessful(): array
    {
        $defaultOptions = [
            'definition' => [
                'key' => 'refName',
                'refSeparator' => 'ä',
                'propertySeparator' => 'ß',
                'keyValueSeparator' => ' ',
                'refPropertyMapping' => [
                    'refName' => 'refname:strip=0',
                    'upstream' => 'upstream:strip=0',
                    'track' => 'upstream:track',
                    'push' => 'push:strip=0',
                    'isCurrentBranch' => 'HEAD',

                ],
            ],
            'assetKey' => 'branches',
        ];

        return [
            'empty-string' => [
                'expected' => [
                    'branches' => [],
                ],
                'stdOutput' => '',
                'options' => $defaultOptions,
            ],
            'basic' => [
                'expected' => [
                    'branches' => [
                        'refs/heads/1.x' => [
                            'isCurrentBranch' => true,
                            'push' => 'refs/remotes/upstream/1.x',
                            'push.short' => 'upstream/1.x',
                            'refName' => 'refs/heads/1.x',
                            'refName.short' => '1.x',
                            'track' => '[ahead 1, behind 1]',
                            'track.ahead' => 1,
                            'track.behind' => 1,
                            'track.gone' => false,
                            'upstream' => 'refs/remotes/upstream/1.x',
                            'upstream.short' => 'upstream/1.x',
                        ],
                    ],
                ],
                'stdOutput' => implode(
                    '',
                    [
                        // phpcs:ignore
                        'refName refs/heads/1.xßupstream refs/remotes/upstream/1.xßtrack [ahead 1, behind 1]ßpush refs/remotes/upstream/1.xßisCurrentBranch *ä',
                    ],
                ),
                'options' => $defaultOptions,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $options
     */
    #[Test]
    #[DataProvider('casesParseSuccessful')]
    public function testParseSuccessful(
        array $expected,
        int $exitCode = 0,
        string $stdOutput = '',
        string $stdError = '',
        array $options = [],
    ): void {
        $parser = new FormatParser();
        $actual = $parser->parse($exitCode, $stdOutput, $stdError, $options);
        static::assertSame($expected, $actual);
    }
}
