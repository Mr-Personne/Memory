<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tips")
 */
class TipsController extends AbstractController
{
    /**
     * @Route("/", name="tips")
     */
    public function index()
    {
        return $this->render('tips/index.html.twig', [
            'controller_name' => 'TipsController',
        ]);
    }
}
