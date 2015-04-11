<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function contactAction()            
    {

        return $this->render(
                'ErwinStudentAdministrationSystemBundle:Contact:contact.html.twig', 
                array(
                    
                )
               );
    }
}
