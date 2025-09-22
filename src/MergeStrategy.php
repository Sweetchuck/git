<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * @see https://git-scm.com/docs/merge-strategies
 */
enum MergeStrategy: string
{
    case Ort = 'ort';

    case Recursive = 'recursive';

    case Resolve = 'resolve';

    case Octopus = 'octopus';

    case Ours = 'ours';

    case Subtree = 'subtree';
}
