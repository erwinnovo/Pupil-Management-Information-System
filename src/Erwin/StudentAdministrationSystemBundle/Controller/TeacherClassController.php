<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\TeacherClass;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class TeacherClassController extends Controller
{
    public function teacherClassAction()
    {
        $menu = array
        (
            array
            ("href" => "teacherClass_select", 
             "menuitem" => "Select Teacher to Class",
            ),
            array
            ("href" => "teacherClass_add", 
             "menuitem" => "Add Teacher to Class",
            ),
            array
            ("href" => "teacherClass_update", 
             "menuitem" => "Update Teacher to Class",
            ),
            array
            ("href" => "teacherClass_delete", 
             "menuitem" => "Delete Teacher to Class",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:TeacherClass:teacherClass.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $teacherClass = new TeacherClass();        

        $form = $this->createFormBuilder($teacherClass)
                     ->add('teacherId', 'text')
                     ->add('classId', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $teacherClassData = $form->getData();
                        
            $teacherClassChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass')
                            ->findOneBy(
                                        array("teacherClassName" => $teacherClassData->getTeacherClassName(),
                                             )
                                       );
            if (!$teacherClassChecking) 
            {
                $teacherClass->setTeacherClassName($teacherClassData->getTeacherClassName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($teacherClass);
                $doctrineIsWorking->flush();

                $message = "New teacher to class record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new teacher to class record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
        
    public function selectAction(Request $request)
    {       
        $teacherClass = new TeacherClass();
        
        $teacherClassRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass');
        
        $teacherClasses = $teacherClassRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($teacherClass)
                     ->add('teacherClassId', 'text')
                     ->add('classId', 'text')         
                     ->add('teacherId', 'text')         
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $teacherClassData = $form->getData();
            
            //echo "<pre>";
            //print_r($teacherClassData);
            //echo "</pre>";
            //exit;
            //$teacherClassData = $form->getValues();
            $teacherClassRecords = $this->getDoctrine()
                                 ->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass');

            $teacherClasses = $teacherClassRecords->findBy(array_filter(array("teacherClassId" => $teacherClassData->getTeacherClassId(),
                                                                "teacherClassName" => $teacherClassData->getTeacherClassName(),
                                                               ), 
                                                          function ($var){ return null !== $var; })
                                            );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:TeacherClass:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "teacherClass" => $teacherClasses,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $teacherClass = new TeacherClass();        

        $teacherClassRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass');
        $teacherClassId = $request->query->get("id");
        $teacherClassResult = $teacherClassRecords->find($teacherClassId);        

        $form = $this->createFormBuilder($teacherClass)
                     ->add('teacherClassId', 'text')
                     ->add('classId', 'text') 
                     ->add('teacherId', 'text')              
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $teacherClassData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass')
                                    ->find($teacherClassResult->getTeacherClassId());            
                
                $repository->setTeacherClassName($teacherClassData->getTeacherClassName());
                $manager->flush();                
                
                $message = "Teacher to class record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacherClass" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Teacher to class record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacherClass" => $teacherClassResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "teacherClass" => $teacherClassResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $teacherClass = new TeacherClass();        

        $teacherClassRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass');
        $teacherClassId = $request->query->get("id");
        $teacherClassResult = $teacherClassRecords->find($teacherClassId);
        
        $form = $this->createFormBuilder($teacherClass)
                     ->add('teacherClassId', 'text')
                     ->add('classId', 'text')
                     ->add('teacherId', 'text')             
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $teacherClassData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:TeacherClass')
                                      ->find($teacherClassResult->getTeacherClassId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Teacher to class record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacherClass" => $teacherClass,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Teacher to class record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacherClass" => $teacherClassResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:TeacherClass:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "teacherClass" => $teacherClassResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}