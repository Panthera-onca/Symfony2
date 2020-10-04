<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ParticipantsRepository::class)
 * @UniqueEntity(
 *     fields= {"mail"},
 *     message= "Le mail que vous avez indiqué est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields= {"username"},
 *     message= "Ce pseudo est déjà utilisé !"
 *     )
 */
class Participants implements UserInterface
{


    private $salt;

    public function __construct($id, $password, $salt, array $roles)
    {
        $this->id = $id;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
        $this->participants = new ArrayCollection();
    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=30, nullable=false)
     */
    private $pseudo;

    /**
     * @ORM\ManyToOne(targetEntity=Participants::class, inversedBy="groupes")
     * @ORM\JoinColumn(nullable=false)
     */

    private $participants;
    /**
     * @ORM\Column (type="binary")
     */
    private $administrateur;
    private $userSite;
    private $roles;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return string
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }


    /**
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        return $this->getPassword();
    }

    public function setPassword($string)
    {
        $this->setPassword($string);
    }


    /**
     * @return Collection|Participants[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participants $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Participants $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }

    public function setDateCreated(\DateTime $param)
    {
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUserSite(): ?Site
    {
        return $this->userSite;
    }

    public function setUserSite(?Site $userSite): self
    {
        $this->userSite = $userSite;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    /**
     * @return mixed
     */
    public function getAdministrateur()
    {
        return $this->administrateur;
    }

    /**
     * @param mixed $administratif
     */
    public function setAdministrateur($administrateur): void
    {
        $this->administratif = $administrateur;
    }

}
