<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\Teacher;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    public function teacherAction()
    {
        $menu = array
        (
            array
            ("href" => "teacher_select", 
             "menuitem" => "Select Teacher Records",
            ),
            array
            ("href" => "teacher_add", 
             "menuitem" => "Add Teacher Records",
            ),
            array
            ("href" => "teacher_update", 
             "menuitem" => "Update Teacher Records",
            ),
            array
            ("href" => "teacher_delete", 
             "menuitem" => "Delete Teacher Records",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:Teacher:teacher.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $teacher = new Teacher();        

        $form = $this->createFormBuilder($teacher)
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $teacherData = $form->getData();
                        
            $teacherChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:Teacher')
                            ->findOneBy(
                                        array(  "firstName" => $teacherData->getFirstName(), 
                                                "middleName" => $teacherData->getMiddleName(),
                                                "lastName" => $teacherData->getLastName(),
                                             )
                                       );
            if (!$teacherChecking) 
            {
                $teacher->setFirstName($teacherData->getFirstName());
                $teacher->setMiddleName($teacherData->getMiddleName());
                $teacher->setLastName($teacherData->getLastName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($teacher);
                $doctrineIsWorking->flush();

                $message = "New teacher record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new teacher record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:add.html.twig', 
                                     array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
    
    public function oldaddAction()
    {
        $menu = array
        (
            array
            ("href" => "teacher_select", 
             "menuitem" => "Select Teacher Records"
            ),
            array
            ("href" => "teacher_add", 
             "menuitem" => "Add Teacher Records"
            ),
            array
            ("href" => "teacher_update", 
             "menuitem" => "Update Teacher Records"
            ),
            array
            ("href" => "teacher_delete", 
             "menuitem" => "Delete Teacher Records"
            ),
        );        
        
        $teacher = new Teacher();
        
        return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:add.html.twig', 
                             array("menu" => $menu,
                                  )
                            );
    }   
    
    public function oldSelectAction($id)
    {
        $teacher = $this->getDoctrine()
                        ->getRepository('ErwinStudentAdministrationSystemBundle:Teacher')
                        ->find($id);

        if (!$teacher) 
        {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        
        return new Response('Teacher Name is '.$teacher->getFirstName(). " " . $teacher->getMiddleName() ." ". $teacher->getLastName());
    }   
    
    public function selectAction(Request $request)
    {       
        $teacher = new Teacher();
        
        $teacherRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Teacher');
        
        $teachers = $teacherRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($teacher)
                     ->add('teacherId', 'text')
                     ->add('firstName', 'text')                          
                     ->add('middleName', 'text')                          
                     ->add('lastName', 'text')          
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $teacherData = $form->getData();
            //$teacherData = $form->getValues();
            $teacherRecords = $this->getDoctrine()
                                   ->getRepository('ErwinStudentAdministrationSystemBundle:Teacher');

            $teachers = $teacherRecords->findBy(array_filter(array("teacherId" => $teacherData->getTeacherId(),
                                                                   "firstName" => $teacherData->getFirstName(),
                                                                   "middleName" => $teacherData->getMiddleName(),
                                                                   "lastName" => $teacherData->getLastName(),
                                                                  ), 
                                                             function ($var){ return null !== $var; })
                                                );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:Teacher:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "teachers" => $teachers,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $teacher = new Teacher();        

        $teacherRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Teacher');
        $teacherId = $request->query->get("id");
        $teacherResult = $teacherRecords->find($teacherId);        

        $form = $this->createFormBuilder($teacher)
                     ->add('teacherId', 'text')
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $teacherData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:Teacher')
                                    ->find($teacherResult->getTeacherId());            
                
                $repository->setFirstName($teacherData->getFirstName());
                $repository->setMiddleName($teacherData->getMiddleName());
                $repository->setLastName($teacherData->getLastName());
                
                $manager->flush();                
                
                $message = "Teacher record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacher" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Teacher record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacher" => $teacherResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "teacher" => $teacherResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $teacher = new Teacher();        

        $teacherRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Teacher');
        $teacherId = $request->query->get("id");
        $teacherResult = $teacherRecords->find($teacherId);
        
        $form = $this->createFormBuilder($teacher)
                     ->add('teacherId', 'text')
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $teacherData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Teacher')
                                      ->find($teacherResult->getTeacherId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Teacher record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacher" => $teacher,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Teacher record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "teacher" => $teacherResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Teacher:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "teacher" => $teacherResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}