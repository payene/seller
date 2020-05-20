<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
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
    * @ORM\OneToMany(targetEntity="Prix", mappedBy="article")
    */
    private $prices;

    /**
    * @ORM\OneToMany(targetEntity="Media", mappedBy="article")
    */
    private $medias;

    /**
    * @ORM\OneToMany(targetEntity="Valeur", mappedBy="article", cascade={"remove"})
    */
    private $valeurs;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \TypeArticle
     *
     * @ORM\ManyToOne(targetEntity="TypeArticle", inversedBy="articles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_article", referencedColumnName="id", nullable=true)
     * })
     */
    private $typeArticle;

    /**
     * @ORM\OneToMany(targetEntity="LigneProformat", mappedBy="article")
     */
    private $lpCollection; 

    /**
     * @ORM\OneToMany(targetEntity="Arrivage", mappedBy="article")
     */
    private $arvgCollection;


    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
    * @ORM\OneToMany(targetEntity="LigneVente", mappedBy="article")
    **/
    private $lignes_vente;



    /**
    * @ORM\OneToMany(targetEntity="InventaireArticle", mappedBy="article")
    **/
    private $inventairesArticles;

     /**
    * @ORM\OneToMany(targetEntity="ArticleStock", mappedBy="article")
    **/
    private $articlesStocks;



    



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString(){
        return $this->designation ;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lignes_vente = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * @return Article
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
     * Add price
     *
     * @param \AppBundle\Entity\Prix $price
     *
     * @return Article
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
     * Add media
     *
     * @param \AppBundle\Entity\Media $media
     *
     * @return Article
     */
    public function addMedia(\AppBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \AppBundle\Entity\Media $media
     */
    public function removeMedia(\AppBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Article
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
     * Set stock
     *
     * @param integer $stock
     *
     * @return Article
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add lpCollection
     *
     * @param \AppBundle\Entity\LigneProformat $lpCollection
     *
     * @return Article
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
     * Add arvgCollection
     *
     * @param \AppBundle\Entity\Arrivage $arvgCollection
     *
     * @return Article
     */
    public function addArvgCollection(\AppBundle\Entity\Arrivage $arvgCollection)
    {
        $this->arvgCollection[] = $arvgCollection;

        return $this;
    }

    /**
     * Remove arvgCollection
     *
     * @param \AppBundle\Entity\Arrivage $arvgCollection
     */
    public function removeArvgCollection(\AppBundle\Entity\Arrivage $arvgCollection)
    {
        $this->arvgCollection->removeElement($arvgCollection);
    }

    /**
     * Get arvgCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArvgCollection()
    {
        return $this->arvgCollection;
    }

    /**
     * Add lignesVente
     *
     * @param \AppBundle\Entity\LigneVente $lignesVente
     *
     * @return Article
     */
    public function addLignesVente(\AppBundle\Entity\LigneVente $lignesVente)
    {
        $this->lignes_vente[] = $lignesVente;

        return $this;
    }

    /**
     * Remove lignesVente
     *
     * @param \AppBundle\Entity\LigneVente $lignesVente
     */
    public function removeLignesVente(\AppBundle\Entity\LigneVente $lignesVente)
    {
        $this->lignes_vente->removeElement($lignesVente);
    }

    /**
     * Get lignesVente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignesVente()
    {
        return $this->lignes_vente;
    }

    /**
     * Add inventairesArticle
     *
     * @param \AppBundle\Entity\InventaireArticle $inventairesArticle
     *
     * @return Article
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

    /**
     * Add articlesStock
     *
     * @param \AppBundle\Entity\ArticleStock $articlesStock
     *
     * @return Article
     */
    public function addArticlesStock(\AppBundle\Entity\ArticleStock $articlesStock)
    {
        $this->articlesStocks[] = $articlesStock;

        return $this;
    }

    /**
     * Remove articlesStock
     *
     * @param \AppBundle\Entity\ArticleStock $articlesStock
     */
    public function removeArticlesStock(\AppBundle\Entity\ArticleStock $articlesStock)
    {
        $this->articlesStocks->removeElement($articlesStock);
    }

    /**
     * Get articlesStocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticlesStocks()
    {
        return $this->articlesStocks;
    }

   

    /**
     * Set typeArticle
     *
     * @param \AppBundle\Entity\TypeArticle $typeArticle
     *
     * @return Article
     */
    public function setTypeArticle(\AppBundle\Entity\TypeArticle $typeArticle = null)
    {
        $this->typeArticle = $typeArticle;

        return $this;
    }

    /**
     * Get typeArticle
     *
     * @return \AppBundle\Entity\TypeArticle
     */
    public function getTypeArticle()
    {
        return $this->typeArticle;
    }

    /**
     * Add valeur
     *
     * @param \AppBundle\Entity\Valeur $valeur
     *
     * @return Article
     */
    public function addValeur(\AppBundle\Entity\Valeur $valeur)
    {
        $this->valeurs[] = $valeur;

        return $this;
    }

    /**
     * Remove valeur
     *
     * @param \AppBundle\Entity\Valeur $valeur
     */
    public function removeValeur(\AppBundle\Entity\Valeur $valeur)
    {
        $this->valeurs->removeElement($valeur);
    }

    /**
     * Get valeurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValeurs()
    {
        return $this->valeurs;
    }
}
