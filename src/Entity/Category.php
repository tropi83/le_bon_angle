<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: CategoryRepository::class)]

#[ApiResource(
    collectionOperations: [
        'get' => ['method' => 'get'],
    ],
    itemOperations: [
        'get' => ['method' => 'get'],
    ],
    attributes: [
        'normalization_context' => ['groups' => ['category:read', 'advert:read']],
        'denormalization_context' => ['groups' => ['category:write', 'advert:read']],
    ],
)]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups(['category:read', 'category:write', 'advert:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: '255')]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom de la catégorie doit contenir au maximum 255 caractères.'
    )]
    #[Groups(['category:read', 'category:write', 'advert:read'])]
    private ?string $name = null;

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

    public function __toString() {
        return $this->name;
    }

}
