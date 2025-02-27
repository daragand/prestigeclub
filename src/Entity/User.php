<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface,\Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(min: 8, minMessage: 'Votre mot de passe doit contenir au moins 8 caractères')]
    #[Assert\Regex(pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/', message: 'Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial')]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Licencie::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_licencie')]
    private Collection $licencies;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: Club::class, inversedBy: 'users')]
    private Collection $club;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;


    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Order::class, orphanRemoval: true,cascade: ['persist', 'remove'])]
    private Collection $orders;

    #[ORM\OneToOne(mappedBy: 'users',orphanRemoval: true)]
    private ?Cart $cart = null;

    #[ORM\Column(length: 255)]
    private ?string $uuidUser = null;

    

    public function __construct()
    {
        $this->licencies = new ArrayCollection();
        $this->club = new ArrayCollection();
        $this->orders = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Licencie>
     */
    public function getLicencies(): Collection
    {
        return $this->licencies;
    }

    public function addLicency(Licencie $licency): static
    {
        if (!$this->licencies->contains($licency)) {
            $this->licencies->add($licency);
        }

        return $this;
    }

    public function removeLicency(Licencie $licency): static
    {
        $this->licencies->removeElement($licency);

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Club>
     */
    public function getClub(): Collection
    {
        return $this->club;
    }

    public function addClub(Club $club): static
    {
        if (!$this->club->contains($club)) {
            $this->club->add($club);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        $this->club->removeElement($club);

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUsers($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUsers() === $this) {
                $order->setUsers(null);
            }
        }

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->firstname. ' '.$this->lastname;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        
        if ($cart === null && $this->cart !== null) {
            $this->cart->setUsers(null);
        }

        
        if ($cart !== null && $cart->getUsers() !== $this) {
            $cart->setUsers($this);
        }

        $this->cart = $cart;

        return $this;
    }
    public function removeCart(Cart $cart): static
    {
        
        if ($cart->getUsers() === $this) {
            $cart->setUsers(null);
        }

        return $this;
    }
     /** @see \Serializable::serialize() */
     public function serialize()
     {
         return serialize(array(
             $this->id,
             $this->email,
             $this->password,
             // see section on salt below
             // $this->salt,
         ));
     }
 
     /** @see \Serializable::unserialize() */
     public function unserialize($serialized)
     {
         list (
             $this->id,
             $this->email,
             $this->password,
             // see section on salt below
             // $this->salt
         ) = unserialize($serialized);
     }

     public function getUuidUser(): ?string
     {
         return $this->uuidUser;
     }

     public function setUuidUser(string $uuidUser): static
     {
         $this->uuidUser = $uuidUser;

         return $this;
     }
    
}
