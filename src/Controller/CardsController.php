<?php

namespace App\Controller;

use App\Form\SetupCardType;
use App\Form\RecallCardType;
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
        $cardSuits = ["S", "D", "C", "H"];
        $numberOfCards = 0;
        $currentPack = [];
        $generatedCards = [];
        $test = [];
        //generates all cards by checking if  a card has already been used by checking the currentpack array
        //once the number of cards generated has been reached (52), it changes the pack to use and generate the
        //next set of cards (by doing currentpack = [])
        for ($i = 0; $i < $randCardsLen; $i++) {
            if ($numberOfCards >= 52) {
                $numberOfCards = 0;
                $currentPack = [];
            }
            $randCardNum = rand(1, 13);
            $randCardSuite = rand(0, 3);
            if($randCardNum == 1){
                $randCardNum = "A";
            }
            elseif($randCardNum == 11){
                $randCardNum = "J";
            }
            elseif($randCardNum == 12){
                $randCardNum = "Q";
            }
            elseif($randCardNum == 13){
                $randCardNum = "K";
            }
            $card = $randCardNum."-".$cardSuits[$randCardSuite];

            if (in_array($card, $currentPack)) {
                while (in_array($card, $currentPack)) {
                    $randCardNum = rand(1, 13);
                    $randCardSuite = rand(0, 3);
                    if($randCardNum == 1){
                        $randCardNum = "A";
                    }
                    elseif($randCardNum == 11){
                        $randCardNum = "J";
                    }
                    elseif($randCardNum == 12){
                        $randCardNum = "Q";
                    }
                    elseif($randCardNum == 13){
                        $randCardNum = "K";
                    }
                    $card = $randCardNum."-".$cardSuits[$randCardSuite];
                }

                array_push($generatedCards, $card);
                array_push($currentPack, $card);
                $numberOfCards++;
            }
            else{
                array_push($generatedCards, $card);
                array_push($currentPack, $card);
                $numberOfCards++;
            }
            
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

    /**
     * @Route("/memorise/end", name="cards_memorise_end")
     */
    public function endMemorise(SessionInterface $session)
    {
        return $this->redirectToRoute('cards_recall');
    }

    /**
     * @Route("/recall", name="cards_recall")
     */
    public function recall(Request $request, SessionInterface $session)
    {

        $form = $this->createForm(RecallCardType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('userAnswer', $_POST['recall_card']);
            return $this->redirectToRoute('cards_score');
        }


        return $this->render('cards/recall.html.twig', [
            'controller_name' => 'Cards recall',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/score", name="cards_score")
     */
    public function score(SessionInterface $session)
    {
        $userAnswerSession = $session->get('userAnswer');
        $userAnswer = strtoupper($userAnswerSession['userAnswer']);
        $answer = $session->get('generatedCards');
        $answer = str_replace(",", " ", $answer);
        // $userAnswer = str_replace(" ", "", $userAnswer);
        $userAnswArr = explode(" ", $userAnswer);
        // $answArr = explode(" ", $answer);
        $answArr = $answer;
        // echo "-----------";
        // print_r($userAnswArr);
        // echo ' vs ';
        // print_r($answArr);
        // print_r($answer);

        $score = 0;
        $maxScore = count($answArr);
        // $userAnswArr = str_split($userAnswer);
        // $answArr = str_split($answer);
        $len = count($userAnswArr);
        // print_r($userAnswArr);
        // echo "    " . $len . "  ";
        // print_r($answArr);
        for ($i = 0; $i < $len; $i++) {

            if ($userAnswArr[$i] == $answArr[$i]) {
                $score++;
            }
        }
        // $score = $maxScore - count($answArr);
        //resplits the answers back to two digits with a space between each for user presentation
        // $strAnswer = implode(" ", $answArr);
        // $strUserAnswer = implode(" ", $userAnswArr);
        $strAnswer = implode(" => ",$answArr);
        $strUserAnswer = implode(" => ",$userAnswArr);


        return $this->render('cards/score.html.twig', [
            'controller_name' => 'Cards score',
            'userAnswer' => $userAnswer,
            'answer' => $answer,
            'score' => $score,
            'maxScore' => $maxScore,
            'strUserAnswer' => $strUserAnswer,
            'strAnswer' => $strAnswer,
        ]);
    }
}
