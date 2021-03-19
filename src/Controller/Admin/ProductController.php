<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name: 'products.')]
class ProductController extends BaseController
{
    #[Route('/create', name: 'create')]
    public function create()
    {
        return $this->render('admin/products/create.html.twig');
    }
}