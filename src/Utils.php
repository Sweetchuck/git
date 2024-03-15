<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

class Utils
{

    /**
     * @var array<string, array<string, string>>
     */
    public array $predefinedRefPropertyMappings = [
        'branch-list.default' => [
            'refName' => 'refname:strip=0',
            'upstream' => 'upstream:strip=0',
            'track' => 'upstream:track',
            'push' => 'push:strip=0',
            'isCurrentBranch' => 'HEAD',
        ],
        'tag-list.default' => [
            'refName' => 'refname:strip=0',
            'objectType' => 'objecttype',
            'objectName' => 'objectname',
            'taggerDate' => 'taggerdate:iso',
            'creatorDate' => 'creatordate:iso',
        ],
    ];

    /**
     * @param array<string, ?bool> $diffFilter
     */
    public function implodeDiffFilter(array $diffFilter): string
    {
        $statuses = [];
        foreach ($diffFilter as $statusName => $status) {
            if ($status === null) {
                continue;
            }

            $statusName = mb_strtoupper($statusName);
            $statuses[$statusName] = $status ? $statusName : mb_strtolower($statusName);
        }

        return implode('', $statuses);
    }

    /**
     * @return array<string, bool>
     */
    public function explodeDiffFilter(string $diffFilter): array
    {
        $parts = [];
        for ($position = 0; $position < mb_strlen($diffFilter); $position++) {
            $char = mb_substr($diffFilter, $position, 1);
            $key = mb_strtoupper($char);
            $parts[$key] = $key === $char;
        }

        return $parts;
    }
}
