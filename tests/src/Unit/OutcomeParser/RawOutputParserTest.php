<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\OutcomeParser\RawOutputParser;
use Sweetchuck\Git\OutcomeParserInterface;

#[CoversClass(RawOutputParser::class)]
class RawOutputParserTest extends TestBase
{
    protected function createParser(): OutcomeParserInterface
    {
        return new RawOutputParser();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesParse(): array
    {
        return [
            'empty-default-keys' => [
                'expected' => [
                    'stdOutput' => '',
                    'stdError' => '',
                ],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'with-content-default-keys' => [
                'expected' => [
                    'stdOutput' => 'This is standard output',
                    'stdError' => 'This is standard error',
                ],
                'exitCode' => 0,
                'stdOutput' => 'This is standard output',
                'stdError' => 'This is standard error',
                'options' => [],
            ],
            'with-content-runtime-custom-keys' => [
                'expected' => [
                    'a' => 'This is standard output',
                    'b' => 'This is standard error',
                ],
                'exitCode' => 0,
                'stdOutput' => 'This is standard output',
                'stdError' => 'This is standard error',
                'options' => [
                    'stdOutputKey' => 'a',
                    'stdErrorKey' => 'b',
                ],
            ],
            'stdErrorKey is null' => [
                'expected' => [
                    'a' => 'This is standard output',
                ],
                'exitCode' => 0,
                'stdOutput' => 'This is standard output',
                'stdError' => 'This is standard error',
                'options' => [
                    'stdOutputKey' => 'a',
                    'stdErrorKey' => null,
                ],
            ],
            'stdOutputKey is null' => [
                'expected' => [
                    'b' => 'This is standard error',
                ],
                'exitCode' => 0,
                'stdOutput' => 'This is standard output',
                'stdError' => 'This is standard error',
                'options' => [
                    'stdOutputKey' => null,
                    'stdErrorKey' => 'b',
                ],
            ],
        ];
    }
}
