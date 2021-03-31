<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cart', name: 'cart.')]
class OrderController extends BaseController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private OrderItemRepository $orderItemRepository
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $order = $this->orderRepository->findAll()[0];
        $cart = [];

        foreach ($order->getItems() as $item) {
            $cart['products'][] = $this->serializer->serialize($item, 'json', [
                'groups' => 'order.read'
            ]);
        }

        $cart['total'] = $order->getTotal();

        return $this->respond('cart', $cart);
    }

//    #[Route('/count', name: 'count', methods: ['GET'])]
//    public function count(): JsonResponse
//    {
//        return $this->respond('cart_total_items', [
//            'total' => $this->orderItemRepository->count([])
//        ]);
//    }

    #[Route('/add', name: 'add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (null === $data) {
            return $this->respondWithError('incorrect_json');
        }

        if (!array_key_exists('id', $data) || !array_key_exists('quantity', $data)) {
            return $this->respondWithError('invalid_keys');
        }

        $orders = $this->orderRepository->findAll();

        if ([] === $orders) {
            $order = new Order();
        } else {
            $order = $orders[0];
        }

        if (!$product = $this->productRepository->find($data['id'])) {
            return $this->respondWithError('product_not_found');
        }

        $item = new OrderItem();
        $item->setProduct($product)
            ->setQuantity($data['quantity']);

        $this->em->persist($item);

        $order->addItem($item);

        $this->em->persist($order);
        $this->em->flush();

        return $this->respond('cart_updated');
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $item = $this->orderItemRepository->findOneBy(['product' => $product]);
        if (null === $item) {
            return $this->respondWithError('item_not_found');
        }

        $this->em->remove($item);
        $this->em->flush();

        return $this->respond('item_removed');
    }
}