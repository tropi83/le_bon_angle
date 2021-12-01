<?php

namespace App\Entity;

use App\Repository\AdminUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: AdminUserRepository::class)]
class AdminUser implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: '180', unique: 'true')]
    #[Assert\NotBlank]
    #[Assert\Email( message: 'Ne correspond pas à une adresse email.')]
    #[Assert\Length(
        max: 180,
        maxMessage: 'L\'adresse email doit avoir au maximum 180 caractères.'
    )]
    private ?string $email = null;


    #[ORM\Column(type: 'json')]
    private array $roles = [];


    #[ORM\Column(type: 'string', length: '128', unique: 'true')]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 128,
        maxMessage: 'Le nom d\'utilisateur doit avoir au maximum 128 caractères.'
    )]
    private ?string $username = null;

    /**
     * @var string|null The hashed password
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: 'string', length: '255')]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le mot de passe doit avoir au maximum 255 caractères.'
    )]
    private ?string $plainPassword = null;


    public function __construct()
    {
        $this->roles = array('ROLE_ADMIN');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }


    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        if(!empty($plainPassword)){
            $this->password = null;
        }
        $this->plainPassword = $plainPassword;

        return $this;
    }


}
