<?php

namespace App\Controller;

use App\Entity\Time;
use App\Form\SetupNumberType;
use App\Form\RecallNumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function setup(Request $request, SessionInterface $session)
    {
        // print_r($_POST['setup_number']);
        // $time = new Time();
        // $time->setMinutes(5);
        // $time->setSecondes(0);

        $form = $this->createForm(SetupNumberType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //user sets time to 0 or less : makes it 5 mins by default
            if ($_POST['setup_number']['minutes'] <= 0 && $_POST['setup_number']['secondes'] <= 0) {
                $_POST['setup_number']['minutes'] = 5;
                $_POST['setup_number']['secondes'] = 0;
            }

            if ($_POST['setup_number']['quantity'] <= 0) {
                $_POST['setup_number']['quantity'] = 10;
            }
            $session->set('numQuantity', $_POST['setup_number']['quantity']);
            $session->set('numMinutes', $_POST['setup_number']['minutes']);
            $session->set('numSecondes', $_POST['setup_number']['secondes']);
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
    public function memorise(SessionInterface $session)
    {

        // print_r($session->get('attribute-name'));
        $randNumLen = $session->get('numQuantity');
        $setNumbers = 1;
        $generatedNums = "";
        for ($i = 0; $i < $randNumLen; $i++) {
            $randNum = rand(0, 9);
            $generatedNums .= $randNum;
            // console.log(randNum);

            //checks if two numbers has been set, if so, creates an extra space (so we get 18 33 73 10 4...etc)
            if ($setNumbers == 2) {
                $generatedNums .= " ";
                $setNumbers = 1;
            } else {
                $setNumbers++;
            }
        }
        $session->set('generatedNums', $generatedNums);
        return $this->render('numbers/memorise.html.twig', [
            'controller_name' => 'Numbers memorise',
            'numQuantity' => $session->get('numQuantity'),
            'numMinutes' => $session->get('numMinutes'),
            'numSecondes' => $session->get('numSecondes'),
            'generatedNums' => $generatedNums,
        ]);
    }


    /**
     * @Route("/memorise/end", name="numbers_memorise_end")
     */
    public function endMemorise(SessionInterface $session)
    {
        return $this->redirectToRoute('numbers_recall');
    }


    /**
     * @Route("/recall", name="numbers_recall")
     */
    public function recall(Request $request, SessionInterface $session)
    {

        $form = $this->createForm(RecallNumberType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('userAnswer', $_POST['recall_number']);
            return $this->redirectToRoute('numbers_score');
        }


        return $this->render('numbers/recall.html.twig', [
            'controller_name' => 'Numbers recall',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/score", name="numbers_score")
     */
    public function score(SessionInterface $session)
    {
        $userAnswerSession = $session->get('userAnswer');
        $userAnswer = $userAnswerSession['userAnswer'];

        $answer = $session->get('generatedNums');
        // print_r($userAnswer);
        // echo ' vs ';
        // print_r($answer);

        $answer = str_replace(" ", "", $answer);
        $userAnswer = str_replace(" ", "", $userAnswer);
        // echo "-----------";
        // print_r($userAnswer);
        // echo ' vs ';
        // print_r($answer);

        //calculates score v1
        // $score = 0;
        // $maxScore = strlen($answer);
        // for ($i=0; $i < strlen($userAnswer); $i++) { 
        //     if ($userAnswer[$i] == $answer[$i]) {
        //         $score++;
        //     }
        // }


        //calculates score v - array_search
        $score = 0;
        $maxScore = strlen($answer);
        $userAnswArr = str_split($userAnswer);
        $answArr = str_split($answer);
        $len = count($userAnswArr);
        // print_r($userAnswArr);
        // echo "    " . $len . "  ";
        // print_r($answArr);
        for ($i = 0; $i < $len; $i++) {
            // echo ' '.$userAnswArr[$i];
            // echo '    '.array_search('lol', $answArr).' next : <br>';
            // if (array_search('lol', $answArr) == "") {
            //     echo "loool";
            // }
            if (array_search($userAnswArr[$i], $answArr)) {
                // $score++;
                //returns position of found number in the answer
                $numIndex = array_search($userAnswArr[$i], $answArr);
                // echo ' NUMBE INDEX ' . $numIndex. ' £USERARRAY '.$userAnswArr[$i];
                //deletes it from the answer array so that it doesnt take in account next time
                // array_splice($answArr, $numberIndex, 1);
                unset($answArr[$numIndex]);


                // echo "<br><br>";
                // echo implode("", $userAnswArr);
                // print_r($userAnswArr);
                // echo "USER    " . $len . "  ANSWER";
                // echo implode("", $answArr);
                // print_r($answArr);
                // echo "<br>";
            }
            elseif (array_search($userAnswArr[$i], $answArr) == 0 && array_search($userAnswArr[$i], $answArr) !== false) {
                $numIndex = array_search($userAnswArr[$i], $answArr);
                // echo ' NUMBE INDEX ' . $numIndex. ' £USERARRAY '.$userAnswArr[$i];
                //deletes it from the answer array so that it doesnt take in account next time
                // array_splice($answArr, $numberIndex, 1);
                unset($answArr[$numIndex]);
            }
        }
        $score = $maxScore - count($answArr);
        //resplits the answers back to two digits with a space between each for user presentation
        $strAnswer = chunk_split($answer, 2, ' ');
        $strUserAnswer = chunk_split($userAnswer, 2, ' ');


        return $this->render('numbers/score.html.twig', [
            'controller_name' => 'Numbers score',
            'userAnswer' => $userAnswer,
            'answer' => $answer,
            'score' => $score,
            'maxScore' => $maxScore,
            'strUserAnswer' => $strUserAnswer,
            'strAnswer' => $strAnswer,
        ]);
    }
}
