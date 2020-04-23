<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneVente
 *
 * @ORM\Table(name="vente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VenteRepository")
 */
class Vente
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
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="ventes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="ventes",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", nullable=true , referencedColumnName="id")
     */
    private $client;


      /**
     * @var int
     *
     * @ORM\Column(name="tva", type="integer")
     */
    private $tva;

      /**
     * @var int
     *
     * @ORM\Column(name="remise", type="integer")
     */
     private $remise;

    /**
     *
     * @ORM\Column(name="total_ht", type="integer")
     */
     private $total_ht;
    
    /**
     *
     * @ORM\Column(name="reste_a_payer", type="integer")
     */
     private $resteAPayer;
    
    /**
     *
     * @ORM\Column(name="credit", type="integer")
     */
     private $credit;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


    /**
    * @ORM\OneToMany(targetEntity="LigneVente", mappedBy="vente")
    **/
    private $lignes_vente;

    /**
    * @ORM\OneToMany(targetEntity="Paiement", mappedBy="vente")
    **/
    private $paiements;





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
        $this->lignes_vente = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paiements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tva
     *
     * @param integer $tva
     *
     * @return Vente
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
     * Set remise
     *
     * @param integer $remise
     *
     * @return Vente
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return integer
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set totalHt
     *
     * @param integer $totalHt
     *
     * @return Vente
     */
    public function setTotalHt($totalHt)
    {
        $this->total_ht = $totalHt;

        return $this;
    }

    /**
     * Get totalHt
     *
     * @return integer
     */
    public function getTotalHt()
    {
        return $this->total_ht;
    }

    /**
     * Set resteAPayer
     *
     * @param integer $resteAPayer
     *
     * @return Vente
     */
    public function setResteAPayer($resteAPayer)
    {
        $this->resteAPayer = $resteAPayer;

        return $this;
    }

    /**
     * Get resteAPayer
     *
     * @return integer
     */
    public function getResteAPayer()
    {
        return $this->resteAPayer;
    }

    /**
     * Set credit
     *
     * @param integer $credit
     *
     * @return Vente
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Vente
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
     * Set createdBy
     *
     * @param \UserBundle\Entity\User $createdBy
     *
     * @return Vente
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Vente
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add lignesVente
     *
     * @param \AppBundle\Entity\LigneVente $lignesVente
     *
     * @return Vente
     */
    public function addLignesVente(\AppBundle\Entity\LigneVente $lignesVente)
    {
        $this->lignes_vente[] = $lignesVente;

        return $this;
    }

    /**
     * Remove lignesVente
     *
     * @param \AppBundle\Entity\LigneVente $lignesVente
     */
    public function removeLignesVente(\AppBundle\Entity\LigneVente $lignesVente)
    {
        $this->lignes_vente->removeElement($lignesVente);
    }

    /**
     * Get lignesVente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignesVente()
    {
        return $this->lignes_vente;
    }

    /**
     * Add paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     *
     * @return Vente
     */
    public function addPaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements[] = $paiement;

        return $this;
    }

    /**
     * Remove paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     */
    public function removePaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements->removeElement($paiement);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }
}
