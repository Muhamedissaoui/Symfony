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
    private $catname;

    /**
     * @ORM\OneToMany(targetEntity=Things::class, mappedBy="category", orphanRemoval=true)
     */
    private $things;

    public function __construct()
    {
        $this->things = new ArrayCollection();
    }
    public function __toString() 
   {
    return (string) $this->catname; 
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatname(): ?string
    {
        return $this->catname;
    }

    public function setCatname(string $catname): self
    {
        $this->catname = $catname;

        return $this;
    }

    /**
     * @return Collection|Things[]
     */
    public function getThings(): Collection
    {
        return $this->Things;
    }

    public function addThing(Things $Things): self
    {
        if (!$this->Things->contains($Things)) {
            $this->Things[] = $Things;
            $Things->setCategory($this);
        }

        return $this;
    }

    public function removeThing(Things $thing): self
    {
        if ($this->Things->removeElement($thing)) {
            // set the owning side to null (unless already changed)
            if ($thing->getCategory() === $this) {
                $thing->setCategory(null);
            }
        }

        return $this;
    }
}
