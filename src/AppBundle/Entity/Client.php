<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="nom", type="string", length=255,  nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="raisoc", type="string", length=255,  nullable=true)
     */
    private $raisoc;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255,  nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, unique=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Prix", mappedBy="client")
     */
    private $prices; 

    /**
     * @ORM\OneToMany(targetEntity="Proformat", mappedBy="client")
     */
    private $devis;

    /**
     * @ORM\OneToMany(targetEntity="Suscriber", mappedBy="client")
     */
    private $members;

    /**
    * @ORM\OneToMany(targetEntity="Vente", mappedBy="client")
    **/
    private $ventes;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set raisoc
     *
     * @param string $raisoc
     *
     * @return Client
     */
    public function setRaisoc($raisoc)
    {
        $this->raisoc = $raisoc;

        return $this;
    }

    /**
     * Get raisoc
     *
     * @return string
     */
    public function getRaisoc()
    {
        return $this->raisoc;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Client
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Client
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->devis = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add price
     *
     * @param \AppBundle\Entity\Prix $price
     *
     * @return Client
     */
    public function addPrice(\AppBundle\Entity\Prix $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \AppBundle\Entity\Prix $price
     */
    public function removePrice(\AppBundle\Entity\Prix $price)
    {
        $this->prices->removeElement($price);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Add devi
     *
     * @param \AppBundle\Entity\Proformat $devi
     *
     * @return Client
     */
    public function addDevi(\AppBundle\Entity\Proformat $devi)
    {
        $this->devis[] = $devi;

        return $this;
    }

    /**
     * Remove devi
     *
     * @param \AppBundle\Entity\Proformat $devi
     */
    public function removeDevi(\AppBundle\Entity\Proformat $devi)
    {
        $this->devis->removeElement($devi);
    }

    /**
     * Get devis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDevis()
    {
        return $this->devis;
    }
    public function __toString()
    {
        if( isset($this->nom) || isset($this->prenom)   )
            return $this->nom . "  " . $this->prenom;
        else
            return $this->telephone;
    }

    /**
     * Add member
     *
     * @param \AppBundle\Entity\Suscriber $member
     *
     * @return Client
     */
    public function addMember(\AppBundle\Entity\Suscriber $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \AppBundle\Entity\Suscriber $member
     */
    public function removeMember(\AppBundle\Entity\Suscriber $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    
    public function setVentes($ventes)
    {
        $this->ventes = $ventes;

        return $this;
    }

    public function getVentes()
    {
        return $this->ventes;
    }



    /**
     * Add vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return Client
     */
    public function addVente(\AppBundle\Entity\Vente $vente)
    {
        $this->ventes[] = $vente;

        return $this;
    }

    /**
     * Remove vente
     *
     * @param \AppBundle\Entity\Vente $vente
     */
    public function removeVente(\AppBundle\Entity\Vente $vente)
    {
        $this->ventes->removeElement($vente);
    }
}
