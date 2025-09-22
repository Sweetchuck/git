<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

enum DiffAlgorithm: string
{
    case Histogram = 'histogram';

    case Minimal = 'minimal';

    case Myers = 'myers';

    case Patience = 'patience';
}
