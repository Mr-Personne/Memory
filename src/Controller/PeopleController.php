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

            if ($_POST['setup_people']['quantity'] <= 0) {
                $_POST['setup_people']['quantity'] = 3;
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
        $userPersonIndex = $session->get('userPersonIndex');


        $jsonAnswer = $session->get('generatedPeople');
        $answer = json_decode($jsonAnswer, true);
        // print_r($answer);
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
            'userPersonIndex' => $userPersonIndex,
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
        // print_r($session);

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
        $currNum = $session->get('userPersonIndex');

        if (!$jsonAnswer) {
            return $this->redirectToRoute('default');
        }

        $userAnswArr = array();
        for ($i = 1; $i < $currNum; $i++) {
            $currUserAnswer = $session->get('userAnswer' . $i);
            unset($currUserAnswer["_token"]);
            $userAnswArr[$i] = $currUserAnswer;
        }

        // print_r($answer);
        // echo "<br><br>";
        // echo "VS";
        // echo "<br><br>";
        // print_r($userAnswArr);

        $score = 0;
        $maxScore = count($answer) - 1;

        $len = count($answer);
        
        $answersVS = array();
        $goodAnswerIndex = array();
        for ($i = 1; $i < $len; $i++) {
            // print_r($userAnswArr[$i]["firstName"]);
            if (isset($userAnswArr[$i]["firstName"])) {
                $answersVS["person".$i]["firstName"] = $answer[$i][0];
                $answersVS["person".$i]["userFirstName"] = $userAnswArr[$i]["firstName"];
            }
            else {
                $answersVS["person".$i]["firstName"] = "";
                $answersVS["person".$i]["userFirstName"] = "";
            }

            if (isset($userAnswArr[$i]["lastName"])) {
                $answersVS["person".$i]["lastName"] = $answer[$i][1];
                $answersVS["person".$i]["userLastName"] = $userAnswArr[$i]["lastName"];
            }
            else {
                $answersVS["person".$i]["lastName"] = "";
                $answersVS["person".$i]["userLastName"] = "";
            }

            if (isset($userAnswArr[$i]["address"])) {
                $answersVS["person".$i]["address"] = $answer[$i][2];
                $answersVS["person".$i]["userAddress"] = $userAnswArr[$i]["address"];
            }
            else {
                $answersVS["person".$i]["address"] = "";
                $answersVS["person".$i]["userAddress"] = "";
            }

            if (isset($userAnswArr[$i]["town"])) {
                $answersVS["person".$i]["town"] = $answer[$i][3];
                $answersVS["person".$i]["userTown"] = $userAnswArr[$i]["town"];
            }
            else {
                $answersVS["person".$i]["town"] = "";
                $answersVS["person".$i]["userTown"] = "";
            }

            if (isset($userAnswArr[$i]["postalCode"])) {
                $answersVS["person".$i]["postalCode"] = $answer[$i][4];
                $answersVS["person".$i]["userPostalCode"] = $userAnswArr[$i]["postalCode"];
            }
            else {
                $answersVS["person".$i]["postalCode"] = "";
                $answersVS["person".$i]["userPostalCode"] = "";
            }

            if (isset($userAnswArr[$i]["job"])) {
                $answersVS["person".$i]["job"] = $answer[$i][5];
                $answersVS["person".$i]["userJob"] = $userAnswArr[$i]["job"];
            }
            else {
                $answersVS["person".$i]["job"] = "";
                $answersVS["person".$i]["userJob"] = "";
            }

            if (isset($userAnswArr[$i]["age"])) {
                $answersVS["person".$i]["age"] = $answer[$i][6];
                $answersVS["person".$i]["userAge"] = $userAnswArr[$i]["age"];
            }
            else {
                $answersVS["person".$i]["age"] = "";
                $answersVS["person".$i]["userAge"] = "";
            }

            if (isset($answersVS["person".$i])) {
                $answersVS["person".$i]["personIndex"] = $i;
                $answersVS["person".$i]["goodAnswer"] = "no";
                // $answersVS["person".$i] = [
                //     "personIndex" => $i,
                //     "goodAnswer" => "no"
                // ];
            }
            

            // print_r($answersVS);
            if (str_replace(" ", "", strtolower($answersVS["person".$i]["firstName"])) == str_replace(" ", "", strtolower($answersVS["person".$i]["userFirstName"]))
                && str_replace(" ", "", strtolower($answersVS["person".$i]["lastName"])) == str_replace(" ", "", strtolower($answersVS["person".$i]["userLastName"]))
                && str_replace(",", "", str_replace(" ", "", strtolower($answersVS["person".$i]["address"]))) == str_replace(",", "", str_replace(" ", "", strtolower($answersVS["person".$i]["userAddress"])))
                && str_replace(" ", "", strtolower($answersVS["person".$i]["town"])) == strtolower($answersVS["person".$i]["userTown"])
                && str_replace(" ", "", $answersVS["person".$i]["postalCode"]) == str_replace(" ", "", $answersVS["person".$i]["userPostalCode"])
                && str_replace(" ", "", strtolower($answersVS["person".$i]["job"])) == str_replace(" ", "", strtolower($answersVS["person".$i]["userJob"]))
                && str_replace(" ", "", strtolower($answersVS["person".$i]["age"])) == str_replace(" ", "", strtolower($answersVS["person".$i]["userAge"]))
            ) {
                $score++;
                $answersVS["person".$i]["goodAnswer"] = "yes";
            }
        }
        // $score = $maxScore - count($answer);
        // $strAnswer = $answer;
        // $strUserAnswer = $userAnswer;

        // print_r($answersVS);
        return $this->render('people/score.html.twig', [
            'controller_name' => 'People score',
            // 'userAnswer' => $userAnswer,
            'answer' => $answer,
            'score' => $score,
            'maxScore' => $maxScore,
            'userAnswer' => $userAnswArr,
            'answer' => $answer,
            'answersVS' => $answersVS,
        ]);
    }
}
