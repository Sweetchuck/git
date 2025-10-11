<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Sweetchuck\Git\CliCommandBuilder;
use Sweetchuck\Git\CommandOptionType;

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
                            'type' => CommandOptionType::StateTrue,
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
                            'type' => CommandOptionType::StateTrue,
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
                            'type' => CommandOptionType::StateBool,
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
                            'type' => CommandOptionType::StateBool,
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
                            'type' => CommandOptionType::StateBool,
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
                            'type' => CommandOptionType::StateBool,
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
                            'type' => CommandOptionType::StateBool,
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
                            'type' => CommandOptionType::StateBool,
                            'state' => true
                        ],
                        'bar' => [
                            'type' => CommandOptionType::StateBool,
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
                            'type' => CommandOptionType::StateFalse,
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
                            'type' => CommandOptionType::StateFalse,
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
                            'type' => CommandOptionType::StateBoolStringOptional,
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
                            'type' => CommandOptionType::StateBoolStringOptional,
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
                            'type' => CommandOptionType::StateBoolStringOptional,
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
                            'type' => CommandOptionType::StateBoolStringOptional,
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
                            'type' => CommandOptionType::StateBoolStringOptional,
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
                            'type' => CommandOptionType::StateBoolStringRequired,
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
                            'type' => CommandOptionType::StateBoolStringRequired,
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
                            'type' => CommandOptionType::StateStringRequiredMulti,
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
                            'type' => CommandOptionType::StateStringRequiredMulti,
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
                            'type' => CommandOptionType::ValueTrueFalseString,
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
                            'type' => CommandOptionType::ValueTrueFalseString,
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
                            'type' => CommandOptionType::ValueTrueFalseString,
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
                            'type' => CommandOptionType::ValueTrueFalseString,
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
                            'type' => CommandOptionType::ValueStringOptional,
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
                            'type' => CommandOptionType::ValueStringOptional,
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
                            'type' => CommandOptionType::ValueStringOptional,
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
                            'type' => CommandOptionType::ValueStringRequired,
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
                            'type' => CommandOptionType::ValueStringRequired,
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
                            'type' => CommandOptionType::ValueStringRequired,
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
                            'type' => CommandOptionType::ValueMultiFalseString,
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
                            'type' => CommandOptionType::ValueMultiFalseString,
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
                            'type' => CommandOptionType::ValueStringMultiple,
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
                            'type' => CommandOptionType::ValueMap,
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
                            'type' => CommandOptionType::StateNameSuffix,
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
                            'type' => CommandOptionType::StateNameSuffix,
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
                            'type' => CommandOptionType::ValueNamePattern,
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
                            'type' => CommandOptionType::ValueNamePattern,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueExpressions,
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
                            'type' => CommandOptionType::ValueStringRequired,
                            'name' => '--git-dir',
                            'value' => '/path/to/repo',
                        ],
                        'cwd' => [
                            'type' => CommandOptionType::ValueStringRequired,
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
                            'type' => CommandOptionType::StateTrue,
                            'state' => true,
                        ],
                    ],
                ],
            ],
            'merge strategies' => [
                'expected' => [
                    'git',
                    'merge',
                    '--strategy=octopus',
                    '--strategy=ort',
                    '--strategy-option=my-true',
                    '--strategy-option=my-int=42',
                    '--strategy-option=my-float=42.56',
                    '--strategy-option=my-string=okay',
                    '--strategy=resolve',
                ],
                'properties' => [
                    'command' => ['merge'],
                    'commandOptions' => [
                        'strategies' => [
                            'type' => CommandOptionType::ValueStrategies,
                            'value' => [
                                'ort' => [
                                    'weight' => 2,
                                    'options' => [
                                        'ignore-me' => null,
                                        'my-true' => true,
                                        'my-int' => 42,
                                        'my-float' => 42.56,
                                        'my-string' => 'okay',
                                    ],
                                ],
                                'octopus' => [
                                    'weight' => 1,
                                ],
                                'subtree' => [
                                    'enabled' => false,
                                ],
                                'resolve' => [],
                            ],
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
