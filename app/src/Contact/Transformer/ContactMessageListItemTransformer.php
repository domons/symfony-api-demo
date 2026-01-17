<?php

declare(strict_types=1);

namespace App\Contact\Transformer;

use App\Contact\DTO\ContactMessagesListItem;
use App\Contact\Entity\ContactMessage;

class ContactMessageListItemTransformer
{
    public function transform(ContactMessage $contactMessage): ContactMessagesListItem
    {
        return new ContactMessagesListItem(
            $contactMessage->getId(),
            $contactMessage->getName(),
            $contactMessage->getEmail(),
            $contactMessage->getContent(),
            $contactMessage->getCreatedAt()->format(DATE_ATOM)
        );
    }

    /**
     * @param iterable<ContactMessage> $items
     *
     * @return ContactMessagesListItem[]
     */
    public function transformMany(iterable $items): array
    {
        return array_map(fn (ContactMessage $item) => $this->transform($item), $items);
    }
}
