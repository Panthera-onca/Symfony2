<?php


namespace App\Entity\User;

use App\Entity\Site;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User1 implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */

    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */

    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    private $administrateur;

    /**
     * @ORM\Column(type="string", length=50)
     */

    private $campus_no_campus;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userSite;


    public function setCampus_no_campus($campus_no_campus){
        $this->campus_no_campus = $campus_no_campus;
    }

    public function getCampus_no_campus(){
        $this->campus_no_campus;
    }


    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $pseudo
     */
    public function setUsername($pseudo)
    {
        $this->username = $pseudo;
    }

    /**
     * @param mixed $mot_de_passe
     */
    public function setPassword($mot_de_passe)
    {
        $this->password = $mot_de_passe;
    }

    public function setAdministrateur($administrateur){
        $this->administrateur = $administrateur;
    }
    public function getAdministrateur(){
        return $this->administrateur;
    }


    public function getId(): ?int
    {
        return $this->id;
    }  



    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->pseudo;
    }

    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
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
}
