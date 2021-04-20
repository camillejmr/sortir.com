<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\AnnulationSortieType;
use App\Form\SearchForm;
use App\Repository\SortieRepository;
use App\Services\EtatsUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main_home")
     */
    public function home(
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        EtatsUpdater $etatsUpdater // Service pour mettre à jour les états
    )
    {
        $sortie = new Sortie();
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
//       dd($data);
        $sorties = $sortieRepository->findSearch($data, $this->getUser());
        $instance = $entityManager->getRepository(Sortie::class)->findall();


        //Je récupère mes sorties liées à une recherche grace à findSearch(), et lui envoie les données

        $etatsUpdater->miseAJourEtatSortie($sortie);

        return $this->render('main/home.html.twig', [
            'sorties' => $sorties, // On envoie nos sorties à la vue
            'form' => $form->createView() // On envoie le formulaire à la vue
        ]);


    }

    /**
     * @Route("/inscriptionSortie/{idSortie}/{idParticipant}", name="inscription_sortie")
     */
    public function inscriptionSortie(int $idSortie, int $idParticipant, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request)
    {


        $sortie = $entityManager->getRepository(Sortie::class)->find($idSortie);
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['id' => $idParticipant]);
        return $this->render('main/inscriptionSortie.html.twig', [
            'sortie' => $sortie, // On envoie notre sortie à la vue
            'participant' => $participant

        ]);
    }

    /**
     * @Route("/validationInscription/{idSortie}/{idParticipant}", name="validation_inscription")
     */
    public function validationInscription(int $idSortie, int $idParticipant, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($idSortie);
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['id' => $idParticipant]);

        $sortie->addParticipant($participant);
        $entityManager->flush();
        $this->addFlash('success', 'Vous êtes bien inscrit(e) à la sortie ' . $sortie->getNom() . '.');

        return $this->redirectToRoute('main_home');

    }

    /**
     * @Route("/annulationSortie/{idSortie}", name="annulation_sortie")
     */
    public function annulationSortie(int $idSortie, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request)
    {$user=$this->getUser();

        $sortie = $entityManager->getRepository(Sortie::class)->find($idSortie);
        $lieuSortie = $entityManager->getRepository(Lieu::class)->findoneBy(['id' => $sortie->getLieux()]);
        $villeSortie = $entityManager->getRepository(Ville::class)->findoneBy(['id' => $lieuSortie->getVilles()]);
        $form = $this->createForm(AnnulationSortieType::class);
        $form->handleRequest($request);
        if($sortie->getOrganisateur()!=$user){throw new NotFoundHttpException("Vous n'êtes pas l'organisateur de cette sortie !");
        }
        if ($form->isSubmitted()) {
            $etat=$entityManager->getRepository(Etat::class)->findOneBy(['libelle'=>'Annulée']);
            $sortie->setEtats($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie ' . $sortie->getNom() . ' a bien été annulée.');

            return $this->redirectToRoute('main_home');
        }
        return $this->render('main/annulationSortie.html.twig', [
            'sortie' => $sortie, // On envoie notre sortie à la vue
            'lieuSortie' => $lieuSortie,
            'villeSortie' => $villeSortie,
            'form' => $form->createView()
        ]);
    }


   

    /**
     * @Route("/desisterInscription/{idSortie}/{idParticipant}", name="desister_inscription")
     */
    public function desisterInscription(int $idSortie, int $idParticipant, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request)
    {

        $sortie = $entityManager->getRepository(Sortie::class)->find($idSortie);
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['id' => $idParticipant]);
        return $this->render('main/desisterSortie.html.twig', [
            'sortie' => $sortie, // On envoie notre sortie à la vue
            'participant' => $participant

        ]);
    }

    /**
     * @Route("/desistementInscription/{idSortie}/{idParticipant}", name="desistement_inscription")
     */
    public function desistementInscription(int $idSortie, int $idParticipant, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($idSortie);
        $participant = $entityManager->getRepository(Participant::class)->findOneBy(['id' => $idParticipant]);

        $sortie->removeParticipant($participant);
        $entityManager->flush();
        $this->addFlash('success', 'Vous êtes bien désinscrit(e) de la sortie ' . $sortie->getNom() . '.');

        return $this->redirectToRoute('main_home');

    }


}
