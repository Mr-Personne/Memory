<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function setup()
    {
        return $this->render('numbers/setup.html.twig', [
            'controller_name' => 'Numbers Setup',
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
