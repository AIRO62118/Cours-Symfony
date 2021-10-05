<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


use App\Form\ContactType;
use App\Form\LivredorType;

use App\Entity\Contact;
use App\Entity\Livredor;

class ListeController extends AbstractController
{
    #[Route('/listeContact', name: 'listeContact')]
    public function listeContact(): Response
    {
        $repoContact = $this->getDoctrine()->getRepository(Contact::class);
        $contacts =$repoContact->findBy(array(),array('nom'=>'ASC'));

        return $this->render('liste/listeContact.html.twig', ['contacts'=>$contacts]);
    }

       #[Route('/listelivredor', name: 'listelivredor')]
    public function listelivredor(): Response
    {
        $repolivredor = $this->getDoctrine()->getRepository(Livredor::class);
        $livre =$repolivredor->findBy(array(),array('nom'=>'ASC'));

        return $this->render('liste/listelivredor.html.twig', ['livre'=>$livre]);
    }
}
