<?php
namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Participants;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"save", "saveWithLieu"})
     */
    private $titre;
    /**
     *
     * @ORM\Column(type="time")
     */

    private $duree;
    /**
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, cascade={"persist"})
     * @Assert\NotBlank(groups={"save"})
     */
    private $lieu;


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
     * @var boolean
     *
     * @Assert\Type(type="boolean", message="This value is not valid!")
     *
     * @ORM\Column(type="boolean")
     */

    private $isPublished;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateSortie;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participants", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $siteOrg;


    public function __construct(){
        $this->sortie = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->name = $titre;
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
     * @return Lieu|null
     */
    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    /**
     * @param Lieu|null $lieu
     * @return Sortie
     */
    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
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
    /**
     * @return mixed $etat
     */
    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @param $etat
     * 
     */
    public function getEtat($etat): ?string
    {
        return $this->$etat;
    }
    
    

    public function setSiteOrg($getUserSite)
    {
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }
    /**
     * @return mixed $dateSortie
     */
    public function setDateSortie(?\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * @return \App\Entity\Participants|null
     */
    public function getOrganisateur(): ?Participants{
        return $this->organisateur;
    }

    /**
     * @param \App\Entity\Participants|null $organisateur
     * @return mixed $organisateur
     */
    public function setOrganisateur(?Participants $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSiteOrg(): ?Site
    {
        return $this->siteOrg;
    }

}