<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Controller\BaseController;
use App\Form\OptionAttributesType;
use App\Repository\OptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/options', name: 'admin.options.')]
class OptionController extends BaseController
{
    public function __construct(
        private OptionRepository $optionRepository
    ) {}

    #[Route('', name: 'index')]
    public function index()
    {
        $options = $this->optionRepository->findAll();
        return $this->render('admin/options/index.html.twig', compact('options'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request)
    {
        // FIXME: Create new OptionType instance with inside possibility to handle multiple AttributeType forms
        $form = $this->createForm(OptionAttributesType::class, new Option());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $option = $form->getData();

            $this->em->persist($option);
            $this->em->flush();

            return $this->redirectToRoute('admin.options.index');
        }

        return $this->render('admin/options/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'update')]
    public function update(Option $option, Request $request)
    {
        $form = $this->createForm(OptionType::class, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $option = $form->getData();

            $this->em->persist($option);

            $this->em->flush();

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/options/update.html.twig', [
            'option' => $option,
            'form' => $form->createView()
        ]);
    }
}