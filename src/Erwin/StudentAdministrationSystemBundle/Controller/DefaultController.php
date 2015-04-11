<?php

namespace Erwin\StudentAdministrationSystemBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()            
    {
        $menu = array 
        ( 
            "Data Maintenance", 
            "Data Entry", 
            "Report Generation", 
        );
        
        $subMenuDataMaintenance = array
        (
            array
            ("href" => "student", 
             "menuitem" => "Pupils",
            ),
            array
            ("href" => "teacher", 
             "menuitem" => "Teachers",
            ),
            array
            ("href" => "class", 
             "menuitem" => "Classes",
            ),            
            array
            ("href" => "subject", 
             "menuitem" => "Subjects",
            ),            
            array
            ("href" => "teacher_subject", 
             "menuitem" => "Teacher to Subject Assignment",
            ),            
            array
            ("href" => "teacherClass", 
             "menuitem" => "Teacher to Class Assignment",
            ),            
            array
            ("href" => "quarter", 
             "menuitem" => "Quarters",
            ),            
            array
            ("href" => "recitationType", 
             "menuitem" => "Recitation Types",
            ),            
            array
            ("href" => "quizType", 
             "menuitem" => "Quiz Types",
            ),                        
        );
        $subMenuDataEntry = array
        (
            array
            ("href" => "quiz_data_entry", 
             "menuitem" => "Quizzes"
            ),
            array
            ("href" => "recitation_data_entry", 
             "menuitem" => "Recitations"
            ),
            array
            ("href" => "assignment_data_entry", 
             "menuitem" => "Assignments"
            ),
            array
            ("href" => "project_data_entry", 
             "menuitem" => "Projects"
            ),
            array
            ("href" => "examination_data_entry", 
             "menuitem" => "Examinations"
            ),            
        );
        $subMenuReportGeneration = array
        (
            array
            ("href" => "quiz_report_generation", 
             "menuitem" => "Quizzes"
            ),
            array
            ("href" => "recitation_report_generation", 
             "menuitem" => "Recitations"
            ),
            array
            ("href" => "assignment_report_generation", 
             "menuitem" => "Assignments"
            ),
            array
            ("href" => "project_report_generation", 
             "menuitem" => "Projects"
            ),
            array
            ("href" => "examination_report_generation", 
             "menuitem" => "Examinations"
            ),            
        );
        
        //echo "<pre>";
        //print_r($subMenuDataMaintenance);
        //echo "</pre>";
        //exit;
        return $this->render
                (
                    'ErwinStudentAdministrationSystemBundle:Default:index.html.twig', 
                    array
                    ("menu" => $menu,
                     "subMenuDataMaintenance" => $subMenuDataMaintenance,
                     "subMenuDataEntry" => $subMenuDataEntry,
                     "subMenuReportGeneration" => $subMenuReportGeneration,
                    )
               );
    }
    
}