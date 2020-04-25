<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventaire
 *
 * @ORM\Table(name="inventaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InventaireRepository")
 */
class Inventaire
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
     * @ORM\Column(name="dateDeb", type="datetime")
     */
    private $dateDeb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $dateFin;
    
    /**
    * @ORM\OneToMany(targetEntity="InventaireArticle", mappedBy="inventaire")
    **/
    private $inventairesArticles;


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
     * Set dateDeb
     *
     * @param \DateTime $dateDeb
     *
     * @return Inventaire
     */
    public function setDateDeb($dateDeb)
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    /**
     * Get dateDeb
     *
     * @return \DateTime
     */
    public function getDateDeb()
    {
        return $this->dateDeb;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Inventaire
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inventairesArticles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add inventairesArticle
     *
     * @param \AppBundle\Entity\InventaireArticle $inventairesArticle
     *
     * @return Inventaire
     */
    public function addInventairesArticle(\AppBundle\Entity\InventaireArticle $inventairesArticle)
    {
        $this->inventairesArticles[] = $inventairesArticle;

        return $this;
    }

    /**
     * Remove inventairesArticle
     *
     * @param \AppBundle\Entity\InventaireArticle $inventairesArticle
     */
    public function removeInventairesArticle(\AppBundle\Entity\InventaireArticle $inventairesArticle)
    {
        $this->inventairesArticles->removeElement($inventairesArticle);
    }

    /**
     * Get inventairesArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInventairesArticles()
    {
        return $this->inventairesArticles;
    }
}
