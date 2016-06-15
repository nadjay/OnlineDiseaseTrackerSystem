<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Disease
 *
 * @ORM\Table(name="disease")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DiseaseRepository")
 * @UniqueEntity(fields="name", message="A disease with this name already in the database")
 */
class Disease
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=true)
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="min_age", type="integer", nullable=true)
     */
    private $minAge;

    /**
     * @var int
     *
     * @ORM\Column(name="max_age", type="integer", nullable=true)
     */
    private $maxAge;

    /**
     * @var string
     *
     * @ORM\Column(name="severity", type="string", length=10, nullable=true)
     */
    private $severity;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Disease
     */

    public function setID($id){
        $this->id = $id;
        return $this;
    }
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
     * Set name
     *
     * @param string $name
     *
     * @return Disease
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Disease
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set minAge
     *
     * @param integer $minAge
     *
     * @return Disease
     */
    public function setMinAge($minAge)
    {
        $this->minAge = $minAge;

        return $this;
    }

    /**
     * Get minAge
     *
     * @return int
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * Set maxAge
     *
     * @param integer $maxAge
     *
     * @return Disease
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    /**
     * Get maxAge
     *
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * Set severity
     *
     * @param string $severity
     *
     * @return Disease
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * Get severity
     *
     * @return string
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Disease
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
