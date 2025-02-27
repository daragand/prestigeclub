<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Club::class, inversedBy: 'clubs' ,cascade: ["persist"])]
    private Collection $clubs;

    #[ORM\OneToMany(mappedBy: 'groupID', targetEntity: PhotoGroup::class, orphanRemoval: true)]
    private Collection $photoGroup;

    #[ORM\OneToMany(mappedBy: 'groupes', targetEntity: Licencie::class, orphanRemoval: true)]
    private Collection $licencies;

   

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->photoGroup = new ArrayCollection();
        $this->licencies = new ArrayCollection();
       
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

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): static
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
            $club->addGroup($this);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        if ($this->clubs->removeElement($club)) {
            $club->removeGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PhotoGroup>
     */
    public function getPhotoGroup(): Collection
    {
        return $this->photoGroup;
    }

    public function addPhotoGroup(PhotoGroup $photoGroup): static
    {
        if (!$this->photoGroup->contains($photoGroup)) {
            $this->photoGroup->add($photoGroup);
            $photoGroup->setGroupID($this);
        }

        return $this;
    }

    public function removePhotoGroup(PhotoGroup $photoGroup): static
    {
        if ($this->photoGroup->removeElement($photoGroup)) {
            // set the owning side to null (unless already changed)
            if ($photoGroup->getGroupID() === $this) {
                $photoGroup->setGroupID(null);
            }
        }

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->name;
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
            $licency->setGroupes($this);
        }

        return $this;
    }

    public function removeLicency(Licencie $licency): static
    {
        if ($this->licencies->removeElement($licency)) {
            // set the owning side to null (unless already changed)
            if ($licency->getGroupes() === $this) {
                $licency->setGroupes(null);
            }
        }

        return $this;
    }
}
