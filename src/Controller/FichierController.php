<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

use App\Entity\Theme;
use App\Entity\Fichier;

use App\Form\AjoutFichierType;

class FichierController extends AbstractController
{
    #[Route('/ajout-fichier', name: 'ajout-fichier')]
    public function ajoutFichier(Request $request): Response
    {
        $fichier=new Fichier();
        $form = $this->createForm(AjoutFichierType::class,$fichier);
        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();

        if($request->get('id') != null){
            $f = $doctrine->getRepository(Fichier::class)->find($request->get('id'));
            try{
                $filesystem = new Filesystem();
                if($filesystem->exists($this->getParameter('file_directory').'/'.$f->getNom())){
                    $filesystem->remove('symlink',$this->getParameter('file_directory'), $f->getNom());
                }
            }
            catch(IOExceptionInterface $exception){

            }
            
            
            $em->remove($f);
            $em->flush();
            return $this->redirectToRoute('ajout-fichier');
        }

        $fichiers = $doctrine->getRepository(Fichier::class)->findBy(array(), array('date'=>'DESC'));

        if($request->isMethod('POST')){
            $form->handlerequest($request);
            if($form->isSubmitted() && $form->isValid()){

                //Récupérer une donnée non mappé
                //$idTheme = $form->get('themes')->getData();
                //$idTheme = $this->getDoctrine()->getRepository(Theme::class)->find($idTheme);

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
                

                return $this->redirectToRoute('ajout-fichier');
            }
        }
        return $this->render('fichier/ajout-fichier.html.twig', ['form' => $form->createView(), 'fichiers' => $fichiers]);
    }

    #[Route('/telechargement-fichier/{id}', name: 'telechargement-fichier', requirements: ["id" => "\d+"])]
    public function telechargementFichier(int $id){
        $doctrine = $this->getDoctrine();
        $repoFichier = $doctrine->getRepository(Fichier::class);
        $fichier = $repoFichier->find($id);

        if($fichier == null){
            $this->redirectToRoute('ajout-fichier');
        } else {
            return $this->file($this->getParameter('file_directory').'/'.$fichier->getNom(),$fichier->getChampOriginal());
        }
    }

}
