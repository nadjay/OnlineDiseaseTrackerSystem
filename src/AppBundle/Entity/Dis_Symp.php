<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dis_Symp
 *
 * @ORM\Table(name="dis__symp")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Dis_SympRepository")
 */
class Dis_Symp
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
     * @var \AppBundle\Entity\Disease
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="disease_id", referencedColumnName="id")
     * })
     */
    
    private $diseaseID;

    /**
     * @var \AppBundle\Entity\Symptom
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Symptom")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="symptom_id", referencedColumnName="id")
     * })
     */

    private $symptomID;



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
     * Set diseaseID
     *
     * @param \AppBundle\Entity\Disease $diseaseID
     *
     * @return Dis_Symp
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

    /**
     * Set symptomID
     *
     * @param \AppBundle\Entity\Symptom $symptomID
     *
     * @return Dis_Symp
     */
    public function setSymptomID(\AppBundle\Entity\Symptom $symptomID = null)
    {
        $this->symptomID = $symptomID;

        return $this;
    }

    /**
     * Get symptomID
     *
     * @return \AppBundle\Entity\Symptom
     */
    public function getSymptomID()
    {
        return $this->symptomID;
    }
}
