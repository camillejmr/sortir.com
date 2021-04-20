<?php

namespace App\Controller;


use App\Entity\Lieu;
use App\Form\AjoutLieuType;
use App\Form\CreerSortieType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjoutLieuController extends AbstractController
{
    /**
     * @Route("/ajoutLieu/{idOrganisateur}", name="ajout_lieu")
     */
    public function index(Request $request, entityManagerInterface $entityManager, int $idOrganisateur, ParticipantRepository $organisateurRepository): Response
    {
        $Lieu = new Lieu();
        $ajoutLieuForm = $this->createForm(AjoutLieuType::class, $Lieu);

        $ajoutLieuForm->handleRequest($request);
        if ($ajoutLieuForm->isSubmitted() && $ajoutLieuForm->isValid()) {
            $organisateur = $organisateurRepository -> findOneBy(['id' =>$idOrganisateur]);
            $this->getUser()->getId();
            $entityManager->persist($Lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu a bien été ajouté !');
            return $this->redirectToRoute('creer_Sortie', ['idOrganisateur' =>1]);

        }

            return $this->render('ajout_lieu/ajoutLieu.html.twig', [
                'AjoutLieuForm' => $ajoutLieuForm -> createView()
            ]);
        }

}
