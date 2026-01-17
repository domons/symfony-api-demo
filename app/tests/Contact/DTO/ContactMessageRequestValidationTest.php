<?php

declare(strict_types=1);

namespace App\Tests\Contact\DTO;

use App\Contact\DTO\ContactMessageRequest;
use App\Tests\Utils\ValidatorAssertionsTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ContactMessageRequestValidationTest extends TestCase
{
    use ValidatorAssertionsTrait;

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidPayloadHasNoViolations(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: 'jan@kowalski.pl',
            content: 'Treść wiadomości',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertCount(0, $violations);
    }

    public function testNameCannotBeBlank(): void
    {
        $message = new ContactMessageRequest(
            name: '',
            email: 'jan@kowalski.pl',
            content: 'Treść wiadomości',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'name', NotBlank::IS_BLANK_ERROR);
    }

    public function testNameMaxLength(): void
    {
        $message = new ContactMessageRequest(
            name: str_repeat('a', 71),
            email: 'jan@kowalski.pl',
            content: 'Treść wiadomości',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'name', Length::TOO_LONG_ERROR);
    }

    public function testEmailCannotBeBlank(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: '',
            content: 'Treść wiadomości',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'email', NotBlank::IS_BLANK_ERROR);
    }

    public function testEmailMustBeValid(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: 'invalid-email',
            content: 'Treść wiadomości',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'email', Email::INVALID_FORMAT_ERROR);
    }

    public function testEmailMaxLength(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: sprintf('%s@a.pl', str_repeat('a', 71)),
            content: 'Treść wiadomości',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'email', Length::TOO_LONG_ERROR);
    }

    public function testContentCannotBeBlank(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: 'jan@kowalski.pl',
            content: '',
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'content', NotBlank::IS_BLANK_ERROR);
    }

    public function testContentMaxLength(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: 'jan@kowalski.pl',
            content: str_repeat('a', 3001),
            consent: true,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'content', Length::TOO_LONG_ERROR);
    }

    public function testConsentMustBeTrue(): void
    {
        $message = new ContactMessageRequest(
            name: 'Jan Kowalski',
            email: 'jan@kowalski.pl',
            content: 'Treść wiadomości',
            consent: false,
        );

        $violations = $this->validator->validate($message);

        $this->assertViolationFor($violations, 'consent', IsTrue::NOT_TRUE_ERROR);
    }
}
