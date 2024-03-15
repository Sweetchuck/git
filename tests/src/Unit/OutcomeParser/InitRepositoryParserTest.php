<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\OutcomeParser\InitRepositoryParser;

class InitRepositoryParserTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParseSuccessful(): array
    {
        return [
            'basic - linux - working copy' => [
                'expected' => [
                    'gitDir' => '/path/to/project-01/.git/',
                ],
                'args' => [
                    0,
                    "Initialized empty Git repository in /path/to/project-01/.git/\n",
                    '',
                ],
            ],
            'basic - linux - bare' => [
                'expected' => [
                    'gitDir' => '/path/to/project-01/',
                ],
                'args' => [
                    0,
                    "Initialized empty Git repository in /path/to/project-01/\n",
                    '',
                ],
            ],
            // WARNING: Not tested.
            'basic - windows native' => [
                'expected' => [
                    'gitDir' => 'C:\\path\\to\\project-01\\.git\\',
                ],
                'args' => [
                    0,
                    "Initialized empty Git repository in C:\\path\\to\\project-01\\.git\\\n",
                    '',
                ],
            ],
            // WARNING: Not tested.
            'basic - windows WSL' => [
                'expected' => [
                    'gitDir' => '\\\\wsl$\\path\\to\\project-01\\.git\\',
                ],
                'args' => [
                    0,
                    "Initialized empty Git repository in \\\\wsl$\\path\\to\\project-01\\.git\\\n",
                    '',
                ],
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
        $parser = new InitRepositoryParser();
        static::assertSame($expected, $parser->parse(...$args));
    }
}
