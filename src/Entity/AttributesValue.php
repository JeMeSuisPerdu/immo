<?php

namespace App\Entity;

use App\Repository\AttributesValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass: AttributeValueRepository::class)
 *
 * @ORM\Table(name: "attribute_value")
 */
#[ORM\Entity(repositoryClass: AttributesValueRepository::class)]
#[ORM\Table(name: 'attribute_value')]
class AttributesValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Attributes::class, inversedBy: 'attributeValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attributes $attribute = null;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'attributeValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $valueString = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $valueInteger = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $valueBoolean = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $valueDate = null;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getValueString(): ?string
    {
        return $this->valueString;
    }

    public function setValueString(?string $valueString): self
    {
        $this->valueString = $valueString;

        return $this;
    }

    public function getValueInteger(): ?int
    {
        return $this->valueInteger;
    }

    public function setValueInteger(?int $valueInteger): self
    {
        $this->valueInteger = $valueInteger;

        return $this;
    }

    public function getValueBoolean(): ?bool
    {
        return $this->valueBoolean;
    }

    public function setValueBoolean(?bool $valueBoolean): self
    {
        $this->valueBoolean = $valueBoolean;

        return $this;
    }

    public function getValueDate(): ?\DateTimeInterface
    {
        return $this->valueDate;
    }

    public function setValueDate(?\DateTimeInterface $valueDate): self
    {
        $this->valueDate = $valueDate;

        return $this;
    }
}
