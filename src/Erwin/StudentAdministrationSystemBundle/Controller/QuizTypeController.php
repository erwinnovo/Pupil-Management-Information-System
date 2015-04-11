<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\QuizType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class QuizTypeController extends Controller
{
    public function quizTypeAction()
    {
        $menu = array
        (
            array
            ("href" => "quizType_select", 
             "menuitem" => "Select Quiz Type",
            ),
            array
            ("href" => "quizType_add", 
             "menuitem" => "Add Quiz Type",
            ),
            array
            ("href" => "quizType_update", 
             "menuitem" => "Update Quiz Type",
            ),
            array
            ("href" => "quizType_delete", 
             "menuitem" => "Delete Quiz Type",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:QuizType:quizType.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $quizType = new QuizType();        

        $form = $this->createFormBuilder($quizType)
                     ->add('quizTypeName', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $quizTypeData = $form->getData();
                        
            $quizTypeChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:QuizType')
                            ->findOneBy(
                                        array(  "quizTypeName" => $quizTypeData->getQuizTypeName(),
                                             )
                                       );
            if (!$quizTypeChecking) 
            {
                $quizType->setQuizTypeName($quizTypeData->getQuizTypeName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($quizType);
                $doctrineIsWorking->flush();

                $message = "New quiz type record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new quiz type record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
        
    public function selectAction(Request $request)
    {       
        $quizType = new QuizType();
        
        $quizTypeRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:QuizType');
        
        $quizTypees = $quizTypeRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($quizType)
                     ->add('quizTypeId', 'text')
                     ->add('quizTypeName', 'text')         
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $quizTypeData = $form->getData();
            
            //echo "<pre>";
            //print_r($quizTypeData);
            //echo "</pre>";
            //exit;
            //$quizTypeData = $form->getValues();
            $quizTypeRecords = $this->getDoctrine()
                                 ->getRepository('ErwinStudentAdministrationSystemBundle:QuizType');

            $quizTypees = $quizTypeRecords->findBy(array_filter(array("quizTypeId" => $quizTypeData->getQuizTypeId(),
                                                                "quizTypeName" => $quizTypeData->getQuizTypeName(),
                                                               ), 
                                                          function ($var){ return null !== $var; })
                                            );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:QuizType:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "quizType" => $quizTypees,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $quizType = new QuizType();        

        $quizTypeRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:QuizType');
        $quizTypeId = $request->query->get("id");
        $quizTypeResult = $quizTypeRecords->find($quizTypeId);        

        $form = $this->createFormBuilder($quizType)
                     ->add('quizTypeId', 'text')
                     ->add('quizTypeName', 'text')              
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $quizTypeData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:QuizType')
                                    ->find($quizTypeResult->getQuizTypeId());            
                
                $repository->setQuizTypeName($quizTypeData->getQuizTypeName());
                $manager->flush();                
                
                $message = "Quiz type record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "quizType" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Quiz type record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "quizType" => $quizTypeResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "quizType" => $quizTypeResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $quizType = new QuizType();        

        $quizTypeRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:QuizType');
        $quizTypeId = $request->query->get("id");
        $quizTypeResult = $quizTypeRecords->find($quizTypeId);
        
        $form = $this->createFormBuilder($quizType)
                     ->add('quizTypeId', 'text')
                     ->add('quizTypeName', 'text')             
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $quizTypeData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:QuizType')
                                      ->find($quizTypeResult->getQuizTypeId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Quiz type record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "quizType" => $quizType,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Quiz type record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "quizType" => $quizTypeResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:QuizType:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "quizType" => $quizTypeResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}