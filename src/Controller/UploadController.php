<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    { $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $file = $upload->getName();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            //modif base de données
            $upload->setName($fileName);

            $entityManager->persist($upload);
            $entityManager->flush();

            $this->addFlash('success', 'Fichier téléchargé!');
            /*return $this->redirectToRoute('upload');*/
            $user=$this->getUser();
            $id=$user->getID();
            return $this->redirectToRoute('profiluser_profil',['id'=>$id]);

        }




        return $this->render('upload/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
