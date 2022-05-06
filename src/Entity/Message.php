<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource (
    mercure: true,
    attributes: ["pagination_enabled" => false],
    normalizationContext: ['groups' => ['read:message']],
    denormalizationContext: ['groups' => ['post:message']],
    collectionOperations: ['post', 'get'],
    itemOperations: ['get', 'delete']
)]
#[ApiFilter(SearchFilter::class, properties: ['conversation' => 'exact'])]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:message'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['post:message', 'read:message'])]
    private $content;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private $isRead;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:message'])]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:message', 'read:message'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:message'])]
    private $conversation;

    public function __construct()
    {
        $this->createdAt = new \DateTime("now", new \DateTimeZone('Europe/Paris'));
        $this->isRead = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = new \DateTime("now", new \DateTimeZone('Europe/Paris'));

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

}
