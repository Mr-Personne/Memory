<?php

namespace App\Controller;

use App\Form\SetupCardType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cards")
 */
class CardsController extends AbstractController
{
    /**
     * @Route("/", name="cards")
     */
    public function index()
    {
        return $this->render('cards/index.html.twig', [
            'controller_name' => 'CardsController',
        ]);
    }

    /**
     * @Route("/setup", name="cards_setup")
     */
    public function setup(Request $request, SessionInterface $session)
    {
        
        $form = $this->createForm(SetupCardType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //user sets time to 0 or less : makes it 5 mins by default
            if ($_POST['setup_card']['minutes'] <= 0 && $_POST['setup_card']['secondes'] <= 0) {
                $_POST['setup_card']['minutes'] = 5;
                $_POST['setup_card']['secondes'] = 0;
            }
            $session->set('cardQuantity', $_POST['setup_card']['quantity']);
            $session->set('cardMinutes', $_POST['setup_card']['minutes']);
            $session->set('cardSecondes', $_POST['setup_card']['secondes']);
            return $this->redirectToRoute('cards_memorise');
        }

        return $this->render('cards/setup.html.twig', [
            'controller_name' => 'cards Setup',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/memorise", name="cards_memorise")
     */
    public function memorise(SessionInterface $session)
    {

        // print_r($session->get('attribute-name'));
        $randCardsLen = $session->get('cardQuantity');
        $cardSuits = ["A", "D", "C", "H"];
        $setNumbers = 1;
        $generatedCards = [];
        for ($i = 0; $i < $randCardsLen; $i++) {
            $randCardNum = rand(1, 13);
            $randCardSuite = rand(0, 13);
            // $generatedCards .= $randNum;
            array_push($generatedCards, $randCardNum."-".$cardSuits[$randCardSuite]);
        }
        $session->set('generatedCards', $generatedCards);
        return $this->render('cards/memorise.html.twig', [
            'controller_name' => 'Cards memorise',
            'cardQuantity' => $session->get('cardQuantity'),
            'cardMinutes' => $session->get('cardMinutes'),
            'cardSecondes' => $session->get('cardSecondes'),
            'generatedCards' => $generatedCards,
        ]);
    }
}
