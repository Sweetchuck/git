<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

enum ObjectType: string
{
    case Tree = 'tree';

    case Blob = 'blob';

    case Symlink = 'symlink';
}
