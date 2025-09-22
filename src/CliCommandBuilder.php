<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * @phpstan-import-type SweetchuckGitCommandProperties from \Sweetchuck\Git\Phpstan
 */
class CliCommandBuilder
{

    /**
     * @phpstan-param SweetchuckGitCommandProperties $properties
     *
     * @return array<string>
     */
    public function build(array $properties): array
    {
        $properties += [
            'workingDirectory' => null,
            'envVars' => [],
            'executable' => 'git',
            'globalOptions' => [],
            'command' => [],
            'commandOptions' => [],
            'commandArguments' => [],
            'extraArguments' => [],
        ];

        $command = [];
        $command[] = $properties['executable'];
        foreach ($properties['globalOptions'] as $optionName => $optionInfo) {
            $this->addOption($command, (string) $optionName, $optionInfo);
        }

        $command = array_merge($command, $properties['command']);

        foreach ($properties['commandOptions'] as $optionName => $optionInfo) {
            $this->addOption($command, (string) $optionName, $optionInfo);
        }

        $this->addArguments($command, $properties['commandArguments']);
        $this->addExtraArguments($command, $properties['extraArguments']);

        return $command;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOption(array &$command, string $optionName, array $optionInfo): static
    {
        switch ($optionInfo['type'] ?? null) {
            case 'state:true':
                // - null  omitted
                // - false invalid
                // - true  --foo
                $this->addOptionStateTrue($command, $optionName, $optionInfo);
                break;

            case 'state:false':
                // - null  omitted
                // - true  invalid
                // - false --no-foo
                $this->addOptionStateFalse($command, $optionName, $optionInfo);
                break;

            case 'state:bool':
                // - null  omitted
                // - false --no-foo
                // - true  --foo
                $this->addOptionStateTrueFalse($command, $optionName, $optionInfo);
                break;

            case 'state:bool:string-optional':
                // - null:null    omitted
                // - null:string  invalid
                // - false:null   --no-foo
                // - false:string --no-foo=value
                // - true:null    --foo
                // - true:string  --foo=value
                $this->addOptionStateTrueFalseValueOptional($command, $optionName, $optionInfo);
                break;

            case 'state:bool:string-required':
                // - null:null    omitted
                // - true:null    invalid
                // - true:string  --foo=value
                // - false:string --no-foo=value
                $this->addOptionStateTrueFalseValueRequired($command, $optionName, $optionInfo);
                break;

            case 'state:string-required:multi':
                // - null:                omitted
                // - array<string, bool>: --foo=value-1 --no-foo=value-2
                $this->addOptionStateStringRequiredMulti($command, $optionName, $optionInfo);
                break;

            case 'value:false:string-required':
                // - null   omitted
                // - false  --no-foo
                // - true   invalid
                // - string --foo=value
                $this->addOptionValueFalseStringRequired($command, $optionName, $optionInfo);
                break;

            case 'value:true-false:string':
                // - null             omitted
                // - false            --no-foo
                // - true             --foo
                // - non-empty-string --foo=value
                $this->addOptionValueTrueFalseString($command, $optionName, $optionInfo);
                break;

            case 'value:string-optional':
                // - null: omitted
                // - empty-string:     --foo
                // - non-empty-string: --foo=value
                $this->addOptionValueStringOptional($command, $optionName, $optionInfo);
                break;

            case 'value:string-required':
                // - null: omitted
                // - empty-string: omitted
                // - non-empty-string: --foo=value
                $this->addOptionValueStringRequired($command, $optionName, $optionInfo);
                break;

            case 'value:multi:false-string':
                // - null:    omitted
                // - [false]: --no-foo
                // - [true]:  --foo
                // - ["bar"]: --foo=bar
                $this->addOptionMultiFalseString($command, $optionName, $optionInfo);
                break;

            case 'value:string-multiple':
                // - null:          omitted
                // - array<string, bool>: --foo=true-key-1 --foo=true-key-2 --no-foo=false-key-1
                $this->addOptionValueMultiple($command, $optionName, $optionInfo);
                break;

            case 'value:map':
                // - null:          omitted
                // - array<string, string>: --foo='key1=value1' --foo='key2=value2'
                $this->addOptionValueMap($command, $optionName, $optionInfo);
                break;

            case 'state:name-suffix':
                // - null:        omitted
                // - string(bar): --foobar'
                $this->addOptionStateNameSuffix($command, $optionName, $optionInfo);
                break;

            case 'value:name-pattern':
                // In case of pattern = "--{{ value }}-foo"
                // - null:  omitted
                // - "bar": --bar-foo
                $this->addOptionValueNamePattern($command, $optionName, $optionInfo);
                break;

            case 'value:name-mapping':
                $this->addOptionValueNameMapping($command, $optionName, $optionInfo);
                break;

            case 'value:expressions':
                // - null:  omitted
                // - "bar": -e 'bar'
                $this->addOptionValueExpressions($command, $optionName, $optionInfo);
                break;

            case 'value:strategies':
                $this->addOptionValueStrategies($command, $optionName, $optionInfo);
                break;

            default:
                throw new \InvalidArgumentException("Unknown option type: {$optionInfo['type']}");
        }

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateStringRequiredMulti(array &$command, string $optionName, array $optionInfo): static
    {
        $names = $this->getOptionNames($optionName, $optionInfo);
        foreach ($optionInfo['value'] as $value => $state) {
            if ($state === null) {
                continue;
            }

            $name = $names[intval($state)];
            if (str_starts_with($name, '--')) {
                $command[] = "$name=$value";
            } else {
                $command[] = (string) $name;
                $command[] = (string) $value;
            }
        }

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateTrue(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['state'] === null) {
            return $this;
        }

        if ($optionInfo['state'] === false) {
            throw new \InvalidArgumentException("Option $optionName cannot be false");
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        $command[] = $names[1];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateFalse(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['state'] === null) {
            return $this;
        }

        if ($optionInfo['state'] === true) {
            throw new \InvalidArgumentException("Option $optionName cannot be true");
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        $command[] = $names[1];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateTrueFalse(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['state'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        $command[] = $optionInfo['state']
            ? $names[1]
            : $names[0];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateTrueFalseValueOptional(
        array &$command,
        string $optionName,
        array $optionInfo,
    ): static {
        if ($optionInfo['state'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        $name = $names[intval($optionInfo['state'])];
        if ($optionInfo['value'] === null) {
            $command[] = $name;

            return $this;
        }

        if (str_starts_with($name, '--')) {
            $command[] = "$name={$optionInfo['value']}";

            return $this;
        }

        $command[] = (string) $name;
        $command[] = (string) $optionInfo['value'];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateTrueFalseValueRequired(
        array &$command,
        string $optionName,
        array $optionInfo,
    ): static {
        if ($optionInfo['state'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        $name = $names[intval($optionInfo['state'])];
        if (str_starts_with($name, '--')) {
            $command[] = "$name={$optionInfo['value']}";

            return $this;
        }

        $command[] = (string) $name;
        $command[] = (string) $optionInfo['value'];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueFalseStringRequired(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === true) {
            $optionInfo['value'] = (string) $optionInfo[''];
        }

        return $this->addOptionValueTrueFalseString($command, $optionName, $optionInfo);
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueTrueFalseString(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        if (is_bool($optionInfo['value'])) {
            $command[] = $names[intval($optionInfo['value'])];

            return $this;
        }

        $name = $names[1];
        if (str_starts_with($name, '--')) {
            $command[] = "$name={$optionInfo['value']}";

            return $this;
        }

        $command[] = (string) $name;
        $command[] = (string) $optionInfo['value'];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueStringOptional(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === '') {
            $command[] = (string) ($optionInfo['name'] ?? "--$optionName");
        }

        return $this->addOptionValueStringRequired($command, $optionName, $optionInfo);
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueStringRequired(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === '' || $optionInfo['value'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        if (str_starts_with($names[1], '--')) {
            $command[] = "{$names[1]}={$optionInfo['value']}";

            return $this;
        }

        $command[] = (string) $names[1];
        $command[] = (string) $optionInfo['value'];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionMultiFalseString(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        foreach ($optionInfo['value'] as $value) {
            $command[] = is_bool($value)
                ? $names[intval($value)]
                : "{$names[1]}=$value";
        }

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueMultiple(array &$command, string $optionName, array $optionInfo): static
    {
        $values = array_keys($optionInfo['value'], true);
        $name = $optionInfo['name'] ?? "--$optionName";
        $isLong = str_starts_with($name, '--');
        foreach ($values as $value) {
            if ($isLong) {
                $command[] = "$name=$value";
            } else {
                $command[] = "$name";
                $command[] = "$value";
            }
        }

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueMap(array &$command, string $optionName, array $optionInfo): static
    {
        $name = $optionInfo['name'] ?? "--$optionName";
        $isLong = str_starts_with($name, '--');
        foreach ($optionInfo['value'] as $key => $value) {
            if ($isLong) {
                $command[] = "$name=$key=$value";
            } else {
                $command[] = "$name";
                $command[] = "$key=$value";
            }
        }

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionStateNameSuffix(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['state'] === null || $optionInfo['value'] === null) {
            return $this;
        }

        $names = $this->getOptionNames($optionName, $optionInfo);
        $command[] = $names[intval($optionInfo['state'])] . $optionInfo['value'];

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueNamePattern(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === null) {
            return $this;
        }

        $command[] = strtr(
            $optionInfo['pattern'],
            [
                '{{ value }}' => $optionInfo['value'],
            ]
        );

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueNameMapping(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === null) {
            return $this;
        }

        $name = $optionInfo['mapping'][$optionInfo['value']] ?? null;
        if (is_string($name)) {
            $command[] = $name;
        }

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueExpressions(array &$command, string $optionName, array $optionInfo): static
    {
        if ($optionInfo['value'] === null) {
            return $this;
        }

        $command = array_merge(
            $command,
            $this->buildExpressionsArgs($optionInfo['value']),
        );

        return $this;
    }

    /**
     * @param array<string> $command
     * @param array<string, mixed> $optionInfo
     */
    protected function addOptionValueStrategies(array &$command, string $optionName, array $optionInfo): static
    {
        if (!$optionInfo['value']) {
            return $this;
        }

        $command = array_merge(
            $command,
            $this->buildStrategiesArgs($optionInfo['value']),
        );

        return $this;
    }

    /**
     * @param array<mixed> $values
     *
     * @return array<string>
     */
    protected function buildExpressionsArgs(array $values): array
    {
        $args = [];

        if (array_key_exists('patterns', $values)) {
            if (empty($values['patterns'])) {
                return $args;
            }

            if (array_key_exists('operator', $values)) {
                $args[] = "--{$values['operator']}";
            }

            $args[] = '(';
            $args = array_merge($args, $this->buildExpressionsArgs($values['patterns']));
            $args[] = ')';

            return $args;
        }

        foreach ($values as $value) {
            if (is_string($value)) {
                $args[] = '-e';
                $args[] = $value;

                continue;
            }

            $args = array_merge($args, $this->buildExpressionsArgs($value));
        }

        return $args;
    }

    /**
     * @param array<array-key, mixed> $strategies
     *
     * @return array<string>
     */
    public function buildStrategiesArgs(array $strategies): array
    {
        $args = [];

        $strategies = array_filter(
            $strategies,
            static function (array $strategy): bool {
                return !array_key_exists('enabled', $strategy) || $strategy['enabled'];
            },
        );
        $defaultWeights = array_keys($strategies);
        foreach ($defaultWeights as $weight => $name) {
            $strategies[$name]['name'] = $name;
            $strategies[$name] += [
                'weight' => $weight,
                'options' => [],
            ];
        }

        uasort(
            $strategies,
            static function (array $a, array $b): int {
                return $a['weight'] <=> $b['weight'];
            },
        );

        foreach ($strategies as $strategy) {
            $args[] = sprintf('--strategy=%s', $strategy['name']);
            foreach ($strategy['options'] as $optName => $optValue) {
                if ($optValue === null) {
                    continue;
                }

                $args[] = $optValue === true
                    ? sprintf('--strategy-option=%s', $optName)
                    : sprintf('--strategy-option=%s=%s', $optName, $optValue);
            }
        }

        return $args;
    }

    /**
     * @param array<?string>|array<string, bool> $values
     * @param-out array<string> $command
     * @param array<string> $command
     */
    protected function addArguments(array &$command, array $values): static
    {
        $first = reset($values);
        if (gettype($first) === 'boolean') {
            /** @var array<string> $values */
            $values = array_keys($values, true);
        }

        /** @var array<string> $values */
        $values = array_filter(
            array_values($values),
            static function ($value): bool {
                return $value !== null;
            },
        );

        if (!$values) {
            return $this;
        }

        $command = array_merge($command, $values);

        return $this;
    }

    /**
     * @param array<string>|array<string, bool> $values
     * @param array<string> $command
     */
    protected function addExtraArguments(array &$command, array $values): static
    {
        $first = reset($values);
        if (gettype($first) !== 'boolean') {
            $values = array_keys($values, true);
        }

        /** @var array<string> $values */
        $values = array_keys($values, true);
        if (!$values) {
            return $this;
        }

        if (!in_array('--', $command)) {
            $command[] = '--';
        }

        $command = array_merge($command, $values);

        return $this;
    }

    /**
     * @param array<string, mixed> $optionInfo
     *
     * @return array{0: string, 1: string}
     */
    public function getOptionNames(string $optionName, array $optionInfo): array
    {
        $optionName = strtolower(preg_replace('/[A-Z]/', '-$0', $optionName));
        $true = $optionInfo['name'] ?? "--$optionName";
        $false = $optionInfo['name-no'] ?? preg_replace('/^--/', '--no-', $true);

        return [
            0 => $false,
            1 => $true,
        ];
    }
}
