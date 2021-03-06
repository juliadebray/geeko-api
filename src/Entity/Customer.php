<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\CustomerController;
use App\Controller\DeleteController;
use App\Controller\UpdateUserController;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Constants\InvalidMessage;

#[
    ApiResource(
        collectionOperations: [
          'get',
          'post' => ['controller' => CustomerController::class],
        ],
        itemOperations: [
            'patch'=> ['controller' => UpdateUserController::class,
                "security" => "is_granted('CUSTOMER_EDIT', object)"],
            'delete'=> ['controller' => DeleteController::class],
            'get' => [
                "security" => "is_granted('CUSTOMER_VIEW', object)",
            ],
        ],
        denormalizationContext: ['groups' => ['write:item']],
        normalizationContext: ['groups' => ['read:item', 'read:Tool']]
    ),
    ApiFilter(SearchFilter::class, properties: ['email' => 'exact', 'status' => 'exact']),
    ApiFilter(OrderFilter::class, properties: ['pseudo', 'alchemist_level', 'alchemist_tool', 'email', 'status', 'created_at', 'updated_at', 'token_password'], arguments: ['orderParameterName' => 'order'])
]
/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="integer")
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Assert\Type(type: "integer", message: InvalidMessage::BAD_TYPE)]
    #[Groups(['read:item', 'write:item'])]
    private int $alchemistLevel;

    /**
     * @ORM\OneToMany(targetEntity=Potion::class, mappedBy="customer")
     */
    private Collection $potions;

    /**
     * @ORM\ManyToOne(targetEntity=Tool::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank(message: InvalidMessage::NOT_BLANK)]
    #[Assert\NotNull(message: InvalidMessage::NOT_NULL)]
    #[Groups(['read:item', 'write:item', 'read:Tool'])]
    private Tool $alchemistTool;

    public function __construct()
    {
        parent::__construct();
        $this->potions = new ArrayCollection();
    }

    public function getAlchemistLevel(): ?int
    {
        return $this->alchemistLevel;
    }

    public function setAlchemistLevel(int $alchemistLevel): self
    {
        $this->alchemistLevel = $alchemistLevel;

        return $this;
    }

    /**
     * @return Collection|Potion[]
     */
    public function getPotions(): Collection
    {
        return $this->potions;
    }

    public function addPotion(Potion $potion): self
    {
        if (!$this->potions->contains($potion)) {
            $this->potions[] = $potion;
            $potion->setCustomer($this);
        }

        return $this;
    }

    public function removePotion(Potion $potion): self
    {
        if ($this->potions->removeElement($potion)) {
            // set the owning side to null (unless already changed)
            if ($potion->getCustomer() === $this) {
                $potion->setCustomer(null);
            }
        }

        return $this;
    }

    public function getAlchemistTool(): ?Tool
    {
        return $this->alchemistTool;
    }

    public function setAlchemistTool(?Tool $alchemistTool): self
    {
        $this->alchemistTool = $alchemistTool;

        return $this;
    }
}
