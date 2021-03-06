<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Valeur
 *
 * @ORM\Table(name="valeur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ValeurRepository")
 */
class Valeur
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
     * @ORM\Column(name="valeurCaracteristique", type="string", length=255)
     */
    private $valeurCaracteristique;

    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="valeurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * @var \Caracteristique
     *
     * @ORM\ManyToOne(targetEntity="Caracteristique", inversedBy="valeurs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="caracteristique", referencedColumnName="id")
     * })
     */
    private $caracteristique;

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
     * Set valeurCaracteristique
     *
     * @param string $valeurCaracteristique
     *
     * @return Valeur
     */
    public function setValeurCaracteristique($valeurCaracteristique)
    {
        $this->valeurCaracteristique = $valeurCaracteristique;

        return $this;
    }

    /**
     * Get valeurCaracteristique
     *
     * @return string
     */
    public function getValeurCaracteristique()
    {
        return $this->valeurCaracteristique;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Valeur
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
     * Set caracteristique
     *
     * @param \AppBundle\Entity\Caracteristique $caracteristique
     *
     * @return Valeur
     */
    public function setCaracteristique(\AppBundle\Entity\Caracteristique $caracteristique = null)
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    /**
     * Get caracteristique
     *
     * @return \AppBundle\Entity\Caracteristique
     */
    public function getCaracteristique()
    {
        return $this->caracteristique;
    }
}
