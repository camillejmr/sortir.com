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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AjoutLieuController extends AbstractController
{
    /**
     * @Route("/ajoutLieu/{idOrganisateur}", name="ajout_lieu")
     */
    public function index(Request $request, entityManagerInterface $entityManager, int $idOrganisateur, ParticipantRepository $organisateurRepository): Response
    {
//        code de Camille : sécurité, on ne peut pas créer de sortie en mettant un autre id que celui de la personne connectée
        $user = $this->getUser();
        $organisateur = $organisateurRepository->findOneBy(['id' => $idOrganisateur]);
        if ($organisateur != $user) {
            throw new NotFoundHttpException("Vous ne pouvez pas créer de lieu avec un autre profil que le votre !");
        }
//        Fin du code de camille
        $Lieu = new Lieu();
        $ajoutLieuForm = $this->createForm(AjoutLieuType::class, $Lieu);
        $ajoutLieuForm->handleRequest($request);
        if ($ajoutLieuForm->isSubmitted() && $ajoutLieuForm->isValid()) {
            $organisateur = $organisateurRepository->findOneBy(['id' => $idOrganisateur]);
            $entityManager->persist($Lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Le lieu a bien été ajouté !');
            return $this->redirectToRoute('creer_Sortie', ['idOrganisateur' => $this->getUser()->getId()]);

        }

        return $this->render('ajout_lieu/ajoutLieu.html.twig', [
            'AjoutLieuForm' => $ajoutLieuForm->createView()
        ]);
    }

}
