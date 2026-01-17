<?php

declare(strict_types=1);

namespace App\Contact\Application;

use App\Contact\DTO\ContactMessageRequest;
use App\Contact\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;

class ContactMessageSubmitting
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function submit(ContactMessageRequest $contactMessageRequest): ContactMessage
    {
        $message = new ContactMessage(
            name: $contactMessageRequest->getName(),
            email: $contactMessageRequest->getEmail(),
            content: $contactMessageRequest->getContent(),
        );

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
}
