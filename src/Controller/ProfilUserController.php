<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilUserController extends AbstractController
{
    /**
     * @Route("/profil", name="profiluser_profil")
     */
    public function Profil(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {


        $user = new Participant();

        $profilForm = $this->createForm(ProfilFormType::class, $user);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted()  && $profilForm->isValid()) {


            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $profilForm->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do something else here

            $this->addFlash('sucess', 'Profil modifié!');

            return $this->redirectToRoute('profiluser_profil');


        }

        return $this->render('profil_user/ProfilUser.html.twig', ["profilForm"=> $profilForm->createView()]);
    }
}
