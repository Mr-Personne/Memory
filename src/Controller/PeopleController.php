<?php

namespace App\Controller;

use App\Form\SetupPeopleType;
use App\Form\RecallPeopleType;
use App\Form\PeopleCollectionType;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        // $session->clear();
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
            return $this->redirectToRoute('people_memorise');
        }

        return $this->render('people/setup.html.twig', [
            'controller_name' => 'people Setup',
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/memorise", name="people_memorise")
     */
    public function memorise(SessionInterface $session, PersonRepository $PersonRepository)
    {
        $peopleList = $PersonRepository->findAll();
        shuffle($peopleList);
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
        return $this->render('people/memorise.html.twig', [
            'controller_name' => 'people memorise',
            'peopleQuantity' => $session->get('peopleQuantity'),
            'peopleMinutes' => $session->get('peopleMinutes'),
            'peopleSecondes' => $session->get('peopleSecondes'),
            'peopleList' => $peopleList,
        ]);
    }

    /**
     * @Route("/memorise/end", name="people_memorise_end")
     */
    public function endMemorise(SessionInterface $session)
    {
        $session->set('generatedPeople', $_POST['data']);
        // print_r($_POST);
        return $this->redirectToRoute('people_memorise');
        // return $_POST['body'];
    }


    /**
     * @Route("/recall", name="people_recall")
     */
    public function recall(Request $request, SessionInterface $session)
    {
        if ($session->has('userPersonIndex') == false) {
            $session->set('userPersonIndex', 1);
        }
        

        $jsonAnswer = $session->get('generatedPeople');
        $answer = json_decode($jsonAnswer, true);
        print_r($answer);
        $peopleQuantity = intval($session->get('peopleQuantity'));


        $form = $this->createForm(RecallPeopleType::class, null, [
            'peopleFormQuantity' => $peopleQuantity,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currNum = $session->get('userPersonIndex');
            $session->set('userAnswer' . $currNum, $_POST['recall_people']);
            $currNum++;
            $session->set('userPersonIndex', $currNum);
            return $this->redirectToRoute('people_score');
        }

        return $this->render('people/recall.html.twig', [
            'controller_name' => 'People recall',
            'form' => $form->createView(),
            'peopleQuantity' => $peopleQuantity,
        ]);
    }

    /**
     * @Route("/recall/another", name="recall_another_person")
     */
    public function recallAnother(SessionInterface $session)
    {
        //ajax sends form info here on button click "another"
        //session var that contains number of people recalled up until now (starts at one then ++ at the end)
        //adds to session $session->set('generatedPeople' + $currPersonNum, $_POST['data'])
        //then send back form (?) to ajax to resend info
        //when you're finished, click on answer

        // print_r($_POST['recall_people']);
        $currNum = $session->get('userPersonIndex');
        $session->set('userAnswer' . $currNum, $_POST['recall_people']);
        $currNum++;
        $session->set('userPersonIndex', $currNum);
        print_r($session);

        // return $this->redirectToRoute('people_memorise');
        return $this->redirectToRoute('people_recall');
    }

    /**
     * @Route("/score", name="people_score")
     */
    public function score(SessionInterface $session)
    {
        $jsonAnswer = $session->get('generatedPeople');
        $answer = json_decode($jsonAnswer, true);
        $test2 = $session->get('userAnswer0');
        $currNum = $session->get('userPersonIndex');
        $session->set('loltest', "cou cou test");
        print_r($answer);
        echo "<br><br>";
        print_r($test2);
        echo "<br><br>";
        print_r($currNum);

        $userAnswArr = array();
        for ($i=1; $i < $currNum; $i++) { 
            $currUserAnswer = $session->get('userAnswer'.$i);
            $userAnswArr[$i] = $currUserAnswer;
        }

        echo "<br><br>";
        print_r($userAnswArr);
        
        $testlol = $session->get('loltest');
        echo "<br><br>";
        print_r($testlol);
        echo "<br><br>";
        print_r($answer[1]);
        echo "<br>VS<br>";
        print_r($userAnswArr[1]);
        // $userAnswerSession = $session->get('userAnswer');
        // print_r($userAnswerSession);
        // $userAnswer = strtolower($userAnswerSession['userAnswer']);
        // $answer = $session->get('generatedPeople');
        // $answer = str_replace(",", " ", $answer);
        // // $userAnswer = str_replace(" ", "", $userAnswer);
        // $userAnswArr = explode(" ", $userAnswer);
        // $answArr = explode(" ", $answer);
        // echo "-----------";
        // print_r($userAnswArr);
        // echo ' vs ';
        // print_r($answArr);
        // print_r($answer);

        // $score = 0;
        // $maxScore = count($answArr);

        // $len = count($userAnswArr);
        // // print_r($userAnswArr);
        // // echo "    " . $len . "  ";
        // // print_r($answArr);
        // for ($i = 0; $i < $len; $i++) {

        //     if (array_search($userAnswArr[$i], $answArr)) {
        //         //returns position of found word in the answer
        //         $wordIndex = array_search($userAnswArr[$i], $answArr);
        //         //deletes it from the answer array so that it doesnt take in account next time
        //         unset($answArr[$wordIndex]);
        //     } elseif (array_search($userAnswArr[$i], $answArr) == 0 && array_search($userAnswArr[$i], $answArr) !== false) {
        //         //returns position of found word in the answer
        //         $wordIndex = array_search($userAnswArr[$i], $answArr);
        //         //deletes it from the answer array so that it doesnt take in account next time
        //         unset($answArr[$wordIndex]);
        //     }
        // }
        // $score = $maxScore - count($answArr);
        // $strAnswer = $answer;
        // $strUserAnswer = $userAnswer;


        return $this->render('people/score.html.twig', [
            'controller_name' => 'People score',
            // 'userAnswer' => $userAnswer,
            // 'answer' => $answer,
            // 'score' => $score,
            // 'maxScore' => $maxScore,
            // 'strUserAnswer' => $strUserAnswer,
            // 'strAnswer' => $strAnswer,
        ]);
    }
}
