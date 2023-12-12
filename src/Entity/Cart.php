<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToMany(targetEntity: Photo::class, mappedBy: 'carts')]
    private Collection $photos;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?Forfait $forfait = null;

   

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: Order::class, orphanRemoval: true)]
    private Collection $orders;

    

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: OptionList::class)]
    private Collection $optionLists;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    private ?User $users = null;

    

    public function __construct()
    {
        $this->photos = new ArrayCollection();
       
        $this->orders = new ArrayCollection();
        $this->optionLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

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
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->addCart($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            $photo->removeCart($this);
        }

        return $this;
    }

    public function getForfait(): ?Forfait
    {
        return $this->forfait;
    }

    public function setForfait(?Forfait $forfait): static
    {
        $this->forfait = $forfait;

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
            $order->setCart($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCart() === $this) {
                $order->setCart(null);
            }
        }

        return $this;
    }

    

   public function __toString(): string
    {
        return $this->forfait->getName();
    }

   /**
    * @return Collection<int, OptionList>
    */
   public function getOptionLists(): Collection
   {
       return $this->optionLists;
   }

   public function addOptionList(OptionList $optionList): static
   {
       if (!$this->optionLists->contains($optionList)) {
           $this->optionLists->add($optionList);
           $optionList->setCart($this);
       }

       return $this;
   }

   public function removeOptionList(OptionList $optionList): static
   {
       if ($this->optionLists->removeElement($optionList)) {
           // set the owning side to null (unless already changed)
           if ($optionList->getCart() === $this) {
               $optionList->setCart(null);
           }
       }

       return $this;
   }

   public function getUsers(): ?User
   {
       return $this->users;
   }

   public function setUsers(?User $users): static
   {
       $this->users = $users;

       return $this;
   }
}
