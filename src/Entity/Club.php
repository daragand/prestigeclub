<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\ManyToMany(targetEntity: Licencie::class, inversedBy: 'clubs')]
    private Collection $licencies;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'clubs')]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: PhotoGroup::class, orphanRemoval: true)]
    private Collection $photoGroups;

    #[ORM\ManyToOne(inversedBy: 'clubs')]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'club')]
    private Collection $users;

    

    public function __construct()
    {
        $this->licencies = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->photoGroups = new ArrayCollection();
        $this->users = new ArrayCollection();
        
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
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

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * @return Collection<int, PhotoGroup>
     */
    public function getPhotoGroups(): Collection
    {
        return $this->photoGroups;
    }

    public function addPhotoGroup(PhotoGroup $photoGroup): static
    {
        if (!$this->photoGroups->contains($photoGroup)) {
            $this->photoGroups->add($photoGroup);
            $photoGroup->setClub($this);
        }

        return $this;
    }

    public function removePhotoGroup(PhotoGroup $photoGroup): static
    {
        if ($this->photoGroups->removeElement($photoGroup)) {
            // set the owning side to null (unless already changed)
            if ($photoGroup->getClub() === $this) {
                $photoGroup->setClub(null);
            }
        }

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
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addClub($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeClub($this);
        }

        return $this;
    }

}
