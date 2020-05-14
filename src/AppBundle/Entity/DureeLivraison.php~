<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DureeLivraison
 *
 * @ORM\Table(name="duree_livraison")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DureeLivraisonRepository")
 */
class DureeLivraison
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
    * @ORM\OneToMany(targetEntity="Proformat", mappedBy="dureeLivraison")
    */
    private $proformats;

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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return DureeLivraison
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proformats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add proformat
     *
     * @param \AppBundle\Entity\Proformat $proformat
     *
     * @return DureeLivraison
     */
    public function addProformat(\AppBundle\Entity\Proformat $proformat)
    {
        $this->proformats[] = $proformat;

        return $this;
    }

    /**
     * Remove proformat
     *
     * @param \AppBundle\Entity\Proformat $proformat
     */
    public function removeProformat(\AppBundle\Entity\Proformat $proformat)
    {
        $this->proformats->removeElement($proformat);
    }

    /**
     * Get proformats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProformats()
    {
        return $this->proformats;
    }
}
