<?php

/**
 * Description of QuizType
 *
 * @author erwin
 */

namespace Erwin\StudentAdministrationSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quizType")
 */
class QuizType
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $quizTypeId;

    /**
    * @ORM\Column(type="string", length=255)
    */    
    protected $quizTypeName;

    /**
     * Get quizTypeId
     *
     * @return integer 
     */
    public function getQuizTypeId()
    {
        return $this->quizTypeId;
    }

    /**
     * Set quizTypeId
     *
     * @return integer 
     */
    public function setQuizTypeId($quizTypeId)
    {
        $this->quizTypeId =  $quizTypeId;
        
        return $this;
    }
    
    
    /**
     * Set quizTypeName
     *
     * @param string $quizTypeName
     * @return string
     */
    public function setQuizTypeName($quizTypeName)
    {
        $this->quizTypeName = $quizTypeName;

        return $this;
    }

    /**
     * Get quizTypeName
     *
     * @return string 
     */
    public function getQuizTypeName()
    {
        return $this->quizTypeName;
    }
}
