<?php
namespace Guapit\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
* Guapit\PublicBundle\Entity\User
*
* @ORM\Table(name="users")
* @ORM\Entity(repositoryClass="Guapit\PublicBundle\Entity\UserRepository")
*/
class User implements UserInterface, \Serializable
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
    * @ORM\Column(type="string", length=512)
    */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $university;


    /**
     * @ORM\Column(type="string", length=128)
     */
    private $speciality;

    /**
     * @ORM\Column(type="integer", length=64)
     */
    private $course;


    /**
     * @ORM\Column(type="string", length=64)
     */
    private $phone;

    /**
     * @ORM\Column(type="text")
     */
    private $about;

    /**
     * @ORM\Column(type="integer")
     */
    private $role_id;


    /**
    * @ORM\Column(name="is_active", type="boolean")
    */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="users")
     * @ORM\JoinTable(name="users_projects")
     **/
    private $projects;

    public function __construct()
    {
    $this->isActive = true;
    $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    // may not be needed, see section on salt below
    // $this->salt = md5(uniqid(null, true));
    }

    /**
    * @inheritDoc
    */
    public function getUsername()
    {
    return $this->username;
    }

    /**
    * @inheritDoc
    */
    public function getSalt()
    {
    // you *may* need a real salt depending on your encoder
    // see section on salt below
    return null;
    }

    /**
    * @inheritDoc
    */
    public function getPassword()
    {
    return $this->password;
    }

    /**
    * @inheritDoc
    */
    public function getRoles()
    {
    return array('ROLE_USER');
    }

    /**
    * @inheritDoc
    */
    public function eraseCredentials()
    {
    }

    /**
    * @see \Serializable::serialize()
    */
    public function serialize()
    {
        return serialize(array(
        $this->id,
        $this->email,
        $this->first_name,
        $this->last_name,
        $this->university,
        $this->speciality,
        $this->course,
        $this->role_id
        // see section on salt below
        // $this->salt,
        ));
    }

    /**
    * @see \Serializable::unserialize()
    */
    public function unserialize($serialized)
    {
        list (
        $this->id,
        $this->email,
        $this->first_name,
        $this->last_name,
        $this->university,
        $this->speciality,
        $this->course,
        $this->role_id
        // see section on salt below
        // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set university
     *
     * @param string $university
     * @return User
     */
    public function setUniversity($university)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university
     *
     * @return string 
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * Set speciality
     *
     * @param string $speciality
     * @return User
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return string 
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set course
     *
     * @param integer $course
     * @return User
     */
    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return integer 
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
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
     * Set role_id
     *
     * @param integer $roleId
     * @return User
     */
    public function setRoleId($roleId)
    {
        $this->role_id = $roleId;

        return $this;
    }

    /**
     * Get role_id
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public static function encodePassword($pass)
    {
        return base64_encode(hash('sha512',$pass));
    }

    /**
     * Add projects
     *
     * @param \Guapit\PublicBundle\Entity\Project $projects
     * @return User
     */
    public function addProject(\Guapit\PublicBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Guapit\PublicBundle\Entity\Project $projects
     */
    public function removeProject(\Guapit\PublicBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }


    /**
     * Set about
     *
     * @param string $about
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
    }
}
