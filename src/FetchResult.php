<?php

declare(strict_types=1);

namespace Sweetchuck\Git;

enum FetchResult: string
{

    case FastForward = ' ';

    case ForcedUpdate = '+';

    /**
     * Prune.
     */
    case RemoteDeleted = '-';

    case NewRef = '*';

    case UpdatedTag = 't';

    case UpToDate = '=';

    case Rejected = '!';
}
