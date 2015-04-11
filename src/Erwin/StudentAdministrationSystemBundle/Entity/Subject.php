<?php

/**
 * Description of Subject
 *
 * @author erwin
 */

namespace Erwin\StudentAdministrationSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subject")
 */
class Subject
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $subjectId;

    /**
    * @ORM\Column(type="string", length=255)
    */    
    protected $subjectName;

    /**
     * Get subjectId
     *
     * @return integer 
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * Set subjectId
     *
     * @return integer 
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId =  $subjectId;
        
        return $this;
    }
    
    
    /**
     * Set subjectName
     *
     * @param string $subjectName
     * @return string
     */
    public function setSubjectName($subjectName)
    {
        $this->subjectName = $subjectName;

        return $this;
    }

    /**
     * Get subjectName
     *
     * @return string 
     */
    public function getSubjectName()
    {
        return $this->subjectName;
    }
}
