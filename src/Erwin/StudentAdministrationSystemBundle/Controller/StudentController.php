<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\Student;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    public function studentAction()
    {
        $menu = array
        (
            array
            ("href" => "student_select", 
             "menuitem" => "Select Pupil Records",
            ),
            array
            ("href" => "student_add", 
             "menuitem" => "Add Pupil Records",
            ),
            array
            ("href" => "student_update", 
             "menuitem" => "Update Pupil Records",
            ),
            array
            ("href" => "student_delete", 
             "menuitem" => "Delete Pupil Records",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:Student:student.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        /*
        $menu = array
        (
            array
            ("href" => "student_select", 
             "menuitem" => "Select Student Records",
            ),
            array
            ("href" => "student_add", 
             "menuitem" => "Add Student Records",
            ),
            array
            ("href" => "student_update", 
             "menuitem" => "Update Student Records",
            ),
            array
            ("href" => "student_delete", 
             "menuitem" => "Delete Student Records",
            ),
        );        
        */
        $student = new Student();        

        $form = $this->createFormBuilder($student)
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $studentData = $form->getData();
                        
            $studentChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                            ->findOneBy(
                                        array(  "firstName" => $studentData->getFirstName(), 
                                                "middleName" => $studentData->getMiddleName(),
                                                "lastName" => $studentData->getLastName(),
                                             )
                                       );
            if (!$studentChecking) 
            {
                $student->setFirstName($studentData->getFirstName());
                $student->setMiddleName($studentData->getMiddleName());
                $student->setLastName($studentData->getLastName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($student);
                $doctrineIsWorking->flush();

                $message = "New student record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Student:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new student record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:Student:add.html.twig', 
                                     array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Student:add.html.twig', 
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
            ("href" => "student_select", 
             "menuitem" => "Select Student Records"
            ),
            array
            ("href" => "student_add", 
             "menuitem" => "Add Student Records"
            ),
            array
            ("href" => "student_update", 
             "menuitem" => "Update Student Records"
            ),
            array
            ("href" => "student_delete", 
             "menuitem" => "Delete Student Records"
            ),
        );        
        
        $student = new Student();
        
        return $this->render('ErwinStudentAdministrationSystemBundle:Student:add.html.twig', 
                             array("menu" => $menu,
                                  )
                            );
    }   
    
    public function oldSelectAction($id)
    {
        $student = $this->getDoctrine()
                        ->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                        ->find($id);

        if (!$student) 
        {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        
        return new Response('Student Name is '.$student->getFirstName(). " " . $student->getMiddleName() ." ". $student->getLastName());
    }   
    
    public function selectAction(Request $request)
    {
        $student = new Student();
        
        $studentRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Student');
        
        $students = $studentRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($student)
                     ->add('studentId', 'text')
                     ->add('firstName', 'text')                          
                     ->add('middleName', 'text')                          
                     ->add('lastName', 'text')          
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $studentData = $form->getData();
            //$studentData = $form->getValues();
            $studentRecords = $this->getDoctrine()
                                   ->getRepository('ErwinStudentAdministrationSystemBundle:Student');

            $students = $studentRecords->findBy(array_filter(array("studentId" => $studentData->getStudentId(),
                                                                   "firstName" => $studentData->getFirstName(),
                                                                   "middleName" => $studentData->getMiddleName(),
                                                                   "lastName" => $studentData->getLastName(),
                                                                  ), 
                                                             function ($var){ return null !== $var; })
                                                );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:Student:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "students" => $students,
                    )
                );

    }       
    
    public function updateAction(Request $request)
    {
        $student = new Student();        

        $studentRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Student');
        
        $studentId = $request->query->get("id");
        
        $studentResult = $studentRecords->find($studentId);
        
        //echo "<pre>";
        //print_r($studentResult);
        //echo "</pre>";
        //exit;
        $form = $this->createFormBuilder($student)
                     ->add('studentId', 'text')
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('update', 'submit')
                     ->add('cancel', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $studentData = $form->getData();
        //echo "<pre>";
        //print_r($studentData);
        //echo "</pre>";
        //exit;                
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                                      ->find($studentResult->getStudentId());            
        //echo "<pre>";
        //print_r($repository);
        //echo "</pre>";
                
                $repository->setFirstName($studentData->getFirstName());
                $repository->setMiddleName($studentData->getMiddleName());
                $repository->setLastName($studentData->getLastName());
                
                $manager->flush();                
                
                $message = "Pupil record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Student:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Pupil record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Student:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $studentResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }
        }
        else
        {             
            $message = "DO YOU WANT TO MAKE CHANGES TO THE PUPIL RECORD ABOVE?";
            return $this->render('ErwinStudentAdministrationSystemBundle:Student:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "student" => $studentResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function oldupdateAction(Request $request)
    {
        $student = new Student();        

        $studentRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Student');

        $studentsId = $studentRecords->findAll();
        
        foreach($studentsId as $id)
        { 
            $idArray[$id->getStudentId()] = $id->getStudentId(); 
        }

        $form = $this->createFormBuilder($student)
                     ->add('studentId', 'choice', array("choices" => $idArray))
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('search', 'submit', array('label' => 'Search Pupil Record'))
                     ->add('update', 'submit', array('label' => 'Update Pupil Record'))                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $studentData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                                    ->find($studentData->getStudentId());            
                
                $repository->setFirstName($studentData->getFirstName());
                $repository->setMiddleName($studentData->getMiddleName());
                $repository->setLastName($studentData->getLastName());
                
                $manager->flush();                
                
                $message = "Pupil record updated.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Student:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("search")->isClicked())
            {
                $studentData = $form->getData();
                $doctrine = $this->getDoctrine();
                $entityManager = $doctrine->getManager();                
                $studentRepository = $entityManager->getRepository('ErwinStudentAdministrationSystemBundle:Student');
                $student = $studentRepository->find($studentData->getStudentId());
                //$studentData->getStudentId()
                if (!$student)
                {                    
                    $message = "Pupil record not found.";
                }
                else
                {
                    $message = "Pupil record found.";
                }

                return $this->render('ErwinStudentAdministrationSystemBundle:Student:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $student,
                                           "message" => $message,                                          
                                          )
                                    );
            }
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Student:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        // Build the search&destroy form
        // Find all the students
        // Delete if button is pressed
        // Render the view form thingy
        
        $student = new Student();        

        $studentRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Student');
        
        $studentId = $request->query->get("id");
        
        $studentResult = $studentRecords->find($studentId);
        
        $form = $this->createFormBuilder($student)
                     ->add('studentId', 'text')
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('cancel', 'submit', array('label' => 'Cancel'))
                     ->add('delete', 'submit', array('label' => 'Delete Record'))                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $studentData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                                      ->find($studentResult->getStudentId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Pupil record DELETED.";

                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                                      ->findAll();            
                                
                return $this->render('ErwinStudentAdministrationSystemBundle:Student:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $student,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                //$studentData = $form->getData();
                $doctrine = $this->getDoctrine();
                $entityManager = $doctrine->getManager();                
                $studentRepository = $entityManager->getRepository('ErwinStudentAdministrationSystemBundle:Student');
                $student = $studentRepository->find($studentId);
                //$studentData->getStudentId()
                
                $message = "Pupil record REMAINS UNCHANGED.";
                return $this->render('ErwinStudentAdministrationSystemBundle:Student:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $student,
                                           "message" => $message,
                                          )
                                    );
            }       
        }
        else
        {   
            
            $message = "DO YOU WANT TO DELETE THE PUPIL RECORD ABOVE?";
            return $this->render('ErwinStudentAdministrationSystemBundle:Student:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "student" => $studentResult,
                                       "message" => $message,
                                      )
                                );
        }
    }  
    
    public function olddeleteAction(Request $request)
    {
        // Build the search&destroy form
        // Find all the students
        // Delete if button is pressed
        // Render the view form thingy
        
        $student = new Student();        

        $studentRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Student');

        $studentsId = $studentRecords->findAll();

        $index = 1;
        
        foreach($studentsId as $id)
        { 
            $idArray[$id->getStudentId()] = $id->getStudentId(); 
            $index++;
        }
        
        $form = $this->createFormBuilder($student)
                     ->add('studentId', 'choice', array("choices" => $idArray))
                     ->add('firstName', 'text')
                     ->add('middleName', 'text')
                     ->add('lastName', 'text')                
                     ->add('search', 'submit', array('label' => 'Search Pupil Record'))
                     ->add('delete', 'submit', array('label' => 'Delete Pupil Record'))                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $studentData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                                      ->find($studentData->getStudentId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Pupil record deleted.";

                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Student')
                                      ->findAll();            
                                
                return $this->render('ErwinStudentAdministrationSystemBundle:Student:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("search")->isClicked())
            {
                //$studentData = $form->getData();
                $doctrine = $this->getDoctrine();
                $entityManager = $doctrine->getManager();                
                $studentRepository = $entityManager->getRepository('ErwinStudentAdministrationSystemBundle:Student');
                $student = $studentRepository->find($studentData->getStudentId());
                //$studentData->getStudentId()
                if (!$student)
                {                    
                    $message = "Pupil record not found.";
                }
                else
                {
                    $message = "Pupil record found.";
                }

                return $this->render('ErwinStudentAdministrationSystemBundle:Student:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "student" => $student,
                                           "message" => $message,
                                          )
                                    );
            }       
        }
        else
        {   
            
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Student:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }      
}