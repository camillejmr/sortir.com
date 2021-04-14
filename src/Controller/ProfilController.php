<?php


namespace App\Controller;


class ProfilController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController


    /**
     * @Route("/profil", name="app_profil")
     */
{
   public function profil() {


       return $this->render('Profil/profil.html.twig');

   }
}