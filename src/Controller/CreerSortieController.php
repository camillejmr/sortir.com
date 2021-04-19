<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\CreerSortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;


class CreerSortieController extends AbstractController
{
    /**
     * @Route("/creationSortie/{idOrganisateur}", name="creer_Sortie")
     */
    public function CreerSortie(Request $request, entityManagerInterface $entityManager, EtatRepository $etatRepository, int $idOrganisateur, ParticipantRepository $organisateurRepository, CampusRepository $campusRepository): Response
    {
        $sortie = new Sortie();
        $creerSortieForm = $this->createForm(CreerSortieType::class, $sortie);

        $creerSortieForm->handleRequest($request);
        if ($creerSortieForm->isSubmitted() && $creerSortieForm->isValid()) {
            $etat = $etatRepository ->findOneBy(['libelle'=>'Ouverte']);
            $sortie ->setEtats($etat);
            $organisateur = $organisateurRepository -> findOneBy(['id'=>$idOrganisateur]);
            $sortie -> setCampus($organisateur -> getCampus());
            $sortie -> setOrganisateur($organisateur);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a bien été créée');

            return $this->redirectToRoute('main_home');
        }

        return $this->render('Sorties/CreerSortie.html.twig',  ['CreerSortieForm' => $creerSortieForm -> createView()]);

}

}
