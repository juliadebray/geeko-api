<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Constants\Constant;
use App\Constants\InvalidMessage;
use App\Controller\DeleteController;
use App\Controller\PotionController;
use App\Repository\PotionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PotionRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'controller' => PotionController::class,
            'security' => "is_granted('ROLE_CUSTOMER')",
        ],
    ],
    itemOperations: [
        'get',
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'controller' => DeleteController::class
        ],
        'patch' => [
            'security' => "is_granted('ROLE_ADMIN')",
            'normalization_context' => ['groups' => ['modify:item']],
        ],
    ],
    denormalizationContext: ['groups' => ['write:item']],
    normalizationContext: ['groups' => ['read:item']]
)]

class Potion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups('read:item')]
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="potions")
     * @ORM\JoinColumn(nullable=true)
     */
    // Propriété remplie automatiquement par le contrôleur PotionController
    #[Groups(['read:item'])]
    private Customer $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="potions")
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['modify:item'])]
    private Recipe $recipe;

    /**
     * @ORM\Column(type="string", length=4)
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Assert\Type(type: "string", message: InvalidMessage::BAD_TYPE)]
    #[Groups(['read:item', 'write:item', 'modify:item'])]
    private string $value;

    /**
     * @ORM\ManyToOne(targetEntity=PotionType::class, inversedBy="potions")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Groups(['read:item', 'write:item', 'modify:item'])]
    private PotionType $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    // Propriété remplie automatiquement par le contrôleur PotionController
    #[Groups(['read:item'])]
    private $created_at;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Assert\Count(
        min: Constant::NUMBER_INGREDIENT_MIN,
        max: Constant::NUMBER_INGREDIENT_MAX,
        minMessage: InvalidMessage::INGREDIENT_MIN,
        maxMessage: InvalidMessage::INGREDIENT_MAX,
    )]
    #[Groups(['write:item'])]
    private array $ingredientsList = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?PotionType
    {
        return $this->type;
    }

    public function setType(?PotionType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIngredientsList(): ?array
    {
        return $this->ingredientsList;
    }

    public function setIngredientsList(?array $ingredientsList): self
    {
        $this->ingredientsList = $ingredientsList;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
