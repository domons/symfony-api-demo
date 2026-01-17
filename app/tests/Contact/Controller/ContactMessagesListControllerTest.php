<?php

declare(strict_types=1);

namespace App\Tests\Contact\Controller;

use App\Contact\Entity\ContactMessage;
use App\Contact\Repository\ContactMessageRepository;
use App\Tests\Utils\JsonDecodeTrait;
use Doctrine\ORM\QueryBuilder;
use JsonException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\MockObject\Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ContactMessagesListControllerTest extends WebTestCase
{
    use JsonDecodeTrait;

    /**
     * @throws Exception
     * @throws ReflectionException
     * @throws JsonException
     */
    public function testItSerializesEntities(): void
    {
        $message1 = $this->makeContactMessage(
            id: 2,
            name: 'Jan Kowalski',
            email: 'jan@kowalski.pl',
            content: 'Treść 2',
            createdAt: new \DateTime('2026-01-02 00:00:00'),
        );

        $message2 = $this->makeContactMessage(
            id: 1,
            name: 'Anna Nowak',
            email: 'anna@nowak.pl',
            content: 'Treść 1',
            createdAt: new \DateTime('2026-01-01 00:00:00'),
        );

        $client = self::createClient();
        $qb = $this->createStub(QueryBuilder::class);

        $repo = $this->createMock(ContactMessageRepository::class);
        $repo->expects(self::once())
            ->method('createListQueryBuilder')
            ->willReturn($qb);

        $pagination = $this->createMock(PaginationInterface::class);
        $pagination->expects(self::once())
            ->method('getItems')
            ->willReturn([$message1, $message2]);

        $pagination->expects(self::once())
            ->method('getTotalItemCount')
            ->willReturn(2);

        $paginator = $this->createMock(PaginatorInterface::class);
        $paginator->expects(self::once())
            ->method('paginate')
            ->with($qb, 1, 25)
            ->willReturn($pagination);

        $container = self::getContainer();
        $container->set(ContactMessageRepository::class, $repo);
        $container->set(PaginatorInterface::class, $paginator);

        $client->request('GET', '/api/v1/contact');

        self::assertResponseIsSuccessful();

        $data = $this->decodeJson($client->getResponse()->getContent());

        self::assertSame(2, $data['totalItems']);
        self::assertCount(2, $data['items']);

        $first = $data['items'][0];

        self::assertSame(2, $first['id']);
        self::assertSame('Jan Kowalski', $first['name']);
        self::assertSame('jan@kowalski.pl', $first['email']);
        self::assertSame('Treść 2', $first['content']);
        self::assertSame((new \DateTime('2026-01-02 00:00:00'))->format(DATE_ATOM), $first['createdAt']);

        $second = $data['items'][1];

        self::assertSame(1, $second['id']);
        self::assertSame('Anna Nowak', $second['name']);
        self::assertSame('anna@nowak.pl', $second['email']);
        self::assertSame('Treść 1', $second['content']);
        self::assertSame((new \DateTime('2026-01-01 00:00:00'))->format(DATE_ATOM), $second['createdAt']);
    }

    /**
     * @throws ReflectionException
     */
    private function makeContactMessage(
        int $id,
        string $name,
        string $email,
        string $content,
        \DateTime $createdAt,
    ): ContactMessage {
        $message = new ContactMessage($name, $email, $content);

        $ref = new \ReflectionProperty($message, 'id');
        $ref->setAccessible(true);
        $ref->setValue($message, $id);

        $ref = new \ReflectionProperty($message, 'createdAt');
        $ref->setAccessible(true);
        $ref->setValue($message, $createdAt);

        return $message;
    }
}
