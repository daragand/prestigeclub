<?php

namespace App\Entity;

use DateTime;
use App\Entity\Cart;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[Vich\Uploadable]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = 'default.jpg';

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column]
    private ?bool $downloaded = null;

    #[Vich\UploadableField(mapping: 'photos', fileNameProperty: 'path')]
    #[Assert\File(maxSize: '5M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp'])]
    private ?File $photoFile = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Licencie $licencie = null;

    #[ORM\ManyToMany(targetEntity: Cart::class, inversedBy: 'photos')]
    private Collection $carts;

    #[ORM\ManyToMany(targetEntity: Order::class, inversedBy: 'photos')]
    private Collection $orders;

    

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    //fonction pour le téléchargement de la photo
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }
    //fonction pour le téléchargement de la photo. On intègre la date de Publication et le booléen downloaded
    public function setPhotoFile(?File $photoFile = null): static
    {
        $this->photoFile = $photoFile;

        if (null !== $photoFile) {
            $this->datePublication = new \DateTimeImmutable();
            $this->downloaded = false;
        }

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path ?: 'default.jpg';

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

    public function isDownloaded(): ?bool
    {
        return $this->downloaded;
    }

    public function setDownloaded(bool $downloaded): static
    {
        $this->downloaded = $downloaded;

        return $this;
    }

    public function getLicencie(): ?Licencie
    {
        return $this->licencie;
    }

    public function setLicencie(?Licencie $licencie): static
    {
        $this->licencie = $licencie;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        $this->carts->removeElement($cart);

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
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        $this->orders->removeElement($order);

        return $this;
    }
}
