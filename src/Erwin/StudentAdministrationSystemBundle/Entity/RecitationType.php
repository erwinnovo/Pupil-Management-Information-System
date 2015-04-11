<?php

/**
 * Description of RecitationType
 *
 * @author erwin
 */

namespace Erwin\StudentAdministrationSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="recitationType")
 */
class RecitationType
{
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $recitationTypeId;

    /**
    * @ORM\Column(type="string", length=255)
    */    
    protected $recitationTypeName;

    /**
     * Get recitationTypeId
     *
     * @return integer 
     */
    public function getRecitationTypeId()
    {
        return $this->recitationTypeId;
    }

    /**
     * Set recitationTypeId
     *
     * @return integer 
     */
    public function setRecitationTypeId($recitationTypeId)
    {
        $this->recitationTypeId =  $recitationTypeId;
        
        return $this;
    }
    
    
    /**
     * Set recitationTypeName
     *
     * @param string $recitationTypeName
     * @return string
     */
    public function setRecitationTypeName($recitationTypeName)
    {
        $this->recitationTypeName = $recitationTypeName;

        return $this;
    }

    /**
     * Get recitationTypeName
     *
     * @return string 
     */
    public function getRecitationTypeName()
    {
        return $this->recitationTypeName;
    }
}
