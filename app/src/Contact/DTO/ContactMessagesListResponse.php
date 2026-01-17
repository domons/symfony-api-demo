<?php

declare(strict_types=1);

namespace App\Contact\DTO;

final readonly class ContactMessagesListResponse
{
    /**
     * @param ContactMessagesListItem[] $items
     */
    public function __construct(
        public array $items,
        public int $totalItems,
    ) {
    }
}
