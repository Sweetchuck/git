<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\CliCommandBuilder;
use PHPUnit\Framework\TestCase;

#[CoversClass(CliCommandBuilder::class)]
class CliCommandBuilderTest extends TestCase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesBuildSuccess(): array
    {
        return [
            'state:true null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:true',
                            'state' => null
                        ],
                    ],
                ],
            ],
            'state:true true' => [
                'expected' => ['my-exe', '--foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:true',
                            'state' => true
                        ],
                    ],
                ],
            ],
            'state:bool null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool',
                            'state' => null
                        ],
                    ],
                ],
            ],
            'state:bool true' => [
                'expected' => ['my-exe', '--foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool',
                            'state' => true
                        ],
                    ],
                ],
            ],
            'state:bool false' => [
                'expected' => ['my-exe', '--no-foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool',
                            'state' => false
                        ],
                    ],
                ],
            ],
            'state:bool custom name' => [
                'expected' => ['my-exe', '--custom-foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool',
                            'state' => true,
                            'name' => '--custom-foo'
                        ],
                    ],
                ],
            ],
            'state:bool custom name-no' => [
                'expected' => ['my-exe', '--custom-no-foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool',
                            'state' => false,
                            'name-no' => '--custom-no-foo'
                        ],
                    ],
                ],
            ],
            'state:bool multiple options' => [
                'expected' => ['my-exe', '--foo', '--no-bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool',
                            'state' => true
                        ],
                        'bar' => [
                            'type' => 'state:bool',
                            'state' => false
                        ],
                    ],
                ],
            ],
            'state:false null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:false',
                            'state' => null
                        ],
                    ],
                ],
            ],
            'state:false false' => [
                'expected' => ['my-exe', '--foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:false',
                            'state' => false
                        ],
                    ],
                ],
            ],
            'state:bool:string-optional null:null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-optional',
                            'state' => null,
                            'value' => null
                        ],
                    ],
                ],
            ],
            'state:bool:string-optional true:null' => [
                'expected' => ['my-exe', '--foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-optional',
                            'state' => true,
                            'value' => null
                        ],
                    ],
                ],
            ],
            'state:bool:string-optional false:null' => [
                'expected' => ['my-exe', '--no-foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-optional',
                            'state' => false,
                            'value' => null
                        ],
                    ],
                ],
            ],
            'state:bool:string-optional true:value' => [
                'expected' => ['my-exe', '--foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-optional',
                            'state' => true,
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'state:bool:string-optional false:value' => [
                'expected' => ['my-exe', '--no-foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-optional',
                            'state' => false,
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'state:bool:string-required true:value' => [
                'expected' => ['my-exe', '--foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-required',
                            'state' => true,
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'state:bool:string-required false:value' => [
                'expected' => ['my-exe', '--no-foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:bool:string-required',
                            'state' => false,
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'state:string-required:multi' => [
                'expected' => ['my-exe', '--foo=value1', '--no-foo=value2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:string-required:multi',
                            'value' => [
                                'value1' => true,
                                'value2' => false,
                                'value3' => null,
                            ],
                        ],
                    ],
                ],
            ],
            'state:string-required:multi whit short name' => [
                'expected' => ['my-exe', '-n', 'value1', '-N', 'value2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'state:string-required:multi',
                            'name' => '-n',
                            'name-no' => '-N',
                            'value' => [
                                'value1' => true,
                                'value2' => false,
                                'value3' => null,
                            ],
                        ],
                    ],
                ],
            ],
            'value:true-false:string null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:true-false:string',
                            'value' => null
                        ],
                    ],
                ],
            ],
            'value:true-false:string true' => [
                'expected' => ['my-exe', '--foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:true-false:string',
                            'value' => true
                        ],
                    ],
                ],
            ],
            'value:true-false:string false' => [
                'expected' => ['my-exe', '--no-foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:true-false:string',
                            'value' => false
                        ],
                    ],
                ],
            ],
            'value:true-false:string string' => [
                'expected' => ['my-exe', '--foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:true-false:string',
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'value:string-optional null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-optional',
                            'value' => null
                        ],
                    ],
                ],
            ],
            'value:string-optional empty' => [
                'expected' => ['my-exe', '--foo'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-optional',
                            'value' => ''
                        ],
                    ],
                ],
            ],
            'value:string-optional value' => [
                'expected' => ['my-exe', '--foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-optional',
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'value:string-required null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-required',
                            'value' => null
                        ],
                    ],
                ],
            ],
            'value:string-required empty' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-required',
                            'value' => ''
                        ],
                    ],
                ],
            ],
            'value:string-required value' => [
                'expected' => ['my-exe', '--foo=bar'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-required',
                            'value' => 'bar'
                        ],
                    ],
                ],
            ],
            'value:multi:false-string null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'serverOption' => [
                            'type' => 'value:multi:false-string',
                            'name' => '--server-option',
                            'value' => null,
                        ],
                    ],
                ],
            ],
            'value:multi:false-string array<false|string>' => [
                'expected' => ['my-exe', '--no-server-option', '--server-option=value1', '--server-option=value2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'serverOption' => [
                            'type' => 'value:multi:false-string',
                            'value' => [false, 'value1', 'value2'],
                        ],
                    ],
                ],
            ],
            'value:string-multiple' => [
                'expected' => ['my-exe', '--foo=value1', '--foo=value2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:string-multiple',
                            'value' => [
                                'value1' => true,
                                'value2' => true,
                                'value3' => false,
                            ],
                        ],
                    ],
                ],
            ],
            'value:map' => [
                'expected' => ['my-exe', '--foo=key1=value1', '--foo=key2=value2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'foo' => [
                            'type' => 'value:map',
                            'value' => [
                                'key1' => 'value1',
                                'key2' => 'value2',
                            ],
                        ],
                    ],
                ],
            ],
            'state:name-suffix null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'ipv' => [
                            'type' => 'state:name-suffix',
                            'state' => true,
                            'value' => null,
                        ],
                    ],
                ],
            ],
            'state:name-suffix string' => [
                'expected' => ['my-exe', '--ipv4'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'ipv' => [
                            'type' => 'state:name-suffix',
                            'state' => true,
                            'value' => '4',
                        ],
                    ],
                ],
            ],
            'value:name-pattern null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:name-pattern',
                            'pattern' => '--prefix-{{ value }}-suffix',
                            'value' => null,
                        ],
                    ],
                ],
            ],
            'value:name-pattern string' => [
                'expected' => ['my-exe', '--prefix-middle-suffix'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:name-pattern',
                            'pattern' => '--prefix-{{ value }}-suffix',
                            'value' => 'middle',
                        ],
                    ],
                ],
            ],
            'value:expressions null' => [
                'expected' => ['my-exe'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => null,
                        ],
                    ],
                ],
            ],
            'value:expressions string single' => [
                'expected' => ['my-exe', '-e', 'a'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => ['a'],
                        ],
                    ],
                ],
            ],
            'value:expressions string multiple' => [
                'expected' => ['my-exe', '-e', 'a', '-e', 'b'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => ['a', 'b'],
                        ],
                    ],
                ],
            ],
            'value:expressions top-level patterns without operator' => [
                'expected' => ['my-exe', '(', '-e', 'a', '-e', 'b', ')'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => [
                                'patterns' => ['a', 'b'],
                            ],
                        ],
                    ],
                ],
            ],
            'value:expressions top-level patterns with operator' => [
                'expected' => ['my-exe', '--not', '(', '-e', 'a', '-e', 'b', ')'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => [
                                'operator' => 'not',
                                'patterns' => ['a', 'b'],
                            ],
                        ],
                    ],
                ],
            ],
            'value:expressions top-level simple, nested with operator' => [
                'expected' => ['my-exe', '-e', 'a', '-e', 'b', '--not', '(', '-e', 'c', '-e', 'd', ')'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => [
                                'a',
                                'b',
                                [
                                    'operator' => 'not',
                                    'patterns' => ['c', 'd'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'value:expressions top-level with operator, nested simple - not real life' => [
                'expected' => ['my-exe', '--not', '(', '-e', 'a', '-e', 'b', '-e', 'c', '-e', 'd', ')'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => [
                                'operator' => 'not',
                                'patterns' => [
                                    'a',
                                    'b',
                                    [
                                        'c',
                                        'd',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'value:expressions patterns->patterns' => [
                'expected' => ['e', '--not', '(', '-e', 'a', '-e', 'b', '--and', '(', '-e', 'c', '-e', 'd', ')', ')'],
                'properties' => [
                    'executable' => 'e',
                    'commandOptions' => [
                        'doesNotMatter' => [
                            'type' => 'value:expressions',
                            'value' => [
                                'operator' => 'not',
                                'patterns' => [
                                    'a',
                                    'b',
                                    [
                                        'operator' => 'and',
                                        'patterns' => [
                                            'c',
                                            'd',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'command arguments' => [
                'expected' => ['my-exe', 'arg1', 'arg2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandArguments' => ['arg1', 'arg2'],
                ],
            ],
            'command arguments with boolean values' => [
                'expected' => ['my-exe', 'arg1', 'arg3'],
                'properties' => [
                    'executable' => 'my-exe',
                    'commandArguments' => [
                        'arg1' => true,
                        'arg2' => false,
                        'arg3' => true,
                    ],
                ],
            ],
            'extra arguments' => [
                'expected' => ['my-exe', '--', 'extra1', 'extra2'],
                'properties' => [
                    'executable' => 'my-exe',
                    'extraArguments' => [
                        'extra1' => true,
                        'extra2' => true,
                    ],
                ],
            ],
            'global options' => [
                'expected' => ['my-exe', '--git-dir=/path/to/repo', '-C', '/path/to/dir'],
                'properties' => [
                    'executable' => 'my-exe',
                    'globalOptions' => [
                        'gitDir' => [
                            'type' => 'value:string-required',
                            'name' => '--git-dir',
                            'value' => '/path/to/repo',
                        ],
                        'cwd' => [
                            'type' => 'value:string-required',
                            'name' => '-C',
                            'value' => '/path/to/dir',
                        ],
                    ],
                ],
            ],
            'command with subcommands' => [
                'expected' => ['my-exe', 'config', '--local'],
                'properties' => [
                    'executable' => 'my-exe',
                    'command' => ['config'],
                    'commandOptions' => [
                        'local' => [
                            'type' => 'state:true',
                            'state' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string> $expected
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesBuildSuccess')]
    public function testBuildSuccess(array $expected, array $properties): void
    {
        $builder = new CliCommandBuilder();
        static::assertSame(
            $expected,
            $builder->build($properties),
        );
    }
}
