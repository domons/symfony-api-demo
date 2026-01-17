<?php

declare(strict_types=1);

namespace App\Contact\DTO;

final readonly class ContactMessagesListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $content,
        public string $createdAt,
    ) {
    }
}
