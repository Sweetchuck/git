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
                'expected' => [
                    'value' => implode(
                        '&',
                        [
                            'myProp01 %(my.prop.01)',
                            'myProp02 %(my.prop.02)',
                            'refName %(refname:strip=0)',
                        ],
                    ) . '|',
                    'definition' => [
                        'key' => 'refName',
                        'refSeparator' => '|',
                        'propertySeparator' => '&',
                        'keyValueSeparator' => ' ',
                        'refPropertyMapping' => [
                            'myProp01' => 'my.prop.01',
                            'myProp02' => 'my.prop.02',
                            'refName' => 'refname:strip=0',
                        ],

                    ],
                ],
                'refPropertyMapping' => [
                    'myProp01' => 'my.prop.01',
                    'myProp02' => 'my.prop.02',
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $refPropertyMapping
     */
    #[Test]
    #[DataProvider('casesCreateMachineReadableFormatDefinition')]
    public function testCreateMachineReadableFormatDefinition(array $expected, array $refPropertyMapping): void
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

        static::assertSame($expected, $formatHandler->createMachineReadableFormatDefinition($refPropertyMapping));
    }
}
