<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    
    public function testAction()
    {
        return $this->render(
                'ErwinStudentAdministrationSystemBundle:Test:test.html.twig',
                array());
    }    
}
