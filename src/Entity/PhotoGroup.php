<?php

namespace App\Entity;

use App\Repository\PhotoGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoGroupRepository::class)]
class PhotoGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'photoGroup')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $groupID = null;

    #[ORM\ManyToOne(inversedBy: 'photoGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

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
}
