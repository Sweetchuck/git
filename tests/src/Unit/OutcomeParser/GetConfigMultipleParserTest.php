<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\ConfigValueHandler;
use Sweetchuck\Git\OutcomeParser\GetConfigMultipleParser;
use Sweetchuck\Git\Tests\Unit\TestBase;

#[CoversClass(GetConfigMultipleParser::class)]
class GetConfigMultipleParserTest extends TestBase
{

    protected function createParser(): GetConfigMultipleParser
    {
        return new GetConfigMultipleParser(
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
                    'g1.k1' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'g1.k1',
                        'value.raw' => 'foo',
                        'value' => 'foo',
                    ],
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => "global\0file:/home/me/.gitconfig\0g1.k1\nfoo\0",
                    'stdError' => '',
                    'options' => [],
                ],
            ],
            'multiple' => [
                'expected' => [
                    'g1.k1' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'g1.k1',
                        'value.raw' => 'foo',
                        'value' => 'foo',
                    ],
                    'g1.k2' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'g1.k2',
                        'value.raw' => 'true',
                        'value' => true,
                    ],
                    'g1.k3' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'g1.k3',
                        'value.raw' => 'false',
                        'value' => false,
                    ],
                ],
                'args' => [
                    'exitCode' => 0,
                    'stdOutput' => implode(
                        '',
                        [
                            "global\0file:/home/me/.gitconfig\0g1.k1\nfoo\0",
                            "global\0file:/home/me/.gitconfig\0g1.k2\ntrue\0",
                            "global\0file:/home/me/.gitconfig\0g1.k3\nfalse\0",
                        ],
                    ),
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
