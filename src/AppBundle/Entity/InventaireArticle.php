<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InventaireArticle
 *
 * @ORM\Table(name="inventaire_article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InventaireArticleRepository")
 */
class InventaireArticle
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
     * @ORM\Column(name="qte", type="integer")
     */
    private $qte;

    /**
     * @var int
     *
     * @ORM\Column(name="stockLogiciel", type="integer")
     */
    private $stockLogiciel;
    


    /**
     *
     * @ORM\ManyToOne(targetEntity="Inventaire", inversedBy="inventairesArticles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inventaire_id", referencedColumnName="id")
     * })
     */
    private $inventaire;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="inventairesArticles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     * })
     */
    private $article;


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
     * Set qte
     *
     * @param integer $qte
     *
     * @return InventaireArticle
     */
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * Get qte
     *
     * @return int
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set inventaire
     *
     * @param \AppBundle\Entity\Inventaire $inventaire
     *
     * @return InventaireArticle
     */
    public function setInventaire(\AppBundle\Entity\Inventaire $inventaire = null)
    {
        $this->inventaire = $inventaire;

        return $this;
    }

    /**
     * Get inventaire
     *
     * @return \AppBundle\Entity\Inventaire
     */
    public function getInventaire()
    {
        return $this->inventaire;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return InventaireArticle
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
     * Set stockLogiciel
     *
     * @param integer $stockLogiciel
     *
     * @return InventaireArticle
     */
    public function setStockLogiciel($stockLogiciel)
    {
        $this->stockLogiciel = $stockLogiciel;

        return $this;
    }

    /**
     * Get stockLogiciel
     *
     * @return integer
     */
    public function getStockLogiciel()
    {
        return $this->stockLogiciel;
    }
}
