<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilUserController extends AbstractController
{
    /**
     * @Route("/profil", name="profiluser_profil")
     */
    public function Profil(): Response
    {

        //TODO aller chercher participants en BDD pour affichage

        return $this->render('ProfilUser.html.twig');
    }
}
