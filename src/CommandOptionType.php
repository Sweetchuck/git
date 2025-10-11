<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

enum CommandOptionType: string
{
    /**
     * - null  omitted
     * - false invalid
     * - true  --foo
     */
    case StateTrue = 'state:true';

    /**
     * - null  omitted
     * - true  invalid
     * - false --no-foo
     */
    case StateFalse = 'state:false';

    /**
     * - null  omitted
     * - false --no-foo
     * - true  --foo
     */
    case StateBool = 'state:bool';

    /**
     * - null:null    omitted
     * - null:string  invalid
     * - false:null   --no-foo
     * - false:string --no-foo=value
     * - true:null    --foo
     * - true:string  --foo=value
     */
    case StateBoolStringOptional = 'state:bool:string-optional';

    /**
     * - null:null    omitted
     * - true:null    invalid
     * - true:string  --foo=value
     * - false:string --no-foo=value
     */
    case StateBoolStringRequired = 'state:bool:string-required';

    /**
     * - null:                omitted
     * - array<string, bool>: --foo=key-1 --no-foo=key-2
     */
    case StateStringRequiredMulti = 'state:string-required:multi';

    /**
     * - null   omitted
     * - false  --no-foo
     * - true   invalid
     * - string --foo=value
     */
    case ValueFalseStringRequired = 'value:false:string-required';

    /**
     * - null             omitted
     * - false            --no-foo
     * - true             --foo
     * - non-empty-string --foo=value
     */
    case ValueTrueFalseString = 'value:true-false:string';

    /**
     * - null: omitted
     * - empty-string:     --foo
     * - non-empty-string: --foo=value
     */
    case ValueStringOptional = 'value:string-optional';

    /**
     * - null: omitted
     * - empty-string: omitted
     * - non-empty-string: --foo=value
     */
    case ValueStringRequired = 'value:string-required';

    /**
     * - null:    omitted
     * - [false]: --no-foo
     * - [true]:  --foo
     * - ["bar"]: --foo=bar
     */
    case ValueMultiFalseString = 'value:multi:false-string';

    /**
     * - null:          omitted
     * - array<string, bool>: --foo=true-key-1 --foo=true-key-2 --no-foo=false-key-1
     */
    case ValueStringMultiple = 'value:string-multiple';

    /**
     * - null: omitted
     * - array<TId, TValue>: --foo=value-1 --foo=value-2
     */
    case ArrayString = 'array<TId, TValue>';

    /**
     * - null:          omitted
     * - array<string, string>: --foo='key1=value1' --foo='key2=value2'
     */
    case ValueMap = 'value:map';

    /**
     * - null:        omitted
     * - string(bar): --foobar'
     */
    case StateNameSuffix = 'state:name-suffix';

    /**
     * In case of a pattern = "--{{ value }}-foo"
     * - null:  omitted
     * - "bar": --bar-foo
     */
    case ValueNamePattern = 'value:name-pattern';

    case ValueNameMapping = 'value:name-mapping';

    /**
     * - null:  omitted
     * - "bar": -e 'bar'
     *
     * @see \Sweetchuck\Git\Command\GrepFiles
     * @see https://git-scm.com/docs/git-grep#Documentation/git-grep.txt--e
     */
    case ValueExpressions = 'value:expressions';

    /**
     * @see \Sweetchuck\Git\Command\ExecuteMerge
     * @see https://git-scm.com/docs/git-merge#Documentation/git-merge.txt---strategystrategy
     * @see \Sweetchuck\Git\Command\ExecuteRebase
     * @see https://git-scm.com/docs/git-rebase#Documentation/git-rebase.txt---strategystrategy
     */
    case ValueStrategies = 'value:strategies';
}
