<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\AjoutThemeType;

use App\Entity\Theme;



class ThemeController extends AbstractController
{
    #[Route('/ajout-theme', name: 'ajout-theme')]
    public function ajoutTheme(Request $request): Response
    {
        $theme=new Theme();
        $form = $this->createForm(AjoutThemeType::class,$theme);

        if($request->isMethod('POST')){
            $form->handlerequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $this->addFlash('notice','Thème ajouté');

                $em = $this->getDoctrine()->getManager();
                $em->persist($theme);
                $em->flush();

                return $this->redirectToRoute('ajout-theme');
            }
        }





        return $this->render('theme/ajout-theme.html.twig', ['form' => $form->createView()]);
    }
}
