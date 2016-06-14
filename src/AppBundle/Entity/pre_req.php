<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * pre_req
 *
 * @ORM\Table(name="pre_req")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\pre_reqRepository")
 */
class pre_req
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
    private $diseaseId;

    /**
     * @var \AppBundle\Entity\Disease
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disease")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="prereq_id", referencedColumnName="id")
     * })
     */
    private $preReqId;


    

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
     * Set diseaseId
     *
     * @param \AppBundle\Entity\Disease $diseaseId
     *
     * @return pre_req
     */
    public function setDiseaseId(\AppBundle\Entity\Disease $diseaseId = null)
    {
        $this->diseaseId = $diseaseId;

        return $this;
    }

    /**
     * Get diseaseId
     *
     * @return \AppBundle\Entity\Disease
     */
    public function getDiseaseId()
    {
        return $this->diseaseId;
    }

    /**
     * Set preReqId
     *
     * @param \AppBundle\Entity\Disease $preReqId
     *
     * @return pre_req
     */
    public function setPreReqId(\AppBundle\Entity\Disease $preReqId = null)
    {
        $this->preReqId = $preReqId;

        return $this;
    }

    /**
     * Get preReqId
     *
     * @return \AppBundle\Entity\Disease
     */
    public function getPreReqId()
    {
        return $this->preReqId;
    }
}
