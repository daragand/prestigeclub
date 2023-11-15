<?php

namespace App\Entity;

use App\Repository\LicencieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LicencieRepository::class)]
class Licencie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Club::class, mappedBy: 'licencies')]
    private Collection $clubs;

    #[ORM\OneToMany(mappedBy: 'licencie', targetEntity: Livret::class, orphanRemoval: true)]
    private Collection $livrets;

    #[ORM\OneToMany(mappedBy: 'licencie', targetEntity: Photo::class, orphanRemoval: true)]
    private Collection $photos;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'licencies')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'licencies')]
    private Collection $Groupes;

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->livrets = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->Groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

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
            $club->addLicency($this);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        if ($this->clubs->removeElement($club)) {
            $club->removeLicency($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Livret>
     */
    public function getLivrets(): Collection
    {
        return $this->livrets;
    }

    public function addLivret(Livret $livret): static
    {
        if (!$this->livrets->contains($livret)) {
            $this->livrets->add($livret);
            $livret->setLicencie($this);
        }

        return $this;
    }

    public function removeLivret(Livret $livret): static
    {
        if ($this->livrets->removeElement($livret)) {
            // set the owning side to null (unless already changed)
            if ($livret->getLicencie() === $this) {
                $livret->setLicencie(null);
            }
        }

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
            $photo->setLicencie($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getLicencie() === $this) {
                $photo->setLicencie(null);
            }
        }

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
            $user->addLicency($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeLicency($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupes(): Collection
    {
        return $this->Groupes;
    }

    public function addGroupe(Group $groupe): static
    {
        if (!$this->Groupes->contains($groupe)) {
            $this->Groupes->add($groupe);
        }

        return $this;
    }

    public function removeGroupe(Group $groupe): static
    {
        $this->Groupes->removeElement($groupe);

        return $this;
    }
    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
