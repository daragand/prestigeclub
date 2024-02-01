<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $paymentDate = null;

    

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderStatus $orderStatus = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $users = null;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OptionList::class,cascade: ['remove'])]
    private Collection $optionLists;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Forfait $forfait = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Licencie $licencie = null;

    #[ORM\Column(length: 255)]
    private ?string $uuidOrder = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipFile = null;

    #[ORM\ManyToMany(targetEntity: Photo::class, mappedBy: 'orders')]
    private Collection $photos;

    public function __construct()
    {
        $this->optionLists = new ArrayCollection();
        $this->photos = new ArrayCollection();
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

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

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
            $optionList->setOrders($this);
        }

        return $this;
    }

    public function removeOptionList(OptionList $optionList): static
    {
        if ($this->optionLists->removeElement($optionList)) {
            // set the owning side to null (unless already changed)
            if ($optionList->getOrders() === $this) {
                $optionList->setOrders(null);
            }
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

    public function getLicencie(): ?Licencie
    {
        return $this->licencie;
    }

    public function setLicencie(?Licencie $licencie): static
    {
        $this->licencie = $licencie;

        return $this;
    }

    public function getUuidOrder(): ?string
    {
        return $this->uuidOrder;
    }

    public function setUuidOrder(string $uuidOrder): static
    {
        $this->uuidOrder = $uuidOrder;

        return $this;
    }

    public function getZipFile(): ?string
    {
        return $this->zipFile;
    }

    public function setZipFile(?string $zipFile): static
    {
        $this->zipFile = $zipFile;

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
            $photo->addOrder($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            $photo->removeOrder($this);
        }

        return $this;
    }
}
