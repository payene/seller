<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MotifSortieStock
 *
 * @ORM\Table(name="motif_sortie_stock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MotifSortieStockRepository")
 */
class MotifSortieStock
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="string", length=255, unique=true)
     */
    private $motif;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

     /**
     * @ORM\OneToMany(targetEntity="SortieStock", mappedBy="motifSortie")
     */
    private $sortieCollection;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set motif
     *
     * @param string $motif
     *
     * @return MotifSortieStock
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MotifSortieStock
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function __toString(){
        return $this->motif;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sortieCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sortieCollection
     *
     * @param \AppBundle\Entity\SortieStock $sortieCollection
     *
     * @return MotifSortieStock
     */
    public function addSortieCollection(\AppBundle\Entity\SortieStock $sortieCollection)
    {
        $this->sortieCollection[] = $sortieCollection;

        return $this;
    }

    /**
     * Remove sortieCollection
     *
     * @param \AppBundle\Entity\SortieStock $sortieCollection
     */
    public function removeSortieCollection(\AppBundle\Entity\SortieStock $sortieCollection)
    {
        $this->sortieCollection->removeElement($sortieCollection);
    }

    /**
     * Get sortieCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSortieCollection()
    {
        return $this->sortieCollection;
    }
}
