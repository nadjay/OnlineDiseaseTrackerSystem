<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Doc_Hos
 *
 * @ORM\Table(name="doc__hos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Doc_HosRepository")
 */
class Doc_Hos
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
     * @var \AppBundle\Entity\Hospital
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Hospital")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="hospital_id", referencedColumnName="id")
     * })
     */

    private $hospitalID;


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
     * @return Doc_Hos
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
     * Set hospitalID
     *
     * @param \AppBundle\Entity\Hospital $hospitalID
     *
     * @return Doc_Hos
     */
    public function setHospitalID(\AppBundle\Entity\Hospital $hospitalID = null)
    {
        $this->hospitalID = $hospitalID;

        return $this;
    }

    /**
     * Get hospitalID
     *
     * @return \AppBundle\Entity\Hospital
     */
    public function getHospitalID()
    {
        return $this->hospitalID;
    }
}
