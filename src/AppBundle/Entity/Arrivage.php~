<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Arrivage
 *
 * @ORM\Table(name="arrivage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArrivageRepository")
 */
class Arrivage
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
     * @var int
     *
     * @ORM\Column(name="qte", type="integer")
     */
    private $qte;

    /**
     * @var string
     *
     * @ORM\Column(name="prixAchat", type="integer")
     */
    private $prixAchat;

    /**
     * @var int
     *
     * @ORM\Column(name="tva", type="integer")
     */
    private $tva;

    /**
     * @var int
     *
     * @ORM\Column(name="taxes", type="integer")
     */
    private $taxes;

    /**
     * @var int
     *
     * @ORM\Column(name="marge", type="integer")
     */
    private $marge;

    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="arvgCollection",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * One Arrivage has One Stock.
     * @ORM\OneToOne(targetEntity="Stock", mappedBy="arrivage")
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity="SortieStock", mappedBy="arrivage")
     */
    private $sortieCollection;
   
    /**
     * @ORM\OneToMany(targetEntity="MvtStock", mappedBy="arrivage")
     */
    private $mvtsCollection;
   
    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="arvgCollection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $createdBy;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;



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
        $this->sortieCollection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mvtsCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set qte
     *
     * @param integer $qte
     *
     * @return Arrivage
     */
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * Get qte
     *
     * @return integer
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set prixAchat
     *
     * @param integer $prixAchat
     *
     * @return Arrivage
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return integer
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set tva
     *
     * @param integer $tva
     *
     * @return Arrivage
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return integer
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set taxes
     *
     * @param integer $taxes
     *
     * @return Arrivage
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;

        return $this;
    }

    /**
     * Get taxes
     *
     * @return integer
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * Set marge
     *
     * @param integer $marge
     *
     * @return Arrivage
     */
    public function setMarge($marge)
    {
        $this->marge = $marge;

        return $this;
    }

    /**
     * Get marge
     *
     * @return integer
     */
    public function getMarge()
    {
        return $this->marge;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Arrivage
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Arrivage
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Arrivage
     */
    public function setArticle(\AppBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set stock
     *
     * @param \AppBundle\Entity\Stock $stock
     *
     * @return Arrivage
     */
    public function setStock(\AppBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return \AppBundle\Entity\Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add sortieCollection
     *
     * @param \AppBundle\Entity\SortieStock $sortieCollection
     *
     * @return Arrivage
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

    /**
     * Add mvtsCollection
     *
     * @param \AppBundle\Entity\MvtStock $mvtsCollection
     *
     * @return Arrivage
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
     * @return Arrivage
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
