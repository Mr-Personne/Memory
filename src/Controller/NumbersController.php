<?php

namespace App\Controller;

use App\Entity\Time;
use App\Form\SetupNumberType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            // $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
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
    public function recall()
    {
        return $this->render('numbers/recall.html.twig', [
            'controller_name' => 'Numbers recall',
        ]);
    }

    /**
     * @Route("/score", name="numbers_score")
     */
    public function score()
    {
        return $this->render('numbers/score.html.twig', [
            'controller_name' => 'Numbers score',
        ]);
    }
}
