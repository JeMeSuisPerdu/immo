<?php

namespace App\Entity;

use App\Entity\AttributesValue;
use App\Entity\Subcategory;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    private ?int $price = null;    

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'article', orphanRemoval: true, cascade: ['persist'])]
    private Collection $photos;

    #[ORM\ManyToOne(inversedBy: 'articles', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'articles',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subcategory $subcategory = null;

    // Relation inverse avec AttributesValue
    /**
     * @var Collection<int, AttributesValue>
     */
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: AttributesValue::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $attributeValues;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->attributeValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains(element: $photo)) {
            $this->photos->add(element: $photo);
            $photo->setArticle(article: $this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement(element: $photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getArticle() === $this) {
                $photo->setArticle(article: null);
            }
        }

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): static
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
        if (!$this->attributeValues->contains(element: $attributeValue)) {
            $this->attributeValues->add(element: $attributeValue);
            $attributeValue->setArticle(article: $this);
        }

        return $this;
    }

    public function removeAttributeValue(AttributesValue $attributeValue): static
    {
        if ($this->attributeValues->removeElement(element: $attributeValue)) {
            if ($attributeValue->getArticle() === $this) {
                $attributeValue->setArticle(article: null);
            }
        }

        return $this;
    }
}
