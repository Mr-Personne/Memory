<?php

namespace App\Controller;

use App\Form\SetupPeopleType;
use App\Form\RecallPeopleType;
use App\Form\PeopleCollectionType;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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

        // $form = $this->createForm(RecallPeopleType::class);
        $form = $this->createForm(PeopleCollectionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('userAnswer', $_POST['recall_people']);
            return $this->redirectToRoute('people_score');
        }
        $jsonAnswer = $session->get('generatedPeople');
        $answer = json_decode($jsonAnswer, true);
        print_r($answer);
        $peopleQuantity = intval($session->get('peopleQuantity'));
        // print_r($peopleQuantity);

        return $this->render('people/recall.html.twig', [
            'controller_name' => 'People recall',
            'form' => $form->createView(),
            'peopleQuantity' => $peopleQuantity,
        ]);
    }
}
