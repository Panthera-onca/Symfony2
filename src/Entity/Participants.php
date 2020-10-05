<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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

    public function __construct()
    {
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
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=Participants::class, inversedBy="groupes")
     * @ORM\JoinColumn(nullable=false)
     */

    private $participants;
    /**
     * @Assert\NotBlank(message= "L'email ne peut être vide !")
     * Assert\Length(min="5",
     *     max="180",
     *     minMessage="5 caractères minimum !",
     *     maxMessage="180 caractères maximum !")
     *
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $mail;

    /**
     * @Assert\NotBlank(message= "Le téléphone ne peut être vide !")
     * Assert\Length(min="12",
     *     max="12",
     *     exactMessage="12 caractères requis !")
     *
     * @ORM\Column(type="string", length=12)
     */
    private $telephone;
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

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    /**
     * @return string
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * @return mixed
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
