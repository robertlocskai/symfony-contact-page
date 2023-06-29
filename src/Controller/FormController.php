<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Message;
use Twig\Environment;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class FormController extends AbstractController
{
    ##[Route('/form', name: 'app_form')]
    #public function index(): Response
    #{
    #    return $this->render('form/index.html.twig', [
    #        'controller_name' => 'FormController',
    #    ]);
    #}

    #[Route('/form', name: 'app_form')]
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager) {

        $msg = new Message();

        $form = $this->createForm(ContactFormType::class, $msg);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($msg);
            $entityManager->flush();

            //return new Response("Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.");
            
            return new Response($twig->render('result.html.twig', [
                'title' => 'Success!',
                'result' => 'Your question was successfully sent!'
            ]));
        
        }
        else if($form->isSubmitted() && !$form->isValid()) {

            return new Response("Hiba! Kérjük töltsd ki az összes mezőt!");

        }

        return new Response($twig->render('index.html.twig', [
            'contactForm' => $form->createView()
        ]));

    }

}
