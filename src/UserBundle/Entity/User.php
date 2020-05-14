<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;


/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @var \AppBundle\Entity\Suscriber
    *
    * @ORM\OneToOne(targetEntity="\AppBundle\Entity\Suscriber",  inversedBy="account", cascade={"persist"}, fetch="EAGER")
    * @ORM\JoinColumn(name="suscriber", referencedColumnName="id", nullable=true)
    */
    private $suscriber;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Fournisseur", mappedBy="user")
     */
    private $fournisseurs;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Employe", mappedBy="user")
     */
    private $employes;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Arrivage", mappedBy="createdBy")
     */
    private $arvgCollection;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\MvtStock", mappedBy="createdBy")
     */
    private $mvtsCollection;


    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Vente", mappedBy="createdBy")
     */
    private $ventes;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\SortieStock", mappedBy="createdBy")
     */
    private $sortieCollection;


    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Paiement", mappedBy="createdBy")
     */
    private $paiements;

    


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
     * Set suscriber
     *
     * @param \AppBundle\Entity\Suscriber $suscriber
     *
     * @return User
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
     * Add fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return User
     */
    public function addFournisseur(\AppBundle\Entity\Fournisseur $fournisseur)
    {
        $this->fournisseurs[] = $fournisseur;

        return $this;
    }

    /**
     * Remove fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     */
    public function removeFournisseur(\AppBundle\Entity\Fournisseur $fournisseur)
    {
        $this->fournisseurs->removeElement($fournisseur);
    }

    /**
     * Get fournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFournisseurs()
    {
        return $this->fournisseurs;
    }

    /**
     * Add employe
     *
     * @param \AppBundle\Entity\Client $employe
     *
     * @return User
     */
    public function addEmploye(\AppBundle\Entity\Client $employe)
    {
        $this->employes[] = $employe;

        return $this;
    }

    /**
     * Remove employe
     *
     * @param \AppBundle\Entity\Client $employe
     */
    public function removeEmploye(\AppBundle\Entity\Client $employe)
    {
        $this->employes->removeElement($employe);
    }

    /**
     * Get employes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployes()
    {
        return $this->employes;
    }

    /**
     * Add arvgCollection
     *
     * @param \AppBundle\Entity\Arrivage $arvgCollection
     *
     * @return User
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
     * Add vente
     *
     * @param \AppBundle\Entity\Vente $vente
     *
     * @return User
     */
    public function addVente(\AppBundle\Entity\Vente $vente)
    {
        $this->ventes[] = $vente;

        return $this;
    }

    /**
     * Remove vente
     *
     * @param \AppBundle\Entity\Vente $vente
     */
    public function removeVente(\AppBundle\Entity\Vente $vente)
    {
        $this->ventes->removeElement($vente);
    }

    /**
     * Get ventes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVentes()
    {
        return $this->ventes;
    }

    /**
     * Add paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     *
     * @return User
     */
    public function addPaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements[] = $paiement;

        return $this;
    }

    /**
     * Remove paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     */
    public function removePaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements->removeElement($paiement);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }

    /**
     * Add mvtsCollection
     *
     * @param \AppBundle\Entity\MvtStock $mvtsCollection
     *
     * @return User
     */
    public function addMvtsCollection(\AppBundle\Entity\MvtStock $mvtsCollection)
    {
        $this->mvtsCollection[] = $mvtsCollection;

        return $this;
    }

    /**
     * Remove mvtsCollection
     *
     * @param \AppBundle\Entity\MvtStock $mvtsCollection
     */
    public function removeMvtsCollection(\AppBundle\Entity\MvtStock $mvtsCollection)
    {
        $this->mvtsCollection->removeElement($mvtsCollection);
    }

    /**
     * Get mvtsCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMvtsCollection()
    {
        return $this->mvtsCollection;
    }

    /**
     * Add sortieCollection
     *
     * @param \AppBundle\Entity\SortieStock $sortieCollection
     *
     * @return User
     */
    public function addSortieCollection(\AppBundle\Entity\SortieStock $sortieCollection)
    {
        $this->sortieCollection[] = $sortieCollection;

        return $this;
    }

    /**
     * Remove sortieCollection
     *
     * @param \AppBundle\Entity\SortieStock $sortieCollection
     */
    public function removeSortieCollection(\AppBundle\Entity\SortieStock $sortieCollection)
    {
        $this->sortieCollection->removeElement($sortieCollection);
    }

    /**
     * Get sortieCollection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSortieCollection()
    {
        return $this->sortieCollection;
    }
}
