<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Contact\Application\ContactMessagesListing;
use App\Contact\DTO\ContactMessagesListResponse;
use Nelmio\ApiDocBundle\Attribute as Nelmio;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag('Contact')]
class ContactMessagesListController extends AbstractController
{
    #[OA\Parameter(
        name: 'page',
        description: 'Numer strony (paginacja)',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1, minimum: 1),
    )]
    #[OA\Response(
        response: 200,
        description: 'Zwraca wiadomości wysłane przez formularz kontaktowy',
        content: new OA\JsonContent(ref: new Nelmio\Model(type: ContactMessagesListResponse::class)),
    )]
    public function __invoke(
        Request $request,
        ContactMessagesListing $contactMessagesListing,
    ): Response {
        $page = $request->query->getInt('page', 1);

        $listResponse = $contactMessagesListing->list($page);

        return $this->json($listResponse);
    }
}
