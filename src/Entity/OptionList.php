<?php

namespace App\Entity;

use App\Repository\OptionListRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OptionListRepository::class)]
class OptionList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

   

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Photo $photos = null;

    #[ORM\ManyToOne(inversedBy: 'optionLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Options $options = null;

    #[ORM\ManyToOne(inversedBy: 'optionLists')]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'optionLists')]
    private ?Order $orders = null;

    #[ORM\Column]
    private ?bool $isArchived = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    

    public function getPhotos(): ?Photo
    {
        return $this->photos;
    }

    public function setPhotos(?Photo $photos): static
    {
        $this->photos = $photos;

        return $this;
    }

    public function getOptions(): ?Options
    {
        return $this->options;
    }

    public function setOptions(?Options $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getOrders(): ?Order
    {
        return $this->orders;
    }

    public function setOrders(?Order $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }
    public function __toString()
    {
        return $this->options->getName().' - photo :'.$this->photos->getId();
    }
}
