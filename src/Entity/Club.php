<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClubRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
#[Vich\Uploadable]
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

    #[Vich\UploadableField(mapping: 'logos', fileNameProperty: 'logo')]
    #[Assert\File(maxSize: '5M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp'])]
    private ?File $logoFile = null;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'clubs')]
    private Collection $groups;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: PhotoGroup::class, orphanRemoval: true)]
    private Collection $photoGroups;

    #[ORM\ManyToOne(inversedBy: 'clubs')]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'club')]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'club', targetEntity: Licencie::class, orphanRemoval: true)]
    private Collection $licencie;

    

    public function __construct()
    {
       
        $this->groups = new ArrayCollection();
        $this->photoGroups = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->licencie = new ArrayCollection();
        
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

    //fonction pour le téléchargement de la photo
    public function getlogoFile(): ?File
    {
        return $this->logoFile;
    }
    //fonction pour le téléchargement de la photo. On intègre la date de Publication et le booléen downloaded
    public function setlogoFile(?File $logoFile = null): static
    {
        $this->logoFile = $logoFile;

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
    public function __toString(): string
    {
        return $this->name. ' - ' . $this->address->getCity();
    }

    /**
     * @return Collection<int, Licencie>
     */
    public function getLicencie(): Collection
    {
        return $this->licencie;
    }

    public function addLicencie(Licencie $licencie): static
    {
        if (!$this->licencie->contains($licencie)) {
            $this->licencie->add($licencie);
            $licencie->setClub($this);
        }

        return $this;
    }

    public function removeLicencie(Licencie $licencie): static
    {
        if ($this->licencie->removeElement($licencie)) {
            // set the owning side to null (unless already changed)
            if ($licencie->getClub() === $this) {
                $licencie->setClub(null);
            }
        }

        return $this;
    }

}
