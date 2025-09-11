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
            'refName' => '%(refname:strip=0)',
            'upstream' => '%(upstream:strip=0)',
            'track' => '%(upstream:track)',
            'push' => '%(push:strip=0)',
            'isCurrentBranch' => '%(HEAD)',
        ],
        'tag-list.default' => [
            'refName' => '%(refname:strip=0)',
            'objectType' => '%(objecttype)',
            'objectName' => '%(objectname)',
            'taggerDate' => '%(taggerdate:iso)',
            'creatorDate' => '%(creatordate:iso)',
        ],
        // https://git-scm.com/docs/git-log#Documentation/git-log.txt-H
        'log-list.default' => [
            'commitHash' => '%H',
            'commitHash.short' => '%h',
            'treeHash' => '%T',
            'treeHash.short' => '%t',
            'parentHashes' => '%P',

            'authorName' => '%an',
            'authorName.mailMap' => '%aN',
            'authorEmail' => '%ae',
            'authorEmail.mailMap' => '%aE',
            'authorDate' => '%ad',

            'committerName' => '%cn',
            'committerName.mailMap' => '%cN',
            'committerEmail' => '%ce',
            'committerEmail.mailMap' => '%cE',
            'committerDate' => '%cd',

            'commitNotes' => '%cN',

            'refNames' => '%D',

            'commitMessage.subject' => '%s',
            'commitMessage.body' => '%b',
            'commitMessage.full' => '%B',

            // @todo GPG.
            // - %GG
            // - %G?
            // - %GS
            // - %GK
            // - %GF
            // - %GP
            // - %GT
            // - %gD
            // - %gd
            // - %gn
            // - %gN
            // - %ge
            // - %gE
            // - %gs

            // @todo Other.
            // - %(trailers[:<option>,...])

            // @todo This is an ugly shortcut.
            'nameStatus' => '',
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
