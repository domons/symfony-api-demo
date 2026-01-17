<?php

declare(strict_types=1);

namespace App\Contact\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ContactMessageRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 70)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 1, max: 70)]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 3000)]
    private string $content;

    #[Assert\IsTrue]
    private bool $consent;

    public function __construct(string $name, string $email, string $content, bool $consent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->content = $content;
        $this->consent = $consent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isConsent(): bool
    {
        return $this->consent;
    }
}
