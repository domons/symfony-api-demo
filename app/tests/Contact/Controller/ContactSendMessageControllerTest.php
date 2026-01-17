<?php

declare(strict_types=1);

namespace App\Tests\Contact\Controller;

use App\Contact\Application\ContactMessageSubmitting;
use App\Contact\Entity\ContactMessage;
use App\Tests\Utils\JsonDecodeTrait;
use App\Tests\Utils\ProblemDetailsAssertionsTrait;
use JsonException;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ContactSendMessageControllerTest extends WebTestCase
{
    use JsonDecodeTrait;
    use ProblemDetailsAssertionsTrait;

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testValidPayloadReturns201AndId(): void
    {
        $client = self::createClient();

        $submitting = $this->createMock(ContactMessageSubmitting::class);

        $message = $this->makeContactMessageWithId(123);

        $submitting->expects(self::once())
            ->method('submit')
            ->willReturn($message);

        self::getContainer()->set(ContactMessageSubmitting::class, $submitting);

        $client->request(
            method: 'POST',
            uri: '/api/v1/contact',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: json_encode([
                'name' => 'Jan Kowalski',
                'email' => 'jan@example.com',
                'content' => 'Treść wiadomości',
                'consent' => true,
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = $this->decodeJson($client->getResponse()->getContent());

        self::assertSame(123, $data['id'] ?? null);
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testInvalidPayloadReturns422AndErrors(): void
    {
        $client = self::createClient();

        $submitting = $this->createMock(ContactMessageSubmitting::class);
        $submitting->expects(self::never())->method('submit');

        self::getContainer()->set(ContactMessageSubmitting::class, $submitting);

        $client->request(
            method: 'POST',
            uri: '/api/v1/contact',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: json_encode([
                'name' => '',
                'email' => '',
                'content' => '',
                'consent' => false,
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload = $this->decodeJson($client->getResponse()->getContent());

        $this->assertSame('Validation Failed', $payload['title'] ?? null);
        $this->assertSame(422, $payload['status'] ?? null);
        $this->assertNotEmpty($payload['type'] ?? null);
        $this->assertNotEmpty($payload['detail'] ?? null);

        $this->assertPayloadHasViolation($payload, 'email');
        $this->assertPayloadHasViolation($payload, 'consent');
        $this->assertPayloadHasViolation($payload, 'name');
        $this->assertPayloadHasViolation($payload, 'content');
    }

    private function makeContactMessageWithId(int $id): ContactMessage
    {
        $message = new ContactMessage(
            name: 'Jan Kowalski',
            email: 'jan@example.com',
            content: 'Treść wiadomości',
        );

        $ref = new \ReflectionProperty($message, 'id');
        $ref->setAccessible(true);
        $ref->setValue($message, $id);

        return $message;
    }
}
