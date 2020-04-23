<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SortieStock
 *
 * @ORM\Table(name="sortie_stock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SortieStockRepository")
 */
class SortieStock
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="qteSortie", type="integer")
     */
    private $qteSortie;

    
    /**
     * @var \MotifSortieStock
     *
     * @ORM\ManyToOne(targetEntity="MotifSortieStock", inversedBy="sortieCollection",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="motif_sortie_stock", referencedColumnName="id", nullable=true)
     * })
     */
    private $motifSortie;  
    

    /**
     * @var \Arrivage
     *
     * @ORM\ManyToOne(targetEntity="Arrivage", inversedBy="sortieCollection",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="arrivage", referencedColumnName="id", nullable=true)
     * })
     */
    private $arrivage;


    /**
     * @ORM\OneToMany(targetEntity="MvtStock", mappedBy="sortieStock")
     */
    private $mvtsCollection;

     /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="sortieCollection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $createdBy;
    

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
     * Constructor
     */
    public function __construct()
    {
        $this->mvtsCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SortieStock
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

    /**
     * Set qteSortie
     *
     * @param integer $qteSortie
     *
     * @return SortieStock
     */
    public function setQteSortie($qteSortie)
    {
        $this->qteSortie = $qteSortie;

        return $this;
    }

    /**
     * Get qteSortie
     *
     * @return integer
     */
    public function getQteSortie()
    {
        return $this->qteSortie;
    }

    /**
     * Set motifSortie
     *
     * @param \AppBundle\Entity\MotifSortieStock $motifSortie
     *
     * @return SortieStock
     */
    public function setMotifSortie(\AppBundle\Entity\MotifSortieStock $motifSortie = null)
    {
        $this->motifSortie = $motifSortie;

        return $this;
    }

    /**
     * Get motifSortie
     *
     * @return \AppBundle\Entity\MotifSortieStock
     */
    public function getMotifSortie()
    {
        return $this->motifSortie;
    }

    /**
     * Set arrivage
     *
     * @param \AppBundle\Entity\Arrivage $arrivage
     *
     * @return SortieStock
     */
    public function setArrivage(\AppBundle\Entity\Arrivage $arrivage = null)
    {
        $this->arrivage = $arrivage;

        return $this;
    }

    /**
     * Get arrivage
     *
     * @return \AppBundle\Entity\Arrivage
     */
    public function getArrivage()
    {
        return $this->arrivage;
    }

    /**
     * Add mvtsCollection
     *
     * @param \AppBundle\Entity\MvtStock $mvtsCollection
     *
     * @return SortieStock
     */
    public function addMvtsCollection(\AppBundle\Entity\MvtStock $mvtsCollection)
    {
        $this->mvtsCollection[] = $mvtsCollection;

        return $this;
    }

    /**
     * Remove mvtsCollection
     *
     * @param \AppBundle\Entity\MvtStock $mvtsCollection
     */
    public function removeMvtsCollection(\AppBundle\Entity\MvtStock $mvtsCollection)
    {
        $this->mvtsCollection->removeElement($mvtsCollection);
    }

    /**
     * Get mvtsCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMvtsCollection()
    {
        return $this->mvtsCollection;
    }

    /**
     * Set createdBy
     *
     * @param \UserBundle\Entity\User $createdBy
     *
     * @return SortieStock
     */
    public function setCreatedBy(\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
