<?php 

namespace App\Controller\Admin;

use App\Entity\Attribute;
use App\Form\AttributeType;
use App\Controller\BaseController;
use App\Repository\AttributeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/attributes', name: 'admin.attributes.')]
class AttributeController extends BaseController
{
    public function __construct(
        private AttributeRepository $attributeRepository
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $attributes = $this->attributeRepository->findAll();
        return $this->render('admin/attributes/index.html.twig', compact('attributes'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): RedirectResponse|Response
    {
        $attribute = new Attribute();
        $form = $this->createForm(AttributeType::class, $attribute)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($attribute);
            $this->em->flush();

            return $this->redirectToRoute('admin.attributes.index');
        }

        return $this->render('admin/attributes/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'update')]
    public function update(Option $option, Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(OptionType::class, $option);
        
        $form->handleRequest($request);

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