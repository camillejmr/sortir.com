<?php


namespace App\Controller;


use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SearchForm;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="main_home")
     */
    public function home(SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $sortie = new Sortie();
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $instance = $entityManager->getRepository(Sortie::class)->findall();

        $form->handleRequest($request);

        //Je récupère mes sorties liées à une recherche grace à findSearch(), et lui envoie les données
        $sorties = $sortieRepository->findSearch($data);
        return $this->render('main/home.html.twig', [
            'sorties' => $sorties, // On envoie nos sorties à la vue
            'form' => $form->createView() // On envoie le formulaire à la vue
        ]);
    }
}
