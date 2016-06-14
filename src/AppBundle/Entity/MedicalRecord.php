<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MedicalRecord
 *
 * @ORM\Table(name="medical_record")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MedicalRecordRepository")
 */
class MedicalRecord
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
     * @var \AppBundle\Entity\Patient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     * })
     */
    private $patientID;

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
     * Set patientID
     *
     * @param \AppBundle\Entity\Patient $patientID
     *
     * @return MedicalRecord
     */
    public function setPatientID(\AppBundle\Entity\Patient $patientID = null)
    {
        $this->patientID = $patientID;

        return $this;
    }

    /**
     * Get patientID
     *
     * @return \AppBundle\Entity\Patient
     */
    public function getPatientID()
    {
        return $this->patientID;
    }

    /**
     * Set diseaseID
     *
     * @param \AppBundle\Entity\Disease $diseaseID
     *
     * @return MedicalRecord
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
