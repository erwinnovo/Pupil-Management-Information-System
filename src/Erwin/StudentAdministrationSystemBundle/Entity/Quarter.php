<?php

/**
 * Description of Quarter
 *
 * @author erwin
 */

namespace Erwin\StudentAdministrationSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quarter")
 */
class Quarter
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $quarterId;

    /**
    * @ORM\Column(type="string", length=255)
    */    
    protected $quarterName;

    /**
     * Get quarterId
     *
     * @return integer 
     */
    public function getQuarterId()
    {
        return $this->quarterId;
    }

    /**
     * Set quarterId
     *
     * @return integer 
     */
    public function setQuarterId($quarterId)
    {
        $this->quarterId =  $quarterId;
        
        return $this;
    }
    
    
    /**
     * Set quarterName
     *
     * @param string $quarterName
     * @return string
     */
    public function setQuarterName($quarterName)
    {
        $this->quarterName = $quarterName;

        return $this;
    }

    /**
     * Get quarterName
     *
     * @return string 
     */
    public function getQuarterName()
    {
        return $this->quarterName;
    }
}
