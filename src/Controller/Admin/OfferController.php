<?php 

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Controller\BaseController;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/offers', name: 'admin.offers.')]
class OfferController extends BaseController
{
    public function __construct(
        private OfferRepository $offerRepository
    ) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $offers = $this->offerRepository->findAll();
        return $this->render('admin/offers/index.html.twig', compact('offers'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): RedirectResponse|Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($offer);
            $this->em->flush();

            return $this->redirectToRoute('admin.offers.index');
        }

        return $this->render('admin/offers/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'update')]
    public function update(Offer $offer, Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('admin.index');
        }

        return $this->render('admin/offers/update.html.twig', [
            'offer' => $offer,
            'form' => $form->createView()
        ]);
    }
}