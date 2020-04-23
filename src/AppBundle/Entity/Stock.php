<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockRepository")
 */
class Stock
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
     * @ORM\Column(name="qteInit", type="integer")
     */
    private $qteInit;

    /**
     * @var int
     *
     * @ORM\Column(name="punit", type="integer")
     */
    private $punit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


    /**
     * @var \Arrivage
     * One Stock has One Arrivage.
     * @ORM\OneToOne(targetEntity="Arrivage", inversedBy="stock",cascade={"persist"})
     * @ORM\JoinColumn(name="arrivage", referencedColumnName="id")
     */
    private $arrivage;


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
     * @return Stock
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

    /**
     * Set punit
     *
     * @param integer $punit
     *
     * @return Stock
     */
    public function setPunit($punit)
    {
        $this->punit = $punit;

        return $this;
    }

    /**
     * Get punit
     *
     * @return integer
     */
    public function getPunit()
    {
        return $this->punit;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Stock
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set arrivage
     *
     * @param \AppBundle\Entity\Arrivage $arrivage
     *
     * @return Stock
     */
    public function setArrivage(\AppBundle\Entity\Arrivage $arrivage = null)
    {
        $this->arrivage = $arrivage;

        return $this;
    }

    /**
     * Get arrivage
     *
     * @return \AppBundle\Entity\Arrivage
     */
    public function getArrivage()
    {
        return $this->arrivage;
    }

    /**
     * Set qteInit
     *
     * @param integer $qteInit
     *
     * @return Stock
     */
    public function setQteInit($qteInit)
    {
        $this->qteInit = $qteInit;

        return $this;
    }

    /**
     * Get qteInit
     *
     * @return integer
     */
    public function getQteInit()
    {
        return $this->qteInit;
    }
}
