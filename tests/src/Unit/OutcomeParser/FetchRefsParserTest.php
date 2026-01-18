<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\FetchResult;
use Sweetchuck\Git\OutcomeParser\FetchRefsParser;

#[CoversClass(FetchRefsParser::class)]
class FetchRefsParserTest extends TestCase
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function casesParse(): array
    {
        return [
            // Exit code 1 still a valid result.
            'error' => [
                'expected' => null,
                'exitCode' => 2,
                'stdOutput' => '',
                'stdError' => 'error',
            ],
            'empty' => [
                'expected' => [],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
            ],
            'basic' => [
                'expected' => [
                    'refs/heads/1.x' => [
                        'result' => FetchResult::FastForward,
                        'local' => 'sha_01',
                        'remote' => 'sha_02',
                        'ref' => 'refs/heads/1.x',
                    ],
                    'refs/heads/2.x' => [
                        'result' => FetchResult::ForcedUpdate,
                        'local' => 'sha_03',
                        'remote' => 'sha_04',
                        'ref' => 'refs/heads/2.x',
                    ],
                    'refs/heads/3.x' => [
                        'result' => FetchResult::RemoteDeleted,
                        'local' => 'sha_05',
                        'remote' => 'sha_06',
                        'ref' => 'refs/heads/3.x',
                    ],
                    'refs/heads/4.x' => [
                        'result' => FetchResult::NewRef,
                        'local' => 'sha_07',
                        'remote' => 'sha_08',
                        'ref' => 'refs/heads/4.x',
                    ],
                    'refs/heads/5.x' => [
                        'result' => FetchResult::UpdatedTag,
                        'local' => 'sha_09',
                        'remote' => 'sha_10',
                        'ref' => 'refs/heads/5.x',
                    ],
                    'refs/heads/6.x' => [
                        'result' => FetchResult::UpToDate,
                        'local' => 'sha_11',
                        'remote' => 'sha_12',
                        'ref' => 'refs/heads/6.x',
                    ],
                    'refs/heads/7.x' => [
                        'result' => FetchResult::Rejected,
                        'local' => 'sha_13',
                        'remote' => 'sha_14',
                        'ref' => 'refs/heads/7.x',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    "\n",
                    [
                        '  sha_01 sha_02 refs/heads/1.x',
                        '+ sha_03 sha_04 refs/heads/2.x',
                        '- sha_05 sha_06 refs/heads/3.x',
                        '* sha_07 sha_08 refs/heads/4.x',
                        't sha_09 sha_10 refs/heads/5.x',
                        '= sha_11 sha_12 refs/heads/6.x',
                        '! sha_13 sha_14 refs/heads/7.x',
                    ],
                ),
                'stdError' => '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     */
    #[DataProvider('casesParse')]
    public function testParse(?array $expected, int $exitCode, string $stdOutput, string $stdError): void
    {
        $parser = new FetchRefsParser();
        $this->assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError));
    }
}
