<?php

/**
 * Description of TeacherClass
 *
 * @author erwin
 */

namespace Erwin\StudentAdministrationSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="teacherClass")
 */
class TeacherClass
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $teacherClassId;

    /**
    * @ORM\Column(type="integer")
    */    
    protected $classId;

    
    /**
    * @ORM\Column(type="integer")
    */    
    protected $teacherId;


    /**
     * Get teacherClassId
     *
     * @return integer 
     */
    public function getTeacherClassId()
    {
        return $this->teacherClassId;
    }

    /**
     * Set classId
     *
     * @param integer $classId
     * @return TeacherClass
     */
    public function setTeacherClassId($teacherClassId)
    {
        $this->teacherClassId = $teacherClassId;

        return $this;
    }    
    
    /**
     * Set classId
     *
     * @param integer $classId
     * @return TeacherClass
     */
    public function setClassId($classId)
    {
        $this->classId = $classId;

        return $this;
    }

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
     * Set teacherId
     *
     * @param integer $teacherId
     * @return TeacherClass
     */
    public function setTeacherId($teacherId)
    {
        $this->teacherId = $teacherId;

        return $this;
    }

    /**
     * Get teacherId
     *
     * @return integer 
     */
    public function getTeacherId()
    {
        return $this->teacherId;
    }
}
