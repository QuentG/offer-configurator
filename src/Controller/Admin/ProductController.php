<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Manager\ProductManager;
use Symfony\Component\Uid\Uuid;
use App\Controller\BaseController;
use App\Repository\OptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name: 'products.')]
class ProductController extends BaseController
{

    public function __construct(
        private ProductManager $productManager
    ) {}

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
}