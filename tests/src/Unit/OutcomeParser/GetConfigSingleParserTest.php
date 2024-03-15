<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\ConfigValueHandler;
use Sweetchuck\Git\OutcomeParser\GetConfigSingleParser;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(GetConfigSingleParser::class)]
class GetConfigSingleParserTest extends TestBase
{

    protected function createParser(): GetConfigSingleParser
    {
        return new GetConfigSingleParser(
            new ConfigValueHandler(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        return [
            'empty' => [
                'expected' => null,
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                    'options' => [],
                ],
            ],
            'single' => [
                'expected' => [
                    'scope' => 'global',
                    'origin' => 'file:/home/me/.gitconfig',
                    'name' => 'g1.k1',
                    'value.raw' => 'foo',
                    'value' => 'foo',
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "global\0file:/home/me/.gitconfig\0g1.k1\nfoo\0",
                    'stdError' => '',
                    'options' => [],
                ],
            ],
        ];
    }

    /**
     * @param null|array<string, mixed> $expected
     * @param array<string, mixed> $args
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(?array $expected, array $args): void
    {
        $parser = $this->createParser();
        static::assertSame($expected, $parser->parse(...$args));
    }
}
