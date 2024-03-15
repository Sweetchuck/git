<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Struct;

use Sweetchuck\Git\FileStatus;

readonly class ChangedFile implements \JsonSerializable
{

    public function __construct(
        public readonly ?string $fileName = null,
        public readonly ?FileStatus $status = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'filePath' => $this->fileName,
            'status' => $this->status,
        ];
    }
}
