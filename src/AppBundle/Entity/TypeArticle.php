<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type Article
 *
 * @ORM\Table(name="type_article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeArticleRepository")
 */
class TypeArticle
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
     * @ORM\Column(name="designation", type="string", length=255, unique=true)
     */
    private $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, unique=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultPrice", type="string", length=255)
    */
    private $defaultPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="source1", type="string", length=255, unique=false, nullable=true)
     */
    private $source1;

    /**
     * @var string
     *
     * @ORM\Column(name="source2", type="string", length=255, unique=false, nullable=true)
     */
    private $source2;

    /**
     * @var string
     *
     * @ORM\Column(name="source3", type="string", length=255, unique=false, nullable=true)
     */
    private $source3;

    /**
     * @var string
     *
     * @ORM\Column(name="source4", type="string", length=255, unique=false, nullable=true)
     */
    private $source4;

    /**
    * @ORM\OneToMany(targetEntity="Article", mappedBy="typeArticle")
    */
    private $articles;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="typeArticles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Caracteristique", cascade={"persist"})
     */
    private $caracteristiques;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->caracteristiques = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return TypeArticle
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return TypeArticle
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set defaultPrice
     *
     * @param string $defaultPrice
     *
     * @return TypeArticle
     */
    public function setDefaultPrice($defaultPrice)
    {
        $this->defaultPrice = $defaultPrice;

        return $this;
    }

    /**
     * Get defaultPrice
     *
     * @return string
     */
    public function getDefaultPrice()
    {
        return $this->defaultPrice;
    }

    /**
     * Set source1
     *
     * @param string $source1
     *
     * @return TypeArticle
     */
    public function setSource1($source1)
    {
        $this->source1 = $source1;

        return $this;
    }

    /**
     * Get source1
     *
     * @return string
     */
    public function getSource1()
    {
        return $this->source1;
    }

    /**
     * Set source2
     *
     * @param string $source2
     *
     * @return TypeArticle
     */
    public function setSource2($source2)
    {
        $this->source2 = $source2;

        return $this;
    }

    /**
     * Get source2
     *
     * @return string
     */
    public function getSource2()
    {
        return $this->source2;
    }

    /**
     * Set source3
     *
     * @param string $source3
     *
     * @return TypeArticle
     */
    public function setSource3($source3)
    {
        $this->source3 = $source3;

        return $this;
    }

    /**
     * Get source3
     *
     * @return string
     */
    public function getSource3()
    {
        return $this->source3;
    }

    /**
     * Set source4
     *
     * @param string $source4
     *
     * @return TypeArticle
     */
    public function setSource4($source4)
    {
        $this->source4 = $source4;

        return $this;
    }

    /**
     * Get source4
     *
     * @return string
     */
    public function getSource4()
    {
        return $this->source4;
    }

    /**
     * Add article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return TypeArticle
     */
    public function addArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \AppBundle\Entity\Article $article
     */
    public function removeArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return TypeArticle
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add caracteristique
     *
     * @param \AppBundle\Entity\Caracteristique $caracteristique
     *
     * @return TypeArticle
     */
    public function addCaracteristique(\AppBundle\Entity\Caracteristique $caracteristique)
    {
        $this->caracteristiques[] = $caracteristique;

        return $this;
    }

    /**
     * Remove caracteristique
     *
     * @param \AppBundle\Entity\Caracteristique $caracteristique
     */
    public function removeCaracteristique(\AppBundle\Entity\Caracteristique $caracteristique)
    {
        $this->caracteristiques->removeElement($caracteristique);
    }

    /**
     * Get caracteristiques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCaracteristiques()
    {
        return $this->caracteristiques;
    }
}
