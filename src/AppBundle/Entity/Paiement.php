<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaiementRepository")
 */
class Paiement
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
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="resteAPayer", type="integer")
     */
    private $resteAPayer;

     /**
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="paiements")
     */
    private $createdBy;

    /**
     * @var string
     *
     * @ORM\Column(name="mode", type="string", length=1)
     */
    private $mode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


     /**
     *
     * @ORM\ManyToOne(targetEntity="Vente", inversedBy="paiements")
     */
    private $vente;


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
     * Set montant
     *
     * @param integer $montant
     *
     * @return Paiement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Paiement
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set mode
     *
     * @param string $mode
     *
     * @return Paiement
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Paiement
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
     * Set vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return Paiement
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
     * Set resteAPayer
     *
     * @param integer $resteAPayer
     *
     * @return Paiement
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
}
