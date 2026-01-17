<?php

declare(strict_types=1);

namespace App\Contact\Entity;

use App\Contact\Repository\ContactMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ContactMessageRepository::class)]
class ContactMessage
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 70)]
    private string $name;

    #[ORM\Column(type: 'string', length: 70)]
    private string $email;

    #[ORM\Column(type: 'string', length: 3000)]
    private string $content;

    public function __construct(string $name, string $email, string $content)
    {
        $this->name = $name;
        $this->email = $email;
        $this->content = $content;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ContactMessage
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): ContactMessage
    {
        $this->email = $email;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): ContactMessage
    {
        $this->content = $content;

        return $this;
    }
}
