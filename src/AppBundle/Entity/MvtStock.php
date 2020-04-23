<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MvtStock
 *
 * @ORM\Table(name="mvt_stock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MvtStockRepository")
 */
class MvtStock
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
     * @ORM\Column(name="qte", type="integer")
     */
    private $qteMvt;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_arvg", type="integer")
     */
    private $stockArvg;


    /**
     * @var int
     * ** 1 arrivage /2 sortie /3 vente
     * @ORM\Column(name="mvtType", type="integer")
     */
    private $mvtType;

    /**
     * @var \Arrivage
     *
     * @ORM\ManyToOne(targetEntity="Arrivage", inversedBy="mvtsCollection",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="arrivage", referencedColumnName="id", nullable=true)
     * })
     */
    private $arrivage;

    /**
     * @var \SortieStock
     *
     * @ORM\ManyToOne(targetEntity="SortieStock", inversedBy="mvtsCollection",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sortie_stock", referencedColumnName="id", nullable=true)
     * })
     */
    private $sortieStock;


    /**
     * @var \LigneVente
     *
     * @ORM\ManyToOne(targetEntity="LigneVente", inversedBy="mvtsCollection",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ligne_vente", referencedColumnName="id", nullable=true)
     * })
     */
    private $ligneVente;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="mvtsCollection")
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MvtStock
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
     * Set qteMvt
     *
     * @param integer $qteMvt
     *
     * @return MvtStock
     */
    public function setQteMvt($qteMvt)
    {
        $this->qteMvt = $qteMvt;

        return $this;
    }

    /**
     * Get qteMvt
     *
     * @return integer
     */
    public function getQteMvt()
    {
        return $this->qteMvt;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return MvtStock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set mvtType
     *
     * @param integer $mvtType
     *
     * @return MvtStock
     */
    public function setMvtType($mvtType)
    {
        $this->mvtType = $mvtType;

        return $this;
    }

    /**
     * Get mvtType
     *
     * @return integer
     */
    public function getMvtType()
    {
        return $this->mvtType;
    }

    /**
     * Set arrivage
     *
     * @param \AppBundle\Entity\Arrivage $arrivage
     *
     * @return MvtStock
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
     * Set sortieStock
     *
     * @param \AppBundle\Entity\SortieStock $sortieStock
     *
     * @return MvtStock
     */
    public function setSortieStock(\AppBundle\Entity\SortieStock $sortieStock = null)
    {
        $this->sortieStock = $sortieStock;

        return $this;
    }

    /**
     * Get sortieStock
     *
     * @return \AppBundle\Entity\SortieStock
     */
    public function getSortieStock()
    {
        return $this->sortieStock;
    }

    /**
     * Set ligneVente
     *
     * @param \AppBundle\Entity\LigneVente $ligneVente
     *
     * @return MvtStock
     */
    public function setLigneVente(\AppBundle\Entity\LigneVente $ligneVente = null)
    {
        $this->ligneVente = $ligneVente;

        return $this;
    }

    /**
     * Get ligneVente
     *
     * @return \AppBundle\Entity\LigneVente
     */
    public function getLigneVente()
    {
        return $this->ligneVente;
    }

    /**
     * Set createdBy
     *
     * @param \UserBundle\Entity\User $createdBy
     *
     * @return MvtStock
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

    /**
     * Set stockArvg
     *
     * @param integer $stockArvg
     *
     * @return MvtStock
     */
    public function setStockArvg($stockArvg)
    {
        $this->stockArvg = $stockArvg;

        return $this;
    }

    /**
     * Get stockArvg
     *
     * @return integer
     */
    public function getStockArvg()
    {
        return $this->stockArvg;
    }
}
