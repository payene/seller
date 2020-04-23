<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneProformat
 *
 * @ORM\Table(name="ligne_proformat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigneProformatRepository")
 */
class LigneProformat
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
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

     /**
     * @var int
     *
     * @ORM\Column(name="qte", type="integer")
     */
    private $qte;


    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="lpCollection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * @var \Proformat
     *
     * @ORM\ManyToOne(targetEntity="Proformat", inversedBy="lpCollection",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proformat", referencedColumnName="id")
     * })
     */
    private $proformat;



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
     * Set prix
     *
     * @param float $prix
     *
     * @return LigneProformat
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return LigneProformat
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
     * Set proformat
     *
     * @param \AppBundle\Entity\Proformat $proformat
     *
     * @return LigneProformat
     */
    public function setProformat(\AppBundle\Entity\Proformat $proformat = null)
    {
        $this->proformat = $proformat;

        return $this;
    }

    /**
     * Get proformat
     *
     * @return \AppBundle\Entity\Proformat
     */
    public function getProformat()
    {
        return $this->proformat;
    }

    /**
     * Set qte
     *
     * @param integer $qte
     *
     * @return LigneProformat
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
}
