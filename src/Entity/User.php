<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Constants\InvalidMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"administrator"="Administrator", "customer"="Customer"})
 * @UniqueEntity("email", message="cet email existe déjà")
 * @UniqueEntity("pseudo", message="ce pseudo existe déjà")
 */
Abstract class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    #[Groups(['read:item'])]
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Assert\Length(max: 255, maxMessage: InvalidMessage::MAX_MESSAGE)]
    #[Assert\Type(type: "string", message: InvalidMessage::BAD_TYPE)]
    #[Groups(['read:item', 'write:item'])]
    private string $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Assert\Length(max: 180, maxMessage: InvalidMessage::MAX_MESSAGE)]
    #[Assert\Email(message: InvalidMessage::INVALID_EMAIL)]
    #[Groups(['read:item', 'write:item'])]
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    // Propriété remplie automatiquement par CustomerController et AdminController
    #[ApiProperty(security: "is_granted('ROLE_ADMIN')")]
    #[Groups(['read:item'])]
    private ?array $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Assert\Regex( "/^(?=.*\W)(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/", message: InvalidMessage::INVALID_PASSWORD)]
    #[Groups(['write:item'])]
    private string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    // Propriété remplie automatiquement par CustomerController et AdminController
    #[Groups(['read:item'])]
    private ?string $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    // Propriété remplie automatiquement lors d'une demande de nouveau mot de passe
    private ?string $tokenPassword;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    // Propriété remplie automatiquement par CustomerController et AdminController
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    // Propriété remplie automatiquement par CustomerController et AdminController
    private $updatedAt;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    /**
     * @return UuidV4
     */
    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->username;
    }

    public function setName(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTokenPassword()
    {
        return $this->tokenPassword;
    }

    public function setTokenPassword(string $tokenPassword)
    {
        $this->tokenPassword = $tokenPassword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $status
     * @return User
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
