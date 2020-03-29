<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(SessionInterface $session)
    {
        $session->clear();
        $package = new Package(new EmptyVersionStrategy());

        // Absolute path
        // echo $package->getUrl('/images/memory-logo-title.svg');
        $test1 = $package->getUrl('/images/memory-logo-title.svg');
        // result: /memory-logo-title.svg
        echo $_SERVER['REQUEST_URI'];
        // Relative path
        // echo $package->getUrl('/images/memory-logo-title.png');
        $test2 = $package->getUrl('/images/memory-logo-title.png');
        // result: memory-logo-title.png
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'test1' => $test1,
            'test2' => $test2,
        ]);
    }
}
