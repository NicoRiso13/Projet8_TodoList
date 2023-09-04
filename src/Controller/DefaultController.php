<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Gérer l'affichage de la page d'accueil.
     *
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}
