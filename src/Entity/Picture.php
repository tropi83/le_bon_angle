<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\CreatePictureAction;
use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Entity(repositoryClass: PictureRepository::class)]
/**
 * @Vich\Uploadable
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => [
            'controller' => CreatePictureAction::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'picture_create'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    iri: 'http://schema.org/Picture',
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['picture:read']]
)]
class Picture
{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups(['picture:read', 'picture:write'])]
    private ?int $id = null;

    //#[Vich\UploadableField(array('mapping' => 'picture', 'fileNameProperty' => 'path'))]
    /**
     * @Vich\UploadableField(mapping="picture", fileNameProperty="path")
     */
    #[Groups(['picture:read', 'picture:write'])]
    #[Assert\NotNull(groups: ['picture_create'])]
    public ?File $file = null;


    #[ApiProperty(iri: 'http://schema.org/path')]
    #[ORM\Column(type: 'string', nullable: 'true')]
    #[Groups(['picture:read', 'picture:write'])]
    public ?string $path = null;


    #[ORM\Column(type: 'datetime')]
    #[Groups(['picture:read', 'picture:write'])]
    private ?\DateTimeInterface $createdAt = null;


    #[ORM\ManyToOne(targetEntity: Advert::class)]
    #[ORM\JoinColumn(nullable: 'false')]
    #[Groups(['picture:read', 'picture:write'])]
    private ?Advert $advert = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
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

    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

}
