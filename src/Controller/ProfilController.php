<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


use App\Entity\User;
use App\Entity\Fichier;
//use App\Entity\Theme;

use App\Form\ProfilType;


class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function profil(Request $request): Response
    {
        $user = new User();
        $fichier = new Fichier();
        $form = $this->createForm(ProfilType::class, $fichier);

        if($request->isMethod('POST')){
            $form->handlerequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $fichier->setUtilisateur($this->getUser()->getUtilisateur());

                //Récupération du fichier
                $fichierPhysique = $fichier->getNom();
                
                $fichier->setDate(new \DateTime());
                $ext = '';
                
                //Je protège mon guessExtension qui renvoie null
                if($fichierPhysique->guessExtension() != null){
                    $ext = $fichierPhysique->guessExtension();
                }
                $fichier->setExtension($ext);
                $fichier->setTaille($fichierPhysique->getSize());
                $fichier->setChampOriginal($fichierPhysique->getClientOriginalName());
                $fichier->setNom(md5(uniqid()));
                //$fichier->addTheme($idTheme);
                try{
                    $fichierPhysique->move($this->getParameter('file_directory'), $fichier->getNom());
                    $this->addFlash('notice','Fichier envoyé');
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($fichier);
                    $em->flush();
                }
                catch(\FileException $e){
                    $this->addFlash('notice','Problème d\'envoi');

                }
                

                return $this->redirectToRoute('profil');
            }
            }
        

        return $this->render('static/profil.html.twig', ['form' => $form->createView()]);
    }
}
