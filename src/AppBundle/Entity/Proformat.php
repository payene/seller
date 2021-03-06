<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proformat
 *
 * @ORM\Table(name="proformat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProformatRepository")
 */
class Proformat
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
     * @ORM\Column(name="dateProformat", type="datetime", unique=true)
     */
    private $dateProformat;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="devis", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var bool
     *
     * @ORM\Column(name="payer", type="boolean")
     */
    private $payer = false;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="livrer", type="boolean")
     */
    private $livrer = false;

     /**
     * @ORM\OneToMany(targetEntity="LigneProformat", mappedBy="proformat")
     */
    private $lpCollection;

    /**
     * @var \DeliveryAdress
     *
     * @ORM\ManyToOne(targetEntity="DeliveryAdress")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_adress", referencedColumnName="id", nullable=true)
     * })
     */
    private $deliveryAdress;

    /**
     * @var bool
     *
     * @ORM\Column(name="livraison_domicile", type="boolean")
     */
    private $livraisonDomicile = false;

    /**
     * @ORM\ManyToOne(targetEntity="DureeLivraison", inversedBy="proformats", fetch="EAGER")
     * @ORM\JoinColumn(name="duree_livraison_id", referencedColumnName="id", nullable=true)
     */
    private $dureeLivraison;


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
     * Set dateProformat
     *
     * @param \DateTime $dateProformat
     *
     * @return Proformat
     */
    public function setDateProformat($dateProformat)
    {
        $this->dateProformat = $dateProformat;

        return $this;
    }

    /**
     * Get dateProformat
     *
     * @return \DateTime
     */
    public function getDateProformat()
    {
        return $this->dateProformat;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lpCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Proformat
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
     * Add lpCollection
     *
     * @param \AppBundle\Entity\LigneProformat $lpCollection
     *
     * @return Proformat
     */
    public function addLpCollection(\AppBundle\Entity\LigneProformat $lpCollection)
    {
        $this->lpCollection[] = $lpCollection;

        return $this;
    }

    /**
     * Remove lpCollection
     *
     * @param \AppBundle\Entity\LigneProformat $lpCollection
     */
    public function removeLpCollection(\AppBundle\Entity\LigneProformat $lpCollection)
    {
        $this->lpCollection->removeElement($lpCollection);
    }

    /**
     * Get lpCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLpCollection()
    {
        return $this->lpCollection;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Proformat
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set deliveryAdress
     *
     * @param \AppBundle\Entity\DeliveryAdress $deliveryAdress
     *
     * @return Proformat
     */
    public function setDeliveryAdress(\AppBundle\Entity\DeliveryAdress $deliveryAdress = null)
    {
        $this->deliveryAdress = $deliveryAdress;

        return $this;
    }

    /**
     * Get deliveryAdress
     *
     * @return \AppBundle\Entity\DeliveryAdress
     */
    public function getDeliveryAdress()
    {
        return $this->deliveryAdress;
    }

    /**
     * Set payer
     *
     * @param boolean $payer
     *
     * @return Proformat
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return boolean
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Set livrer
     *
     * @param boolean $livrer
     *
     * @return Proformat
     */
    public function setLivrer($livrer)
    {
        $this->livrer = $livrer;

        return $this;
    }

    /**
     * Get livrer
     *
     * @return boolean
     */
    public function getLivrer()
    {
        return $this->livrer;
    }

    /**
     * Set livraisonDomicile
     *
     * @param boolean $livraisonDomicile
     *
     * @return Proformat
     */
    public function setLivraisonDomicile($livraisonDomicile)
    {
        $this->livraisonDomicile = $livraisonDomicile;

        return $this;
    }

    /**
     * Get livraisonDomicile
     *
     * @return boolean
     */
    public function getLivraisonDomicile()
    {
        return $this->livraisonDomicile;
    }

    /**
     * Set dureeLivraison
     *
     * @param \AppBundle\Entity\DureeLivraison $dureeLivraison
     *
     * @return Proformat
     */
    public function setDureeLivraison(\AppBundle\Entity\DureeLivraison $dureeLivraison = null)
    {
        $this->dureeLivraison = $dureeLivraison;

        return $this;
    }

    /**
     * Get dureeLivraison
     *
     * @return \AppBundle\Entity\DureeLivraison
     */
    public function getDureeLivraison()
    {
        return $this->dureeLivraison;
    }
}
