<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\InscriptionType;
use App\Form\LivredorType;
use App\Form\ContactType;


use App\Entity\Utilisateur;
use App\Entity\Livredor;
use App\Entity\Contact;



class ModifController extends AbstractController
{
    #[Route('/modif-utilisateur/{id}', name: 'modif-utilisateur', requirements: ["id" => "\d+"])]
    public function modifUtilisateur(Request $request, int $id): Response
    {
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
        $form = $this->createForm(InscriptionType::class, $utilisateur);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                return $this->redirectToRoute('liste-utilisateur');
            }
        }

        return $this->render('modif/modif-utilisateur.html.twig', ['form'=>$form->createView()]);
    }

    #[Route('/modif-contact/{id}', name: 'modif-contact', requirements: ["id" => "\d+"])]
    public function modifContact(Request $request, int $id): Response
    {
        $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
        $form = $this->createForm(ContactType::class, $contact);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                return $this->redirectToRoute('liste-contact');
            }
        }

        return $this->render('modif/modif-contact.html.twig', ['form'=>$form->createView()]);
    }
    
    #[Route('/modif-livredor/{id}', name: 'modif-livredor', requirements: ["id" => "\d+"])]
    public function modifLivredor(Request $request, int $id): Response
    {
        $livredor = $this->getDoctrine()->getRepository(Livredor::class)->find($id);
        $form = $this->createForm(LivredorType::class, $livredor);

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($livredor);
                $em->flush();
                return $this->redirectToRoute('liste-livredor');
            }
        }

        return $this->render('modif/modif-livredor.html.twig', ['form'=>$form->createView()]);
    }
}
