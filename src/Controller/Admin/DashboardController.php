<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin.')]
class DashboardController extends BaseController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'nbrProducts' => $this->productRepository->count(['parentId' => null, 'type' => Product::PARENT_TYPE])
        ]);
    }
}