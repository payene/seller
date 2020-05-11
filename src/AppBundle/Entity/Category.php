<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="catname", type="string", length=255, unique=true)
     */
    private $catname;

    /**
     * @var string
     *
     * @ORM\Column(name="catdesc", type="text", nullable=true)
     */
    private $catdesc;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="TypeArticle", mappedBy="category")
     */
    private $typeArticles;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categories",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", nullable=true , referencedColumnName="id")
     */
    private $parent;

    /**
    * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
    **/
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="icone", type="string", length=255, unique=false, nullable=true)
     */
    private $icone;




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
     * Set catname
     *
     * @param string $catname
     *
     * @return Category
     */
    public function setCatname($catname)
    {
        $this->catname = $catname;

        return $this;
    }

    /**
     * Get catname
     *
     * @return string
     */
    public function getCatname()
    {
        return $this->catname;
    }

    /**
     * Set catdesc
     *
     * @param string $catdesc
     *
     * @return Category
     */
    public function setCatdesc($catdesc)
    {
        $this->catdesc = $catdesc;

        return $this;
    }

    /**
     * Get catdesc
     *
     * @return string
     */
    public function getCatdesc()
    {
        return $this->catdesc;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Category
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

    
    public function __toString(){
        return $this->catname;
    }

    
    

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\AppBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Category
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set icone
     *
     * @param string $icone
     *
     * @return Category
     */
    public function setIcone($icone)
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * Get icone
     *
     * @return string
     */
    public function getIcone()
    {
        return $this->icone;
    }

    /**
     * Add typeArticle
     *
     * @param \AppBundle\Entity\TypeArticle $typeArticle
     *
     * @return Category
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
