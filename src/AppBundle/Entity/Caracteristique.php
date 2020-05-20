<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Caracteristique
 *
 * @ORM\Table(name="caracteristique")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CaracteristiqueRepository")
 */
class Caracteristique
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
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
    * @ORM\ManyToMany(targetEntity="TypeArticle", mappedBy="caracteristiques")
    */
    private $typeArticles;

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
     * @return Caracteristique
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
        $this->typeArticles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add typeArticle
     *
     * @param \AppBundle\Entity\TypeArticle $typeArticle
     *
     * @return Caracteristique
     */
    public function addTypeArticle(\AppBundle\Entity\TypeArticle $typeArticle)
    {
        $this->typeArticles[] = $typeArticle;

        return $this;
    }

    /**
     * Remove typeArticle
     *
     * @param \AppBundle\Entity\TypeArticle $typeArticle
     */
    public function removeTypeArticle(\AppBundle\Entity\TypeArticle $typeArticle)
    {
        $this->typeArticles->removeElement($typeArticle);
    }

    /**
     * Get typeArticles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypeArticles()
    {
        return $this->typeArticles;
    }
}
