<?php

namespace App\Entity;

use App\Repository\OptionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionsRepository::class)]
class Options
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

   

    #[ORM\OneToMany(mappedBy: 'options', targetEntity: OptionList::class, orphanRemoval: true)]
    private Collection $optionLists;

    public function __construct()
    {
        
        $this->optionLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
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
            $optionList->setOptions($this);
        }

        return $this;
    }

    public function removeOptionList(OptionList $optionList): static
    {
        if ($this->optionLists->removeElement($optionList)) {
            // set the owning side to null (unless already changed)
            if ($optionList->getOptions() === $this) {
                $optionList->setOptions(null);
            }
        }

        return $this;
    }
}
