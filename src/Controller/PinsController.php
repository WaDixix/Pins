<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class PinsController extends AbstractController
{
    public function index(TranslatorInterface $translator, PinRepository $repo): Response
    {
        $pins = $repo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pins/index.html.twig', ['pins' => $pins]);
    }

    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created!');

            return $this->redirectToRoute('index');
        }

        //dd($form);

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show(int $id, PinRepository $repo): Response
    {
        $pin = $repo->find($id);

        if (! $pin) {
           throw $this->createNotFoundException("Pin #". $id ." Not Found!");
        }

        return $this->render("pins/show.html.twig", ['pin' => $pin]);
    }

    public function edit(int $id, PinRepository $repo, Request $request, EntityManagerInterface $em): Response
    {
        $pin = $repo->find($id);

        if (! $pin) {
           throw $this->createNotFoundException("Pin #". $id ." Not Found!");
        }

        $form = $this->createForm(PinType::class, $pin, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Pin successfully updated!');

            return $this->redirectToRoute('index');
        }

        return $this->render('pins/edit.html.twig', ['form' => $form->createView(), 'pin' => $pin]);
    }

    public function delete(Request $request, int $id, PinRepository $repo, EntityManagerInterface $em): Response
    {
        $pin = $repo->find($id);

        if ($this->isCsrfTokenValid('__pin_deletion', $request->request->get('_token'))) {
            $em->remove($pin);
            $em->flush();

            $this->addFlash('info', 'Pin successfully deleted!');
        }
        
        return $this->redirectToRoute('index');
    }

    
}
