<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

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
        $doctrine = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();

        if($request->get('id') != null){
            $f = $doctrine->getRepository(Fichier::class)->find($request->get('id'));
            try{
                $filesystem = new Filesystem();
                if($filesystem->exists($this->getParameter('file_directory').'/'.$f->getNom())){
                    $filesystem->remove('symlink',$this->getParameter('file_directory'), $f->getNom());
                }elseif($filesystem->exists($this->getParameter('file_photos').'/'.$f->getNom())){
                    $filesystem->remove('symlink',$this->getParameter('file_photos'), $f->getNom());
                }
            }
            catch(IOExceptionInterface $exception){

            }
            
            
            $em->remove($f);
            $em->flush();
            return $this->redirectToRoute('profil');
        }

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

                if($fichierPhysique->guessExtension() == 'jpg' || $fichierPhysique->guessExtension() == 'png' || $fichierPhysique->guessExtension() == 'jpeg'){
                    $fichierPhysique->move($this->getParameter('file_photos'), $fichier->getNom().$ext);

                    $em = $this->getDoctrine()->getManager();
                        $em->persist($fichier);
                        $em->flush();
                }else{
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
                }

                
                

                return $this->redirectToRoute('profil');
            }
            }
        

        return $this->render('static/profil.html.twig', ['form' => $form->createView()]);
    }
}
