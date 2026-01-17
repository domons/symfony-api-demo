<?php

declare(strict_types=1);

namespace App\Contact\DTO;

final readonly class ContactMessageCreatedResponse
{
    public function __construct(
        public int $id,
    ) {
    }
}
