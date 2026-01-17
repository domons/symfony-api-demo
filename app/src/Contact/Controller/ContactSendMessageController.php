<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Contact\Application\ContactMessageSubmitting;
use App\Contact\DTO\ContactMessageCreatedResponse;
use App\Contact\DTO\ContactMessageRequest;
use Nelmio\ApiDocBundle\Attribute as Nelmio;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[OA\Tag('Contact')]
class ContactSendMessageController extends AbstractController
{
    #[OA\Response(
        response: 201,
        description: 'Poprawny zapis wiadomości z formularza kontaktowego',
        content: new OA\JsonContent(
            ref: new Nelmio\Model(type: ContactMessageCreatedResponse::class)
        ),
    )]
    #[OA\Response(
        response: 422,
        description: 'Błąd walidacji pól formularza kontaktowego',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationProblemDetails'),
    )]
    public function __invoke(
        #[MapRequestPayload] ContactMessageRequest $contactMessageRequest,
        ContactMessageSubmitting $contactMessageSubmitting,
    ): Response {
        $message = $contactMessageSubmitting->submit($contactMessageRequest);

        return $this->json(
            new ContactMessageCreatedResponse($message->getId()),
            Response::HTTP_CREATED
        );
    }
}
