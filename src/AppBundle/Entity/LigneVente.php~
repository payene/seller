<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneVente
 *
 * @ORM\Table(name="ligne_vente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigneVenteRepository")
 */
class LigneVente
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
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="qte", type="integer")
     */
    private $qte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
    * @ORM\ManyToOne(targetEntity="Article", inversedBy="lignes_vente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article", referencedColumnName="id")
     * })
    **/
    private $article;

    /**
    * @ORM\ManyToOne(targetEntity="Vente", inversedBy="lignes_vente")
    **/
    private $vente;

     /**
     * @ORM\OneToMany(targetEntity="MvtStock", mappedBy="ligneVente")
     */
    private $mvtsCollection;

 //   public $client;

    
     /**
     * @var int
     *
     * @ORM\Column(name="del", type="integer")
     */
     private $del = 0;


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
     * Set montant
     *
     * @param integer $montant
     *
     * @return LigneVente
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set qte
     *
     * @param integer $qte
     *
     * @return LigneVente
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LigneVente
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
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return LigneVente
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
     * Set vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return LigneVente
     */
    public function setVente(\AppBundle\Entity\Vente $vente = null)
    {
        $this->vente = $vente;

        return $this;
    }

    /**
     * Get vente
     *
     * @return \AppBundle\Entity\Vente
     */
    public function getVente()
    {
        return $this->vente;
    }

    /**
     * Add mvtsCollection
     *
     * @param \AppBundle\Entity\MvtStock $mvtsCollection
     *
     * @return LigneVente
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
     * Set del
     *
     * @param integer $del
     *
     * @return LigneVente
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del
     *
     * @return integer
     */
    public function getDel()
    {
        return $this->del;
    }
}
