<?php

/**
 * Description of Clas
 *
 * @author erwin
 */

namespace Erwin\StudentAdministrationSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="class")
 */
class Clas 
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $classId;

    /**
    * @ORM\Column(type="string", length=255)
    */    
    protected $className;

    /**
     * Get classId
     *
     * @return integer 
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * Set classId
     *
     * @return integer 
     */
    public function setClassId($classId)
    {
        $this->classId =  $classId;
        
        return $this;
    }
    
    
    /**
     * Set className
     *
     * @param string $className
     * @return Clas
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string 
     */
    public function getClassName()
    {
        return $this->className;
    }
}
