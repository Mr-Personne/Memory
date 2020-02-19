<?php

namespace App\Controller;

use App\Form\SetupWordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/words")
 */
class WordsController extends AbstractController
{
    /**
     * @Route("/", name="words")
     */
    public function index()
    {
        return $this->render('words/index.html.twig', [
            'controller_name' => 'WordsController',
        ]);
    }

    /**
     * @Route("/setup", name="words_setup")
     */
    public function setup(Request $request, SessionInterface $session)
    {
        // print_r($_POST['setup_number']);
        // $time = new Time();
        // $time->setMinutes(5);
        // $time->setSecondes(0);

        $form = $this->createForm(SetupWordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $session->set('numQuantity', $_POST['setup_number']['quantity']);
            $session->set('numMinutes', $_POST['setup_number']['minutes']);
            $session->set('numSecondes', $_POST['setup_number']['secondes']);
            return $this->redirectToRoute('words_memorise');
        }

        return $this->render('words/setup.html.twig', [
            'controller_name' => 'words Setup',
            'form' => $form->createView(),

        ]);
    }
}
