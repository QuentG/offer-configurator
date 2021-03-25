<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Controller\BaseController;
use App\Form\OptionAttributesType;
use App\Repository\OptionRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/options', name: 'admin.options.')]
class OptionController extends BaseController
{
    public function __construct(
        private OptionRepository $optionRepository
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $options = $this->optionRepository->findAll();
        return $this->render('admin/options/index.html.twig', compact('options'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): RedirectResponse|Response
    {
        $option = new Option();
        $form = $this->createForm(OptionAttributesType::class, $option)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($option);
            $this->em->flush();

            return $this->redirectToRoute('admin.options.index');
        }

        return $this->render('admin/options/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'update')]
    public function update(Option $option, Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(OptionType::class, $option)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/options/update.html.twig', [
            'option' => $option,
            'form' => $form->createView()
        ]);
    }
}