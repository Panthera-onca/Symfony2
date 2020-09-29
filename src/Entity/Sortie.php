<?php
namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"tmdbId"})
 * @ORM\Entity
 * @ORM\Table(name = "serie")
 */
/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Assert\NotBlank(message="Please, provide a message")
     * @ORM\Column(type="string", length=255)
     */

    private $name;
    /**
     *
     * @ORM\Column(type="time")
     */

    private $duree;
    /**
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $status;


    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateHeureDebut;
    /**
     * @ORM\Column(type="date", nullable=true)
     */

    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $infosSortie;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $nbInscriptionsMax;

    /**
     * @var boolean
     *
     * @Assert\Type(type="boolean", message="This value is not valid!")
     *
     * @ORM\Column(type="boolean")
     */

    private $isPublished;


    public function __construct(){
        $this->sortie = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param mixed $duree
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    }



    /**
     * @return mixed
     */
    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param mixed $dateHeureDebut
     */
    public function setDateHeureDebut($dateHeureDebut)
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }

    /**
     * @return mixed
     */
    public function getDateLimiteInscription()
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param mixed $dateLimiteInscription
     */
    public function setDateLimiteInscription($dateLimiteInscription)
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    /**
     * @return mixed
     */
    public function getInfosSortie()
    {
        return $this->infosSortie;
    }

    /**
     * @param mixed $infosSortie
     */
    public function setInfosSortie($infosSortie)
    {
        $this->infosSortie = $infosSortie;
    }



    /**
     * @return mixed
     */
    public function getNbInscriptionsMax()
    {
        return $this->nbInscriptionsMax;
    }

    /**
     * @param mixed $nbInscriptionsMax
     */
    public function setNbInscriptionsMax($nbInscriptionsMax)
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     */

    public function setIsPublished(bool $isPublished)
    {

        $this->isPublished = $isPublished;
    }


}