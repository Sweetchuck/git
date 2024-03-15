<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\OutcomeParser\GetVersionParser;

class GetVersionParserTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParseSuccessful(): array
    {
        return [
            'non-zero exit code' => [
                'expected' => null,
                'args' => [1, "git version 2.40.1\n", "Some error occurred\n"],
            ],
            'normal' => [
                'expected' => ['version' => '2.40.1'],
                'args' => [0, "git version 2.40.1\n", ''],
            ],
        ];
    }

    /**
     * @param null|array<string, mixed> $expected
     * @param array<string, mixed> $args
     */
    #[Test]
    #[DataProvider('casesParseSuccessful')]
    public function testParseSuccessful(?array $expected, array $args): void
    {
        $parser = new GetVersionParser();
        static::assertSame($expected, $parser->parse(...$args));
    }
}
