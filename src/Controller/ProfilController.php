<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class ProfilController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController


    /**
     * @Route("/profil", name="app_profil")
     */
{
   public function profil() {

       $this->getDoctrine()->getRepository();

       return $this->render('Profil/profil.html.twig');

   }
}
