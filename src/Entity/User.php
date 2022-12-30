<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name:"users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: "invited_by_user_id")]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[ORM\OneToOne(targetEntity: User::class, mappedBy: "id")]
    #[ORM\JoinColumn(name: "invited_by_user_id", referencedColumnName: "id")]
    private ?int $invited_by_user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getInvitedByUserId(): ?int
    {
        return $this->invited_by_user_id;
    }

    public function setInvitedByUserId(?int $invited_by_user_id): self
    {
        $this->invited_by_user_id = $invited_by_user_id;

        return $this;
    }
}
