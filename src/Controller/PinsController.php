<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    public function index(PinRepository $repo): Response
    {
        return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]);
    }

    public function show(int $id, PinRepository $repo): Response
    {
        $pin = $repo->find($id);

        if (! $pin) {
           throw $this->createNotFoundException("Pin #". $id ." Not Found!");
        }

        return $this->render("pins/show.html.twig", ['pin' => $pin]);
    }

    public function form(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;
        $form = $this->createFormBuilder($pin)
            ->add("title", null, ['attr' => ['autofocus' => true]])
            ->add("description", null, ['attr' => ['rows' => 10, 'cols' => 50]])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        //dd($form);

        return $this->render('pins/create.html.twig', [
            'monFormulaire' => $form->createView(),
        ]);
    }

    
}
