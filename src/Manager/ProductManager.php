<?php 

namespace App\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager 
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}
    
    public function createVariants(Product $product): void
    {
        foreach ($product->getOptions() as $option) {
            foreach ($option->getAttributes() as $attribute) {
                $variant = new Product();
                $variant->setName($product->getName() . '-' . $attribute->getLabel())
                    ->setDescription($product->getDescription())
                    ->setStock($product->getStock())
                    ->setPrice($product->getPrice() + $attribute->getPrice())
                    ->setBrand($product->getBrand())
                    ->setType(Product::CHILD_TYPE)
                    ->setEntityId(random_int(100, 999))
                    ->setParentId($product->getEntityId())
                ;
                $this->em->persist($variant);
            }
        }
    }
}