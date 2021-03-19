<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin.')]
class DashboardController extends BaseController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}