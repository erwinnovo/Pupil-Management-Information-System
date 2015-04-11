<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\Quarter;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class QuarterController extends Controller
{
    public function quarterAction()
    {
        $menu = array
        (
            array
            ("href" => "quarter_select", 
             "menuitem" => "Select Quarter Records",
            ),
            array
            ("href" => "quarter_add", 
             "menuitem" => "Add Quarter Records",
            ),
            array
            ("href" => "quarter_update", 
             "menuitem" => "Update Quarter Records",
            ),
            array
            ("href" => "quarter_delete", 
             "menuitem" => "Delete Quarter Records",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:Quarter:quarter.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $quarter = new Quarter();        

        $form = $this->createFormBuilder($quarter)
                     ->add('quarterName', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $quarterData = $form->getData();
                        
            $quarterChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:Quarter')
                            ->findOneBy(
                                        array(  "quarterName" => $quarterData->getQuarterName(),
                                             )
                                       );
            if (!$quarterChecking) 
            {
                $quarter->setQuarterName($quarterData->getQuarterName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($quarter);
                $doctrineIsWorking->flush();

                $message = "New quarter record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new quarter record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
        
    public function selectAction(Request $request)
    {       
        $quarter = new Quarter();
        
        $quarterRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Quarter');
        
        $quarteres = $quarterRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($quarter)
                     ->add('quarterId', 'text')
                     ->add('quarterName', 'text')         
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $quarterData = $form->getData();
            
            //echo "<pre>";
            //print_r($quarterData);
            //echo "</pre>";
            //exit;
            //$quarterData = $form->getValues();
            $quarterRecords = $this->getDoctrine()
                                 ->getRepository('ErwinStudentAdministrationSystemBundle:Quarter');

            $quarteres = $quarterRecords->findBy(array_filter(array("quarterId" => $quarterData->getQuarterId(),
                                                                "quarterName" => $quarterData->getQuarterName(),
                                                               ), 
                                                          function ($var){ return null !== $var; })
                                            );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:Quarter:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "quarter" => $quarteres,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $quarter = new Quarter();        

        $quarterRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Quarter');
        $quarterId = $request->query->get("id");
        $quarterResult = $quarterRecords->find($quarterId);        

        $form = $this->createFormBuilder($quarter)
                     ->add('quarterId', 'text')
                     ->add('quarterName', 'text')              
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $quarterData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:Quarter')
                                    ->find($quarterResult->getQuarterId());            
                
                $repository->setQuarterName($quarterData->getQuarterName());
                $manager->flush();                
                
                $message = "Quarter record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "quarter" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Quarter record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "quarter" => $quarterResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "quarter" => $quarterResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $quarter = new Quarter();        

        $quarterRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:Quarter');
        $quarterId = $request->query->get("id");
        $quarterResult = $quarterRecords->find($quarterId);
        
        $form = $this->createFormBuilder($quarter)
                     ->add('quarterId', 'text')
                     ->add('quarterName', 'text')             
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $quarterData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:Quarter')
                                      ->find($quarterResult->getQuarterId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Quarter record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "quarter" => $quarter,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Quarter record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "quarter" => $quarterResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:Quarter:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "quarter" => $quarterResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}