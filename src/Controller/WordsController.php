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
        return $this->redirectToRoute('words_setup');
    }


    /**
     * @Route("/recall", name="words_recall")
     */
    public function recall(Request $request, SessionInterface $session)
    {

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


}
