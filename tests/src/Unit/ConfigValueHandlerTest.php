<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\ConfigValueHandler;

#[CoversClass(ConfigValueHandler::class)]
class ConfigValueHandlerTest extends TestBase
{

    protected function createHandler(): ConfigValueHandler
    {
        return new ConfigValueHandler();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function casesTransform(): array
    {
        return [
            'unknown - string' => [
                'expected' => 'bar',
                'name' => 'foo',
                'value' => 'bar',
            ],
            'unknown - true' => [
                'expected' => true,
                'name' => 'foo',
                'value' => 'true',
            ],
            'unknown - false' => [
                'expected' => false,
                'name' => 'foo',
                'value' => 'false',
            ],
        ];
    }

    #[Test]
    #[DataProvider('casesTransform')]
    public function testTransform(mixed $expected, string $name, string $value): void
    {
        $handler = $this->createHandler();
        static::assertSame($expected, $handler->transform($name, $value));
    }
}
