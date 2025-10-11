<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

/**
 * @see https://git-scm.com/docs/gitattributes#_eol
 */
enum EndOfLine: string
{
    case None = 'none';

    case CRLF = 'crlf';

    case LF = 'lf';
}
