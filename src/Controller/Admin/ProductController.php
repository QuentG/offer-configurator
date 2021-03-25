<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Manager\ProductManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\BaseController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name: 'admin.products.')]
class ProductController extends BaseController
{

    public function __construct(
        private ProductManager $productManager,
        private ProductRepository $productRepository
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $configurables = $this->productRepository->findBy(['type' => Product::PARENT_TYPE]);
        return $this->render('admin/products/index.html.twig', compact('configurables'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(ProductType::class, new Product())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setEntityId(random_int(100, 999))
                ->setType('configurable');

            $this->em->persist($product);

            $this->productManager->createVariants($product);

            $this->em->flush();

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/products/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'update')]
    public function update(Product $product, Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(ProductType::class, $product)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/products/update.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'variants' => $this->productRepository->findBy(['parentId' => $product->getEntityId()])
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function delete(Product $product): RedirectResponse
    {
        if ($product->getType() === Product::PARENT_TYPE) {
            $this->productRepository->removeVariants($product);
        }

        $this->em->remove($product);
        $this->em->flush();

        return $this->redirectToRoute('admin.products.index');
    }
}