<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NumbersController extends AbstractController
{
    /**
     * @Route("/numbers", name="numbers")
     */
    public function index()
    {
        return $this->render('numbers/index.html.twig', [
            'controller_name' => 'NumbersController',
        ]);
    }
}
