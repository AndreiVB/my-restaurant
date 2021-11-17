<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dish", mappedBy="category")
     */

    private $dish;

    public function __construct()
    {
        $this->dish = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDish(): Collection
    {
        return $this->dish;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->dish->contains($dish)) {
            $this->dish[] = $dish;
            $dish->setCategory($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->dish->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getCategory() === $this) {
                $dish->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
