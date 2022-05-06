<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ApiResource(
    mercure: true,
    attributes: ["pagination_enabled" => false],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read:user', 'read:message']]
        ]
    ],
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read:collection', 'read:user']]
        ],
        'post' => [
		    'denormalization_context' => ['groups' => 'create:conversation']
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['users' => 'exact'])]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection'])]
    private $id;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, orphanRemoval: true, cascade: ['persist'])]
    #[Groups(['read:message', 'read:user'])]
    private $messages;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[Groups(['create:conversation', 'read:user'])]
    private $users;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getusers(): Collection
    {
        return $this->users;
    }

    public function adduser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeuser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
