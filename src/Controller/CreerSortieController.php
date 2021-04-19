<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreerSortieType;
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
     * @Route("/creationSortie", name="creer_Sortie")
     */
    public function CreerSortie(Request $request, entityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $creerSortieForm = $this->createForm(CreerSortieType::class, $sortie);

        $creerSortieForm->handleRequest($request);
        if ($creerSortieForm->isSubmitted() && $creerSortieForm->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
        }
        return $this->render('Sorties/CreerSortie.html.twig',  ['CreerSortieForm' => $creerSortieForm -> createView()]);

}
    /*public function sortie (SortieRepository $sortieRepository): Response
    {

        return $this->render('Sorties/CreerSortie.html.twig', [

        ]);*/



}
