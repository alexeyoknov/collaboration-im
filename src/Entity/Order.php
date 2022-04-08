<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $clientName = 'anonymous';

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $phoneNumber = 'anonymous';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email = 'anonymous';

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="OrderRef", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $orderProducts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $status = self::STATUS_CART;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=SonataUserUser::class, inversedBy="orders")
     */
    private ?SonataUserUser $user;

    /**
     * An order that is in progress, not placed yet.
     *
     * @var string
     */
    const STATUS_CART = 'cart';
    
    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
//        if (!$this->orderProducts->contains($orderProduct)) {
//            $this->orderProducts[] = $orderProduct;
//            $orderProduct->setOrderRef($this);
//        }

         foreach ($this->getOrderProducts() as $existingItem) {
             if ($existingItem->equals($orderProduct)) {
                 $existingItem->setQuantity(
                     $existingItem->getQuantity() + $orderProduct->getQuantity()
                 );
                 return $this;
             }
         }
    
         $this->orderProducts[] = $orderProduct;
         $orderProduct->setOrderRef($this);
    
        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getOrderRef() === $this) {
                $orderProduct->setOrderRef(null);
            }
        }

        return $this;
    }

    /**
     * Removes all items from the order.
     *
     * @return $this
     */
    public function removeOrderProducts(): self
    {
        foreach ($this->getOrderProducts() as $item) {
            $this->removeOrderProduct($item);
        }

        return $this;
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getOrderProducts() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): ?SonataUserUser
    {
        return $this->user;
    }

    public function setUser(?SonataUserUser $user): self
    {
        $this->user = $user;
        $this->email = $user->getEmail() ?? 'anonymous';
        $this->clientName = $user->getUsername() ?? 'anonymous';
        $this->phoneNumber = $user->getPhone() ?? 'anonymous';
        
        return $this;
    }

}
