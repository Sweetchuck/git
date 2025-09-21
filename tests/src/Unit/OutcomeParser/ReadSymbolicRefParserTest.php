<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\OutcomeParser\ReadSymbolicRefParser;
use Sweetchuck\Git\OutcomeParserInterface;

#[CoversClass(ReadSymbolicRefParser::class)]
class ReadSymbolicRefParserTest extends TestBase
{
    protected function createParser(): OutcomeParserInterface
    {
        return new ReadSymbolicRefParser();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesParse(): array
    {
        return [
            'exitCode-error' => [
                'expected' => null,
                'exitCode' => 2,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'empty' => [
                'expected' => null,
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'empty-with-whitespace' => [
                'expected' => null,
                'exitCode' => 0,
                'stdOutput' => "\n\r\n",
                'stdError' => '',
                'options' => [],
            ],
            'basic branch' => [
                'expected' => [
                    'name.full' => 'refs/heads/feature/issue-42',
                    'name.short' => 'feature/issue-42',
                    'type' => 'heads',
                ],
                'exitCode' => 0,
                'stdOutput' => "refs/heads/feature/issue-42\n",
                'stdError' => '',
                'options' => [],
            ],
            'basic tags' => [
                'expected' => [
                    'name.full' => 'refs/tags/v1.2.3',
                    'name.short' => 'v1.2.3',
                    'type' => 'tags',
                ],
                'exitCode' => 0,
                'stdOutput' => "refs/tags/v1.2.3\n",
                'stdError' => '',
                'options' => [],
            ],
        ];
    }
}
