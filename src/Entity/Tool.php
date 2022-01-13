<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ToolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ToolRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post' =>
            [
                'method' => 'POST',
                'path' => '/tools',
                'security' => "is_granted('ROLE_ADMIN')",
            ],
    ],
    itemOperations: [
        'patch' =>
            [
                'method' => 'PATCH',
                'path' => '/tools',
                'security' => "is_granted('ROLE_ADMIN')",
            ],
        'delete' =>
            [
                'method' => 'DELETE',
                'path' => '/tools',
                'security' => "is_granted('ROLE_ADMIN')",
            ],
        'get',
    ],
    denormalizationContext: ['groups' => ['write:item']],
    normalizationContext: ['groups' => ['read:collection']]
)]
class Tool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Tool'])]
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champ est recquis")
     * @Assert\NotNull(message="ce champ est recquis")
     */
    #[Groups(['read:Tool', 'read:collection', 'write:item'])]
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champ est recquis")
     * @Assert\NotNull(message="ce champ est recquis")
     */
    #[Groups(['read:collection','write:item'])]
    private string $image;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="alchemistTool")
     */
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setAlchemistTool($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getAlchemistTool() === $this) {
                $customer->setAlchemistTool(null);
            }
        }

        return $this;
    }
}
