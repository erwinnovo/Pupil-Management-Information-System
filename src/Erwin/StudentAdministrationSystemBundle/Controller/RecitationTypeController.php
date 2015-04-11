<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erwin\StudentAdministrationSystemBundle\Entity\RecitationType;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class RecitationTypeController extends Controller
{
    public function recitationTypeAction()
    {
        $menu = array
        (
            array
            ("href" => "recitationType_select", 
             "menuitem" => "Select Recitation Type",
            ),
            array
            ("href" => "recitationType_add", 
             "menuitem" => "Add Recitation Type",
            ),
            array
            ("href" => "recitationType_update", 
             "menuitem" => "Update Recitation Type",
            ),
            array
            ("href" => "recitationType_delete", 
             "menuitem" => "Delete Recitation Type",
            ),
        );
        return $this->render
               (
                    'ErwinStudentAdministrationSystemBundle:RecitationType:recitationType.html.twig',
                    array
                    (
                        "menu" => $menu,
                    )
               );
    }
    
    public function addAction(Request $request)
    {
        $recitationType = new RecitationType();        

        $form = $this->createFormBuilder($recitationType)
                     ->add('recitationTypeName', 'text')                
                     ->add('save', 'submit')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) 
        {   
            $recitationTypeData = $form->getData();
                        
            $recitationTypeChecking = $this->getDoctrine()
                            ->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType')
                            ->findOneBy(
                                        array(  "recitationTypeName" => $recitationTypeData->getRecitationTypeName(),
                                             )
                                       );
            if (!$recitationTypeChecking) 
            {
                $recitationType->setRecitationTypeName($recitationTypeData->getRecitationTypeName());

                $doctrineIsWorking = $this->getDoctrine()->getManager();

                $doctrineIsWorking->persist($recitationType);
                $doctrineIsWorking->flush();

                $message = "New recitation type record is ADDED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );                    
            }
            else
            {
                $message = "Sorry cannot add new recitation type record because it already exist.";
                return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:add.html.twig', 
                                     array("form" => $form->createView(),
                                           "message" => $message,
                                          )
                                    );

            }
        }        
        else
        {
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:add.html.twig', 
                                 array("form" => $form->createView(),
                                       "message" => $message,
                                      )
                                );
        }
    }
        
    public function selectAction(Request $request)
    {       
        $recitationType = new RecitationType();
        
        $recitationTypeRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType');
        
        $recitationTypees = $recitationTypeRecords->findAll();
        //$width = "150px";
        $form = $this->createFormBuilder($recitationType)
                     ->add('recitationTypeId', 'text')
                     ->add('recitationTypeName', 'text')         
                     ->add('search', 'submit')                     
                     ->getForm();

        $form->handleRequest($request);
        
        if($form->isValid()) 
        {
            //search            
            $recitationTypeData = $form->getData();
            
            //echo "<pre>";
            //print_r($recitationTypeData);
            //echo "</pre>";
            //exit;
            //$recitationTypeData = $form->getValues();
            $recitationTypeRecords = $this->getDoctrine()
                                 ->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType');

            $recitationTypees = $recitationTypeRecords->findBy(array_filter(array("recitationTypeId" => $recitationTypeData->getRecitationTypeId(),
                                                                "recitationTypeName" => $recitationTypeData->getRecitationTypeName(),
                                                               ), 
                                                          function ($var){ return null !== $var; })
                                            );      
        }
                
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:RecitationType:select.html.twig',
                    array
                    (
                        "form" => $form->createView(),
                        "recitationType" => $recitationTypees,
                    )
                );
    }       
    
    public function updateAction(Request $request)
    {
        $recitationType = new RecitationType();        

        $recitationTypeRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType');
        $recitationTypeId = $request->query->get("id");
        $recitationTypeResult = $recitationTypeRecords->find($recitationTypeId);        

        $form = $this->createFormBuilder($recitationType)
                     ->add('recitationTypeId', 'text')
                     ->add('recitationTypeName', 'text')              
                     ->add('cancel', 'submit')
                     ->add('update', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {               
            if ($form->get("update")->isClicked())
            {
                $recitationTypeData = $form->getData();
                
                $manager = $this->getDoctrine()->getManager();
                $repository=$manager->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType')
                                    ->find($recitationTypeResult->getRecitationTypeId());            
                
                $repository->setRecitationTypeName($recitationTypeData->getRecitationTypeName());
                $manager->flush();                
                
                $message = "Recitation type record UPDATED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "recitationType" => $repository,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Recitation type record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:update.html.twig', 
                                     array("form" => $form->createView(),
                                           "recitationType" => $recitationTypeResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }        
            
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:update.html.twig', 
                                 array("form" => $form->createView(),
                                       "recitationType" => $recitationTypeResult,
                                       "message" => $message,
                                      )
                                );
        }
    }

    public function deleteAction(Request $request)
    {
        $recitationType = new RecitationType();        

        $recitationTypeRecords = $this->getDoctrine()
                               ->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType');
        $recitationTypeId = $request->query->get("id");
        $recitationTypeResult = $recitationTypeRecords->find($recitationTypeId);
        
        $form = $this->createFormBuilder($recitationType)
                     ->add('recitationTypeId', 'text')
                     ->add('recitationTypeName', 'text')             
                     ->add('cancel', 'submit')
                     ->add('delete', 'submit')                
                     ->getForm();

        $form->handleRequest($request);
            
        if ($form->isValid()) 
        {
            $recitationTypeData = $form->getData();            
            
            if ($form->get("delete")->isClicked())
            {
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository('ErwinStudentAdministrationSystemBundle:RecitationType')
                                      ->find($recitationTypeResult->getRecitationTypeId());            
                                
                $manager->remove($repository);
                $manager->flush();
                
                $message = "Recitation type record DELETED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "recitationType" => $recitationType,
                                           "message" => $message,
                                          )
                                    );                    
            }                
                
            if ($form->get("cancel")->isClicked())
            {
                $message = "Recitation type record REMAINS UNCHANGED.";

                return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:delete.html.twig', 
                                     array("form" => $form->createView(),
                                           "recitationType" => $recitationTypeResult,
                                           "message" => $message,                                          
                                          )
                                    );
            }   
        }
        else
        {                    
            $message = "";
            return $this->render('ErwinStudentAdministrationSystemBundle:RecitationType:delete.html.twig', 
                                 array("form" => $form->createView(),
                                       "recitationType" => $recitationTypeResult,
                                       "message" => $message,
                                      )
                                );
        }
    }           
}