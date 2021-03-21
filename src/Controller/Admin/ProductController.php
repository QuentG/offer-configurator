<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Manager\ProductManager;
use Symfony\Component\Uid\Uuid;
use App\Controller\BaseController;
use App\Repository\OptionRepository;
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
    public function index()
    {
        $configurables = $this->productRepository->findBy(['type' => Product::PARENT_TYPE]);
        return $this->render('admin/products/index.html.twig', compact('configurables'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request)
    {
        $form = $this->createForm(ProductType::class, new Product());

        $form->handleRequest($request);

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
    public function update(Product $product, Request $request)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $this->em->persist($product);

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
    public function delete(Product $product)
    {
        if ($product->getType() === Product::PARENT_TYPE) {
            $this->productRepository->removeVariants($product);
        }

        $this->em->remove($product);
        $this->em->flush();

        return $this->redirectToRoute('admin.products.index');
    }
}