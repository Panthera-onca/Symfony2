<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Villes
 *
 * @ORM\Table(name="villes")
 * @ORM\Entity
 */
class Ville
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_ville", type="string", length=30, nullable=false)
     */
    private $nomVille;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=10, nullable=false)
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lieux", mappedBy="ville")
     */
    private $lieux ;

    /**
     * @ORM\OneToMany(targetEntity=Lieu::class, mappedBy="villeLieu")
     */
    private $lieus;

    public function __construct()
    {
        $this->lieus = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    /**
     * @param string $nomVille
     */
    public function setNomVille(string $nomVille): void
    {
        $this->nomVille = $nomVille;
    }

    /**
     * @return string
     */
    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $codePostal
     */
    public function setCodePostal(string $codePostal): void
    {
        $this->codePostal = $codePostal;
    }

    /**
     * @return mixed
     */
    public function getLieux()
    {
        return $this->lieux;
    }

    /**
     * @param mixed $lieux
     */
    public function setLieux($lieux): void
    {
        $this->lieux = $lieux;
    }

    public function addLieu(Lieu $lieu): self
    {
        if (!$this->lieus->contains($lieu)) {
            $this->lieus[] = $lieu;
            $lieu->setVilleLieu($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): self
    {
        if ($this->lieus->contains($lieu)) {
            $this->lieus->removeElement($lieu);
            // set the owning side to null (unless already changed)
            if ($lieu->getVilleLieu() === $this) {
                $lieu->setVilleLieu(null);
            }
        }

        return $this;
    }


}
