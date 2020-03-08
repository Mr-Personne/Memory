<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/people")
 */
class PeopleController extends AbstractController
{
    /**
     * @Route("/", name="people")
     */
    public function index()
    {
        return $this->render('people/index.html.twig', [
            'controller_name' => 'PeopleController',
        ]);
    }

    /**
     * @Route("/setup", name="people_setup")
     */
    public function setup(Request $request, SessionInterface $session)
    {

        $form = $this->createForm(SetupPeopleType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //user sets time to 0 or less : makes it 5 mins by default
            if ($_POST['setup_people']['minutes'] <= 0 && $_POST['setup_people']['secondes'] <= 0) {
                $_POST['setup_people']['minutes'] = 5;
                $_POST['setup_people']['secondes'] = 0;
            }
            $session->set('peopleQuantity', $_POST['setup_people']['quantity']);
            $session->set('peopleMinutes', $_POST['setup_people']['minutes']);
            $session->set('peopleSecondes', $_POST['setup_people']['secondes']);
            return $this->redirectToRoute('peoples_memorise');
        }

        return $this->render('peoples/setup.html.twig', [
            'controller_name' => 'peoples Setup',
            'form' => $form->createView(),

        ]);
    }
}
