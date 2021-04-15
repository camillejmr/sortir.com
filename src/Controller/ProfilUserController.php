<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilUserController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profiluser_profil")
     */
    public function Profil(int $id, Request $request,EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ParticipantRepository $participantRepository): Response
    {
//        $user = $participantRepository->find($id);
        $user = $entityManager->getRepository(Participant::class)->find($id);
        if (!$user) {throw  $this->createNotFoundException('Cet utilisateur est inexistant');}


//        $participant = new Participant();

        $profilForm = $this->createForm(ProfilFormType::class, $user);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted()  && $profilForm->isValid()) {

    $user->setPseudo('Camilletest');
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,

                    $profilForm->get('password')->getData()
                )
            );

//            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do something else here

            $this->addFlash('sucess', 'Profil modifié!');

            return $this->redirectToRoute('main_home');


        }

        return $this->render('profil_user/ProfilUser.html.twig', ["profilForm"=> $profilForm->createView(),'user'=>$user]);
    }
}
