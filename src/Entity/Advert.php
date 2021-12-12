<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\TermFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\AdvertRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Entity(repositoryClass: AdvertRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
        'post' => ['method' => 'post'],
    ],
    itemOperations: [
        'get' => ['method' => 'get'],
    ],
    attributes: [
        'normalization_context' => ['groups' => ['advert:read', 'advert:write']],
        'denormalization_context' => ['groups' => ['advert:write']],
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['publishedAt', 'price'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(SearchFilter::class, properties: ['category.name : exact'])]
class Advert
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups(['advert:read', 'advert:write'])]
    private ?int $id = null;


    #[ORM\Column(type: 'string', length: '255')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le titre doit contenir au minimum 3 caractères.',
        maxMessage: 'Le titre doit contenir au maximum 100 caractères.'
    )]
    #[Groups(['advert:read', 'advert:write'])]
    private ?string $title = null;


    #[ORM\Column(type: 'string', length: '1200')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 1200,
        maxMessage: 'Le contenu doit avoir au maximum 1200 caractères.'
    )]
    #[Groups(['advert:read', 'advert:write'])]
    private ?string $content = null;


    #[ORM\Column(type: 'string', length: '255')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom de l\'auteur doit avoir au maximum 255 caractères.'
    )]
    #[Groups(['advert:read', 'advert:write'])]
    private ?string $author = null;


    #[ORM\Column(type: 'string', length: '180')]
    #[Assert\NotBlank]
    #[Assert\Email( message: 'Ne correspond pas à une adresse email.')]
    #[Assert\Length(
        max: 180,
        maxMessage: 'L\'adresse email doit avoir au maximum 180 caractères.'
    )]
    #[Groups(['advert:read', 'advert:write'])]
    private ?string $email = null;


    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: 'false')]
    #[Groups(['advert:read', 'advert:write'])]
    private ?Category $category = null;


    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\Length(
        min: 1,
        max: 1000000.00,
        minMessage: 'Le prix ne peut pas être inférieur à 1.',
        maxMessage: 'Le prix ne peut pas être supérieur à 1000000.00 .'
    )]
    #[Groups(['advert:read', 'advert:write'])]
    private ? float $price = null;


    #[ORM\Column(type: 'string', length: '255')]
    #[Groups(['advert:read', 'advert:write'])]
    private ?string $state = null;


    #[ORM\Column(type: 'datetime')]
    #[Groups(['advert:read', 'advert:write'])]
    private ?\DateTime $createdAt = null;


    #[ORM\Column(type: 'datetime', nullable: 'true')]
    #[Groups(['advert:read', 'advert:write'])]
    private ?\DateTime $publishedAt = null;

    public function __construct()
    {
        $this->state = "draft";
        $this->createdAt = new \DateTime('now', 'Europe/Paris');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
