<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\CommitMsg;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Sweetchuck\Git\CommitMsg\CommitMsgPartRecognizer;
use Sweetchuck\Git\Tests\Unit\TestBase;

/**
 * @phpstan-import-type SweetchuckGitCommitMsgPartRecognizerOptions from \Sweetchuck\Git\Phpstan
 * @phpstan-import-type SweetchuckGitCommitMsgPart from \Sweetchuck\Git\Phpstan
 */
#[CoversClass(CommitMsgPartRecognizer::class)]
class CommitMsgPartRecognizerTest extends TestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesInvokeSuccess(): array
    {
        $optionsCommon = [
            'checkerPattern' => '/^Changes in (?P<lockFilePath>.+?):\n/',
            'keyTemplate' => 'pamald:report:{{ lockFilePath }}',
        ];

        return [
            'empty' => [
                'expected' => [],
                'options' => $optionsCommon,
                'parts' => [],
            ],
            'basic' => [
                'expected' => [
                    'subject' => [
                        'enabled' => true,
                        'weight' => -999,
                        'marginTop' => 0,
                        'content' => "My subject line\n",
                    ],
                    'pamald:report:composer.lock' => [
                        'enabled' => true,
                        'weight' => 10,
                        'marginTop' => 1,
                        'content' => <<< 'TEXT'
                            Changes in composer.lock:
                            | old | new |
                            | --- | --- |
                            | 1.2 | 2.3 |

                            TEXT,
                    ],
                    'unknown.2' => [
                        'enabled' => true,
                        'weight' => 20,
                        'marginTop' => 1,
                        'content' => 'Foo bar.',
                    ],
                    'footer_comment' => [
                        'enabled' => true,
                        'weight' => 999,
                        'marginTop' => 1,
                        'content' => "# Comment 1\nComment2\n",
                    ],
                ],
                'options' => $optionsCommon,
                'parts' => [
                    'subject' => [
                        'enabled' => true,
                        'weight' => -999,
                        'marginTop' => 0,
                        'content' => "My subject line\n",
                    ],
                    'unknown.1' => [
                        'enabled' => true,
                        'weight' => 10,
                        'marginTop' => 1,
                        'content' => <<< 'TEXT'
                            Changes in composer.lock:
                            | old | new |
                            | --- | --- |
                            | 1.2 | 2.3 |

                            TEXT,
                    ],
                    'unknown.2' => [
                        'enabled' => true,
                        'weight' => 20,
                        'marginTop' => 1,
                        'content' => 'Foo bar.',
                    ],
                    'footer_comment' => [
                        'enabled' => true,
                        'weight' => 999,
                        'marginTop' => 1,
                        'content' => "# Comment 1\nComment2\n",
                    ],
                ],
            ],
        ];
    }

    /**
     * @phpstan-param array<string, mixed> $expected
     * @phpstan-param SweetchuckGitCommitMsgPartRecognizerOptions $options
     * @phpstan-param array<string, SweetchuckGitCommitMsgPart> $parts
     */
    #[DataProvider('casesInvokeSuccess')]
    public function testInvokeSuccess(array $expected, array $options, array $parts): void
    {
        $modifier = new CommitMsgPartRecognizer();
        $modifier->setOptions($options);
        $modifier($parts);
        static::assertSame($expected, $parts);
    }

    public function testInvokeFailMissingCheckerPattern(): void
    {
        $modifier = new CommitMsgPartRecognizer();
        $modifier->setKeyTemplate('pamald:report:{{ lockFilePath }}');
        $parts = [];
        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(1);
        $modifier($parts);
    }

    public function testInvokeFailMissingKeyTemplate(): void
    {
        $modifier = new CommitMsgPartRecognizer();
        $modifier->setCheckerPattern('/^Changes in (?P<lockFilePath>.+?):\n/');
        $parts = [];
        $this->expectException(\LogicException::class);
        $this->expectExceptionCode(2);
        $modifier($parts);
    }
}
