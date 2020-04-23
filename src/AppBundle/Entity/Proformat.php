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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="devis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;

     /**
     * @ORM\OneToMany(targetEntity="LigneProformat", mappedBy="proformat")
     */
    private $lpCollection;



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
}
