<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Doc_Dis
 *
 * @ORM\Table(name="doc__dis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Doc_DisRepository")
 */
class Doc_Dis
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
     * @var \AppBundle\Entity\Doctor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Doctor")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="doctor_id", referencedColumnName="id")
     * })
     */

    private $doctorID;

    /**
     * @var \AppBundle\Entity\Disease
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="disease_id", referencedColumnName="id")
     * })
     */

    private $diseaseID;


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
     * Set doctorID
     *
     * @param \AppBundle\Entity\Doctor $doctorID
     *
     * @return Doc_Dis
     */
    public function setDoctorID(\AppBundle\Entity\Doctor $doctorID = null)
    {
        $this->doctorID = $doctorID;

        return $this;
    }

    /**
     * Get doctorID
     *
     * @return \AppBundle\Entity\Doctor
     */
    public function getDoctorID()
    {
        return $this->doctorID;
    }

    /**
     * Set diseaseID
     *
     * @param \AppBundle\Entity\Disease $diseaseID
     *
     * @return Doc_Dis
     */
    public function setDiseaseID(\AppBundle\Entity\Disease $diseaseID = null)
    {
        $this->diseaseID = $diseaseID;

        return $this;
    }

    /**
     * Get diseaseID
     *
     * @return \AppBundle\Entity\Disease
     */
    public function getDiseaseID()
    {
        return $this->diseaseID;
    }
}
