<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getUsers'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Firstname is required and cannot be null")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your firstname must be at least {{ limit }} characters long',
        maxMessage: 'Your firstname cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['getUsers', 'createUser'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Lastname is required and cannot be null")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your lastname must be at least {{ limit }} characters long',
        maxMessage: 'Your lastname cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['getUsers', 'createUser'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "Email is required and cannot be null")]
    #[Assert\Length(
        min: 2,
        max: 180,
        minMessage: 'Your email must be at least {{ limit }} characters long',
        maxMessage: 'Your email cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['getUsers', 'createUser'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Password is required and cannot be null")]
    #[Assert\Length(
        min: 12,
        max: 255,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['getUsers', 'createUser'])]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getUsers'])]
    private ?Customer $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
