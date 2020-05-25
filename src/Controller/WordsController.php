<?php

namespace App\Controller;

use App\Form\SetupWordType;
use App\Form\RecallWordType;
use App\Repository\WordRepository;
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

        $form = $this->createForm(SetupWordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //user sets time to 0 or less : makes it 5 mins by default
            if ($_POST['setup_word']['minutes'] <= 0 && $_POST['setup_word']['secondes'] <= 0) {
                $_POST['setup_word']['minutes'] = 5;
                $_POST['setup_word']['secondes'] = 0;
            }

            if ($_POST['setup_word']['quantity'] <= 0 || $_POST['setup_word']['quantity'] > 2000) {
                $_POST['setup_word']['quantity'] = 10;
            }
            $session->set('wordQuantity', $_POST['setup_word']['quantity']);
            $session->set('wordMinutes', $_POST['setup_word']['minutes']);
            $session->set('wordSecondes', $_POST['setup_word']['secondes']);
            return $this->redirectToRoute('words_memorise');
        }

        return $this->render('words/setup.html.twig', [
            'controller_name' => 'words Setup',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/memorise", name="words_memorise")
     */
    public function memorise(SessionInterface $session, WordRepository $wordRepository)
    {
        $wordQuantity = $session->get('wordQuantity');
        if ($wordQuantity == "") {
            // echo 'no GENERATED';
            return $this->redirectToRoute('words_setup');
        }

        $wordsList = $wordRepository->findAll();
        shuffle($wordsList);
        // print_r($wordsList);
        // $randWordLen = $session->get('wordQuantity');
        // // $setWords = 1;
        // $wordsArray = array();
        // $wordsDisplay = "";
        // for ($i = 0; $i < $randWordLen; $i++) {
        //     $randIndex = rand(0, 500);
        //     array_push($wordsArray, $wordsList[$randIndex]);
        //     $wordsDisplay .= $wordsList[$randIndex] . " ";

        // }
        // $session->set('generatedNums', $generatedNums);
        return $this->render('words/memorise.html.twig', [
            'controller_name' => 'words memorise',
            'wordQuantity' => $session->get('wordQuantity'),
            'wordMinutes' => $session->get('wordMinutes'),
            'wordSecondes' => $session->get('wordSecondes'),
            'wordsList' => $wordsList,
        ]);
    }



    /**
     * @Route("/memorise/end", name="words_memorise_end")
     */
    public function endMemorise(SessionInterface $session)
    {
        $session->set('generatedWords', $_POST['data']);
        // print_r($_POST);
        return $this->redirectToRoute('words_memorise');
        // return $_POST['body'];
    }


    /**
     * @Route("/recall", name="words_recall")
     */
    public function recall(Request $request, SessionInterface $session)
    {
        $answer = $session->get('generatedWords');
        if ($answer == "") {
            // echo 'no GENERATED';
            return $this->redirectToRoute('words_setup');
        }

        $form = $this->createForm(RecallWordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('userAnswer', $_POST['recall_word']);
            return $this->redirectToRoute('words_score');
        }


        return $this->render('words/recall.html.twig', [
            'controller_name' => 'Words recall',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/score", name="words_score")
     */
    public function score(SessionInterface $session)
    {
        $userAnswerSession = $session->get('userAnswer');
        $userAnswer = strtolower($userAnswerSession['userAnswer']);
        $answer = $session->get('generatedWords');

        if ($answer == "") {
            // echo 'no GENERATED';
            return $this->redirectToRoute('words_setup');
        }

        $answer = str_replace(",", " ", $answer);
        // $userAnswer = str_replace(" ", "", $userAnswer);
        $userAnswArr = explode(" ", $userAnswer);
        $answArr = explode(" ", $answer);
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

            if (array_search($userAnswArr[$i], $answArr)) {
                //returns position of found word in the answer
                $wordIndex = array_search($userAnswArr[$i], $answArr);
                // echo gettype($wordIndex), "\n";
                // echo '----- word  INDEX ' . $wordIndex . ' $USERARRAY ' . $userAnswArr[$i];
                //deletes it from the answer array so that it doesnt take in account next time
                unset($answArr[$wordIndex]);
            } elseif (array_search($userAnswArr[$i], $answArr) == 0 && array_search($userAnswArr[$i], $answArr) !== false) {
                //returns position of found word in the answer
                $wordIndex = array_search($userAnswArr[$i], $answArr);
                // echo gettype($wordIndex), "\n";
                // echo '----- word  INDEX ' . $wordIndex. ' $USERARRAY '.$userAnswArr[$i];
                //deletes it from the answer array so that it doesnt take in account next time
                unset($answArr[$wordIndex]);
            }
        }
        $score = $maxScore - count($answArr);
        //resplits the answers back to two digits with a space between each for user presentation
        // $strAnswer = implode(" ", $answArr);
        // $strUserAnswer = implode(" ", $userAnswArr);
        $strAnswer = $answer;
        $strUserAnswer = $userAnswer;


        return $this->render('words/score.html.twig', [
            'controller_name' => 'Words score',
            'userAnswer' => $userAnswer,
            'answer' => $answer,
            'score' => $score,
            'maxScore' => $maxScore,
            'strUserAnswer' => $strUserAnswer,
            'strAnswer' => $strAnswer,
        ]);
    }
}
