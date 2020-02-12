<?php

namespace App\Controller;

use App\Entity\Time;
use App\Form\SetupNumberType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/numbers")
 */
class NumbersController extends AbstractController
{
    /**
     * @Route("/", name="numbers")
     */
    public function index()
    {
        return $this->render('numbers/index.html.twig', [
            'controller_name' => 'NumbersController',
        ]);
    }

    /**
     * @Route("/setup", name="numbers_setup")
     */
    public function setup(Request $request)
    {
        
        // $time = new Time();
        // $time->setMinutes(5);
        // $time->setSecondes(0);

        $form = $this->createForm(SetupNumberType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            // $task = $form->getData();
    
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
    
            return $this->redirectToRoute('numbers_memorise');
        }

        return $this->render('numbers/setup.html.twig', [
            'controller_name' => 'Numbers Setup',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/memorise", name="numbers_memorise")
     */
    public function memorise()
    {
        return $this->render('numbers/memorise.html.twig', [
            'controller_name' => 'Numbers memorise',
        ]);
    }

    /**
     * @Route("/recall", name="numbers_recall")
     */
    public function recall()
    {
        return $this->render('numbers/recall.html.twig', [
            'controller_name' => 'Numbers recall',
        ]);
    }

    /**
     * @Route("/score", name="numbers_score")
     */
    public function score()
    {
        return $this->render('numbers/score.html.twig', [
            'controller_name' => 'Numbers score',
        ]);
    }
}
