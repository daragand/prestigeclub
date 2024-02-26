<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotoGroupRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoGroupRepository::class)]
#[Vich\Uploadable]
class PhotoGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = 'default_group.jpg';

    //Photo de groupe provisoire pour charger les images. Non utilisée dans la BDD. Le mapping est disponible dans le fichier config/vich_uploader.yaml
    #[Vich\UploadableField(mapping: 'groupes', fileNameProperty: 'path')]
    #[Assert\File(maxSize: '5M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp'])]
    private ?File $photoGroupFile = null;

    #[ORM\ManyToOne(inversedBy: 'photoGroup')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $groupID = null;

    #[ORM\ManyToOne(inversedBy: 'photoGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path ?: 'default_group.jpg';

        return $this;
    }

    public function getGroupID(): ?Group
    {
        return $this->groupID;
    }

    public function setGroupID(?Group $groupID): static
    {
        $this->groupID = $groupID;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }

    //fonction pour le téléchargement de la photo de groupe.
    public function getPhotoGroupFile(): ?File
    {
        return $this->photoGroupFile;
    }
    //fonction pour le téléchargement de la photo de groupe.
    public function setPhotoGroupFile(?File $photoGroupFile = null): static
    {
        $this->photoGroupFile = $photoGroupFile;

        if (null !== $photoGroupFile) {
            $this->datePublication = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }
}
