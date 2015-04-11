<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\Subject;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    public function subjectAction()
    {
        $menu = array
        (
            array
            ("href" => "subject_select", 
             "menuitem" => "Select Subject Records",
            ),
            array
            ("href" => "subject_add", 
             "menuitem" => "Add Subject Records",
            ),
            array
            ("href" => "subject_update", 
             "menuitem" => "Update Subject Records",
            ),
            array
            ("href" => "subject_delete", 
             "menuitem" => "Delete Subject Records",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:Subject:subject.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $subject = new Subject();        

        $form = $this->createFormBuilder($subject)
                     ->add('subjectName', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $subjectData = $form->getData();
                        
            $subjectChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:Subject')
                            ->findOneBy(
                                        array(  "subjectName" => $subjectData->getSubjectName(),
                                             )
                                       );
            if (!$subjectChecking) 
            {
                $subject->setSubjectName($subjectData->getSubjectName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($subject);
                $doctrineIsWorking->flush();

                $message = "New subject record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Subject:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new subject record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:Subject:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Subject:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
        
    public function selectAction(Request $request)
    {       
        $subject = new Subject();
        
        $subjectRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Subject');
        
        $subjectes = $subjectRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($subject)
                     ->add('subjectId', 'text')
                     ->add('subjectName', 'text')         
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $subjectData = $form->getData();
            
            //echo "<pre>";
            //print_r($subjectData);
            //echo "</pre>";
            //exit;
            //$subjectData = $form->getValues();
            $subjectRecords = $this->getDoctrine()
                                 ->getRepository('ErwinStudentAdministrationSystemBundle:Subject');

            $subjectes = $subjectRecords->findBy(array_filter(array("subjectId" => $subjectData->getSubjectId(),
                                                                "subjectName" => $subjectData->getSubjectName(),
                                                               ), 
                                                          function ($var){ return null !== $var; })
                                            );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:Subject:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "subject" => $subjectes,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $subject = new Subject();        

        $subjectRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Subject');
        $subjectId = $request->query->get("id");
        $subjectResult = $subjectRecords->find($subjectId);        

        $form = $this->createFormBuilder($subject)
                     ->add('subjectId', 'text')
                     ->add('subjectName', 'text')              
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $subjectData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:Subject')
                                    ->find($subjectResult->getSubjectId());            
                
                $repository->setSubjectName($subjectData->getSubjectName());
                $manager->flush();                
                
                $message = "Subject record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Subject:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "subject" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Subject record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Subject:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "subject" => $subjectResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Subject:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "subject" => $subjectResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $subject = new Subject();        

        $subjectRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Subject');
        $subjectId = $request->query->get("id");
        $subjectResult = $subjectRecords->find($subjectId);
        
        $form = $this->createFormBuilder($subject)
                     ->add('subjectId', 'text')
                     ->add('subjectName', 'text')             
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $subjectData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Subject')
                                      ->find($subjectResult->getSubjectId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Subject record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Subject:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "subject" => $subject,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Subject record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Subject:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "subject" => $subjectResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Subject:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "subject" => $subjectResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}