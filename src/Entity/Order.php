<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{

    const STATUS = ['Finalizado', 'En Proceso', 'En proceso de despacho', 'Despachado'];
    const PAYMENT_METHOD = ['Efectivo'=>'Efectivo', 'Datafono'=>'Datafono', 'Pago en linea'=>'Pago en linea'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $realizationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalValue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dispatch_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $product_discount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_delivery;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ProductOrder::class, mappedBy="orderr")
     */
    private $productOrders;

    public function __construct($user)
    {
        $this->user = $user;
        $this->status = self::STATUS[1];
        $this->productOrders = new ArrayCollection();
        $this->discount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealizationDate(): ?\DateTimeInterface
    {
        return $this->realizationDate;
    }

    public function setRealizationDate(\DateTimeInterface $realizationDate): self
    {
        $this->realizationDate = $realizationDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTotalValue(): ?int
    {
        return $this->totalValue;
    }

    public function setTotalValue(int $totalValue): self
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ProductOrder[]
     */
    public function getProductOrders(): Collection
    {
        return $this->productOrders;
    }

    public function addProductOrder(ProductOrder $productOrder): self
    {
        if (!$this->productOrders->contains($productOrder)) {
            $this->productOrders[] = $productOrder;
            $productOrder->setOrderr($this);
        }

        return $this;
    }

    public function removeProductOrder(ProductOrder $productOrder): self
    {
        if ($this->productOrders->contains($productOrder)) {
            $this->productOrders->removeElement($productOrder);
            // set the owning side to null (unless already changed)
            if ($productOrder->getOrderr() === $this) {
                $productOrder->setOrderr(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDispatchDate()
    {
        return $this->dispatch_date;
    }

    /**
     * @param mixed $dispatch_date
     */
    public function setDispatchDate($dispatch_date): void
    {
        $this->dispatch_date = $dispatch_date;
    }

    /**
     * @return mixed
     */
    public function getProductDiscount()
    {
        return $this->product_discount;
    }

    /**
     * @param mixed $product_discount
     */
    public function setProductDiscount($product_discount): void
    {
        $this->product_discount = $product_discount;
    }

    /**
     * @return mixed
     */
    public function getAddressDelivery()
    {
        return $this->address_delivery;
    }

    /**
     * @param mixed $address_delivery
     */
    public function setAddressDelivery($address_delivery): void
    {
        $this->address_delivery = $address_delivery;
    }
    
}
