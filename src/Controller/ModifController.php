<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\InscriptionType;

use App\Entity\Utilisateur;


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
}
