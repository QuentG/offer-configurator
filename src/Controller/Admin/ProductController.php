<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Controller\BaseController;
use App\Repository\OptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name: 'products.')]
class ProductController extends BaseController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request)
    {
        $form = $this->createForm(ProductType::class, new Product());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            dd($product);
            return $this->redirectToRoute('task_success');
        }

        return $this->render('admin/products/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}