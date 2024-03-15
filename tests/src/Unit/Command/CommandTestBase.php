<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\ProcessFactory;
use Sweetchuck\Git\ProcessFactoryInterface;
use Sweetchuck\Git\Tests\Helper\DummyProcess;
use Sweetchuck\Git\Tests\Unit\TestBase;

abstract class CommandTestBase extends TestBase
{

    /**
     * @return array<string, mixed>
     */
    abstract public static function casesGetCliCommand(): array;

    /**
     * @param array<string> $expected
     * @param array<string, mixed> $options
     */
    #[Test]
    #[DataProvider('casesGetCliCommand')]
    public function testGetCliCommand(array $expected, array $options): void
    {
        $command = $this->createCommand();
        $command->setOptions($options);
        static::assertSame($expected, $command->getCliCommand());
    }

    abstract protected function createCommand(): CliCommandInterface;

    /**
     * @param array<array<string, mixed>> $processOutcomes
     */
    protected function createProcessFactory(array $processOutcomes): ProcessFactoryInterface
    {
        $processFactory = $this->createMock(ProcessFactory::class);
        $processFactory
            ->expects($this->exactly(count($processOutcomes)))
            ->method('createProcess')
            ->willReturnCallback(function () use (&$processOutcomes): DummyProcess {
                $processOutcome = (array) array_shift($processOutcomes);
                $process = new DummyProcess([]);
                $process->setOutcome(...$processOutcome);

                return $process;
            });

        return $processFactory;
    }
}
