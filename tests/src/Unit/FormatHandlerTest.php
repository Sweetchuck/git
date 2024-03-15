<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\FormatHandler;

#[CoversClass(FormatHandler::class)]
class FormatHandlerTest extends TestBase
{

    #[Test]
    public function testGetFinalUniqueIdGenerator(): void
    {
        $formatHandler = new FormatHandler();
        $uniqueIdGenerator = $formatHandler->getFinalUniqueIdGenerator();

        static::assertNotEquals(
            $uniqueIdGenerator(),
            $uniqueIdGenerator(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesCreateMachineReadableFormatDefinition(): array
    {
        return [
            'basic' => [
                [
                    'key' => 'refName',
                    'refSeparator' => '|',
                    'propertySeparator' => '&',
                    'keyValueSeparator' => ' ',
                    'properties' => [
                        'myProp01' => 'my.prop.01',
                        'myProp02' => 'my.prop.02',
                        'refName' => 'refname:strip=0',
                    ],
                    'format' => implode(
                        '&',
                        [
                            'myProp01 %(my.prop.01)',
                            'myProp02 %(my.prop.02)',
                            'refName %(refname:strip=0)',
                        ],
                    ) . '|',
                ],
                [
                    'myProp01' => 'my.prop.01',
                    'myProp02' => 'my.prop.02',
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesCreateMachineReadableFormatDefinition')]
    public function testCreateMachineReadableFormatDefinition(array $expected, array $properties): void
    {
        $uniqueIds = ['|', '&'];
        $uniqueIdGenerator = function () use (&$uniqueIds): string {
            if (empty($uniqueIds)) {
                throw new \LogicException('No more unique ids available');
            }

            return array_shift($uniqueIds);
        };
        $formatHandler = new FormatHandler();
        $formatHandler->setUniqueIdGenerator($uniqueIdGenerator);

        static::assertSame($expected, $formatHandler->createMachineReadableFormatDefinition($properties));
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesParseStdOutput(): array
    {
        return [
            'basic' => [
                [
                    '1' => [
                        'a' => '1',
                        'a.short' => '1',
                        'b' => false,
                        'c' => 'behind 42',
                        'c.ahead' => null,
                        'c.behind' => 42,
                        'c.gone' => false,
                    ],
                    '4' => [
                        'a' => '4',
                        'a.short' => '4',
                        'b' => true,
                        'c' => 'ahead 8',
                        'c.ahead' => 8,
                        'c.behind' => null,
                        'c.gone' => false,
                    ],
                    '5' => [
                        'a' => '5',
                        'a.short' => '5',
                        'b' => true,
                        'c' => '6',
                        'c.ahead' => null,
                        'c.behind' => null,
                        'c.gone' => false,
                    ],
                ],
                implode("!\n", [
                    'a 1|b 0|c behind 42',
                    'a 4|b 1|c ahead 8',
                    'a 5|b 1|c 6',
                    '',
                ]),
                [
                    'key' => 'a',
                    'refSeparator' => '!',
                    'propertySeparator' => '|',
                    'keyValueSeparator' => ' ',
                    'properties' => [
                        'a' => 'refname',
                        'b' => 'HEAD',
                        'c' => 'upstream:track',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<mixed> $expected
     * @param array<string, mixed> $definition
     */
    #[Test]
    #[DataProvider('casesParseStdOutput')]
    public function testParseStdOutput(array $expected, string $stdOutput, array $definition): void
    {
        static::assertSame(
            $expected,
            (new FormatHandler())->parseStdOutput($stdOutput, $definition),
        );
    }
}
