<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryAdress
 *
 * @ORM\Table(name="delivery_adress")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryAdressRepository")
 */
class DeliveryAdress
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="quartier", type="string", length=255, nullable=true)
     */
    private $quartier;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="Suscriber", inversedBy="deliveryAdresses")
     * @ORM\JoinColumn(name="suscriber_id", referencedColumnName="id", nullable=true)
     */
    private $suscriber;


    /**
    * @ORM\OneToMany(targetEntity="Proformat", mappedBy="deliveryAdress")
    */
    private $proformats;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function copy($deliveryAdress){
        $this->lastname = $deliveryAdress->getLastname();
        $this->firstname = $deliveryAdress->getFirstname();
        $this->email = $deliveryAdress->getEmail();
        $this->address = $deliveryAdress->getAddress();
        $this->phone = $deliveryAdress->getPhone();
        $this->quartier = $deliveryAdress->getQuartier();
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return DeliveryAdress
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return DeliveryAdress
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DeliveryAdress
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return DeliveryAdress
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set quartier
     *
     * @param string $quartier
     *
     * @return DeliveryAdress
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;

        return $this;
    }

    /**
     * Get quartier
     *
     * @return string
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return DeliveryAdress
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set suscriber
     *
     * @param \AppBundle\Entity\Suscriber $suscriber
     *
     * @return DeliveryAdress
     */
    public function setSuscriber(\AppBundle\Entity\Suscriber $suscriber = null)
    {
        $this->suscriber = $suscriber;

        return $this;
    }

    /**
     * Get suscriber
     *
     * @return \AppBundle\Entity\Suscriber
     */
    public function getSuscriber()
    {
        return $this->suscriber;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proformats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add proformat
     *
     * @param \AppBundle\Entity\Proformat $proformat
     *
     * @return DeliveryAdress
     */
    public function addProformat(\AppBundle\Entity\Proformat $proformat)
    {
        $this->proformats[] = $proformat;

        return $this;
    }

    /**
     * Remove proformat
     *
     * @param \AppBundle\Entity\Proformat $proformat
     */
    public function removeProformat(\AppBundle\Entity\Proformat $proformat)
    {
        $this->proformats->removeElement($proformat);
    }

    /**
     * Get proformats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProformats()
    {
        return $this->proformats;
    }
}
