<?php

declare(strict_types=1);

namespace App\Contact\Application;

use App\Contact\DTO\ContactMessagesListResponse;
use App\Contact\Repository\ContactMessageRepository;
use App\Contact\Transformer\ContactMessageListItemTransformer;
use Knp\Component\Pager\PaginatorInterface;

class ContactMessagesListing
{
    private const int ITEMS_PER_PAGE = 25;

    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly ContactMessageRepository $contactMessageRepository,
        private readonly ContactMessageListItemTransformer $contactMessageListItemTransformer,
    ) {
    }

    public function list(int $page): ContactMessagesListResponse
    {
        $page = max(1, $page);

        $pagination = $this->paginator->paginate(
            $this->contactMessageRepository->createListQueryBuilder(),
            $page,
            self::ITEMS_PER_PAGE,
        );

        return new ContactMessagesListResponse(
            items: $this->contactMessageListItemTransformer->transformMany($pagination->getItems()),
            totalItems: $pagination->getTotalItemCount(),
        );
    }
}
