<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IngredientTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=IngredientTypeRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    itemOperations: [
        'get',
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],
        'patch' => ['security' => "is_granted('ROLE_ADMIN')"],
    ],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']]
)]
class IngredientType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read'])]
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read', 'write'])]
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['read', 'write'])]
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=Ingredient::class, mappedBy="type")
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setType($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getType() === $this) {
                $ingredient->setType(null);
            }
        }

        return $this;
    }
}
