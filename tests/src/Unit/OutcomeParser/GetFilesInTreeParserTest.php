<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\OutcomeParser;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\OutcomeParser\GetFilesInTreeParser;

#[CoversClass(GetFilesInTreeParser::class)]
class GetFilesInTreeParserTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesParse(): array
    {
        return [
            'non-zero exit code' => [
                'expected' => null,
                'exitCode' => 1,
                'stdOutput' => "100644 blob abc123def456\tREADME.md\0",
                'stdError' => 'fatal: not a git repository',
                'options' => [],
            ],
            'empty stdOutput' => [
                'expected' => [],
                'exitCode' => 0,
                'stdOutput' => '',
                'stdError' => '',
                'options' => [],
            ],
            'whitespace only stdOutput' => [
                'expected' => [],
                'exitCode' => 0,
                'stdOutput' => "   \n\t  ",
                'stdError' => '',
                'options' => [],
            ],
            'single file' => [
                'expected' => [
                    'README.md' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'abc123def456789abcdef123456789abcdef123',
                        'filePath' => 'README.md',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => "100644 blob abc123def456789abcdef123456789abcdef123\tREADME.md\0",
                'stdError' => '',
                'options' => [],
            ],
            'multiple files with different types' => [
                'expected' => [
                    'README.md' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'abc123def456789abcdef123456789abcdef123',
                        'filePath' => 'README.md',
                    ],
                    'src' => [
                        'chmod' => '040000',
                        'objectType' => 'tree',
                        'objectId' => 'def456abc789def456abc789def456abc789def4',
                        'filePath' => 'src',
                    ],
                    'script.sh' => [
                        'chmod' => '100755',
                        'objectType' => 'blob',
                        'objectId' => '123456789abcdef123456789abcdef123456789a',
                        'filePath' => 'script.sh',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    '',
                    [
                        "100644 blob abc123def456789abcdef123456789abcdef123\tREADME.md\0",
                        "040000 tree def456abc789def456abc789def456abc789def4\tsrc\0",
                        "100755 blob 123456789abcdef123456789abcdef123456789a\tscript.sh\0",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
            'files with special characters in paths' => [
                'expected' => [
                    'file with spaces.txt' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'special123456789abcdef123456789abcdef123',
                        'filePath' => 'file with spaces.txt',
                    ],
                    'file-with-dashes.txt' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'dashed456789abcdef123456789abcdef1234567',
                        'filePath' => 'file-with-dashes.txt',
                    ],
                    'path/to/nested/file.txt' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'nested789abcdef123456789abcdef123456789a',
                        'filePath' => 'path/to/nested/file.txt',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    '',
                    [
                        "100644 blob special123456789abcdef123456789abcdef123\tfile with spaces.txt\0",
                        "100644 blob dashed456789abcdef123456789abcdef1234567\tfile-with-dashes.txt\0",
                        "100644 blob nested789abcdef123456789abcdef123456789a\tpath/to/nested/file.txt\0",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
            'empty lines are skipped' => [
                'expected' => [
                    'README.md' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'abc123def456789abcdef123456789abcdef123',
                        'filePath' => 'README.md',
                    ],
                    'LICENSE' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'license456789abcdef123456789abcdef12345',
                        'filePath' => 'LICENSE',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    '',
                    [
                        "100644 blob abc123def456789abcdef123456789abcdef123\tREADME.md\0",
                        "\0",
                        "100644 blob license456789abcdef123456789abcdef12345\tLICENSE\0",
                        "\0",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
            'malformed lines are skipped' => [
                'expected' => [
                    'valid-file.txt' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'valid123456789abcdef123456789abcdef1234',
                        'filePath' => 'valid-file.txt',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    '',
                    [
                        "100644 blob valid123456789abcdef123456789abcdef1234\tvalid-file.txt\0",
                        "invalid line without proper format\0",
                        "100644 missing-hash-and-path\0",
                        "only-one-part\0",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
            'trailing null character is removed' => [
                'expected' => [
                    'README.md' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'abc123def456789abcdef123456789abcdef123',
                        'filePath' => 'README.md',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => "100644 blob abc123def456789abcdef123456789abcdef123\tREADME.md\0\0\0",
                'stdError' => '',
                'options' => [],
            ],
            'mixed valid and invalid lines' => [
                'expected' => [
                    'good-file1.txt' => [
                        'chmod' => '100644',
                        'objectType' => 'blob',
                        'objectId' => 'good1123456789abcdef123456789abcdef123',
                        'filePath' => 'good-file1.txt',
                    ],
                    'good-file2.txt' => [
                        'chmod' => '100755',
                        'objectType' => 'blob',
                        'objectId' => 'good2456789abcdef123456789abcdef1234567',
                        'filePath' => 'good-file2.txt',
                    ],
                ],
                'exitCode' => 0,
                'stdOutput' => implode(
                    '',
                    [
                        "100644 blob good1123456789abcdef123456789abcdef123\tgood-file1.txt\0",
                        "bad-line-format\0",
                        "\0",
                        "100755 blob good2456789abcdef123456789abcdef1234567\tgood-file2.txt\0",
                        "another bad line\0",
                    ],
                ),
                'stdError' => '',
                'options' => [],
            ],
        ];
    }

    /**
     * @param null|array<string, array<string, string>> $expected
     * @param array<string, mixed> $options
     */
    #[Test]
    #[DataProvider('casesParse')]
    public function testParse(
        ?array $expected,
        int $exitCode,
        string $stdOutput,
        string $stdError = '',
        array $options = [],
    ): void {
        $parser = new GetFilesInTreeParser();
        $actual = $parser->parse($exitCode, $stdOutput, $stdError, $options);

        static::assertSame($expected, $actual);
    }
}
