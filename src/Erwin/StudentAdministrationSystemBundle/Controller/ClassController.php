<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\Clas;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class ClassController extends Controller
{
    public function classAction()
    {
        $menu = array
        (
            array
            ("href" => "class_select", 
             "menuitem" => "Select Class Records",
            ),
            array
            ("href" => "class_add", 
             "menuitem" => "Add Class Records",
            ),
            array
            ("href" => "class_update", 
             "menuitem" => "Update Class Records",
            ),
            array
            ("href" => "class_delete", 
             "menuitem" => "Delete Class Records",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:Class:class.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $class = new Clas();        

        $form = $this->createFormBuilder($class)
                     ->add('className', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $classData = $form->getData();
                        
            $classChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:Clas')
                            ->findOneBy(
                                        array(  "className" => $classData->getClassName(),
                                             )
                                       );
            if (!$classChecking) 
            {
                $class->setClassName($classData->getClassName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($class);
                $doctrineIsWorking->flush();

                $message = "New class record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Class:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new class record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:Class:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Class:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
        
    public function selectAction(Request $request)
    {       
        $class = new Clas();
        
        $classRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Clas');
        
        $classes = $classRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($class)
                     ->add('classId', 'text')
                     ->add('className', 'text')         
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $classData = $form->getData();
            
            //echo "<pre>";
            //print_r($classData);
            //echo "</pre>";
            //exit;
            //$classData = $form->getValues();
            $classRecords = $this->getDoctrine()
                                 ->getRepository('ErwinStudentAdministrationSystemBundle:Clas');

            $classes = $classRecords->findBy(array_filter(array("classId" => $classData->getClassId(),
                                                                "className" => $classData->getClassName(),
                                                               ), 
                                                          function ($var){ return null !== $var; })
                                            );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:Class:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "class" => $classes,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $class = new Clas();        

        $classRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Clas');
        $classId = $request->query->get("id");
        $classResult = $classRecords->find($classId);        

        $form = $this->createFormBuilder($class)
                     ->add('classId', 'text')
                     ->add('className', 'text')              
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $classData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:Clas')
                                    ->find($classResult->getClassId());            
                
                $repository->setClassName($classData->getClassName());
                $manager->flush();                
                
                $message = "Class record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Class:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "class" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Class record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Class:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "class" => $classResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Class:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "class" => $classResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $class = new Clas();        

        $classRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Clas');
        $classId = $request->query->get("id");
        $classResult = $classRecords->find($classId);
        
        $form = $this->createFormBuilder($class)
                     ->add('classId', 'text')
                     ->add('className', 'text')             
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $classData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Clas')
                                      ->find($classResult->getClassId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Class record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Class:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "class" => $class,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Class record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Class:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "class" => $classResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Class:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "class" => $classResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}