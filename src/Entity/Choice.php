<?php

namespace App\Entity;

use App\Repository\ChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChoiceRepository::class)
 * @ORM\Table(name="choice")
 */
#[ORM\Entity(repositoryClass: ChoiceRepository::class)]
#[ORM\Table(name: 'choice')]
class Choice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Attributes::class, inversedBy: 'choices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attributes $attribute = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttribute(): ?Attributes
    {
        return $this->attribute;
    }

    public function setAttribute(?Attributes $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
