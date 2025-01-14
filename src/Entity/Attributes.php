<?php

namespace App\Entity;

use App\Repository\AttributesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttributeRepository::class)
 */
#[ORM\Entity(repositoryClass: AttributesRepository::class)]
class Attributes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null; // Ajout du type d'attribut

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'attributes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subcategory $subcategory = null;

    // Relation inverse avec AttributeValue
    /**
     * @var Collection<int, AttributesValue>
     */
    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: AttributesValue::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $attributeValues;

    #[ORM\OneToMany(mappedBy: 'attribute', targetEntity: Choice::class, orphanRemoval: true)]
    private $choices;
    
    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
        $this->choices = new ArrayCollection();

    }
    public function getChoices(): array
    {
        return $this->choices->map(fn (Choice $choice) => $choice->getValue())->toArray();
    }

    public function addChoice(Choice $choice): self
    {
        if (!$this->choices->contains($choice)) {
            $this->choices[] = $choice;
            $choice->setAttribute($this);
        }
        return $this;
    }

    public function removeChoice(Choice $choice): self
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getAttribute() === $this) {
                $choice->setAttribute(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;
        return $this;
    }

    /**
     * @return Collection<int, AttributesValue>
     */
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(AttributesValue $attributeValue): static
    {
        if (!$this->attributeValues->contains($attributeValue)) {
            $this->attributeValues->add($attributeValue);
            $attributeValue->setAttribute($this);
        }

        return $this;
    }

    public function removeAttributeValue(AttributesValue $attributeValue): static
    {
        if ($this->attributeValues->removeElement($attributeValue)) {
            if ($attributeValue->getAttribute() === $this) {
                $attributeValue->setAttribute(null);
            }
        }

        return $this;
    }
}
