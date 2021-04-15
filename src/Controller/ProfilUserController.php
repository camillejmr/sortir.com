<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilFormType;
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

        $user = new Participant();
        $profilForm = $this->createForm(ProfilFormType::class, $user);


        return $this->render('profil_user/ProfilUser.html.twig', ["profilForm"=> $profilForm->createView()]);
    }
}
