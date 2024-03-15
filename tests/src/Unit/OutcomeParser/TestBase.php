<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Tests\Unit\TestBase as UnitTestBase;

abstract class TestBase extends UnitTestBase
{

    abstract protected function createParser(): OutcomeParserInterface;

    /**
     * @return array<string, mixed>
     */
    abstract public static function casesParse(): array;

    /**
     * @phpstan-param array<mixed> $expected
     * @phpstan-param array<string, mixed> $options
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(
        array $expected,
        int $exitCode,
        string $stdOutput,
        string $stdError = '',
        array $options = [],
    ): void {
        $parser = $this->createParser();
        static::assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError, $options));
    }
}
