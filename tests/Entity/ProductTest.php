<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use App\Tests\ApiTestCase;
use PHPUnit\Framework\TestCase;

class ProductTest extends ApiTestCase
{
    public function buildProductEntity(): Product
    {
        return (new Product())
            ->setName('ProductName')
            ->setDescription('Some description')
            ->setStock(100)
            ->setPrice(20.00)
            ->setBrand('Brand')
        ;
    }

    public function testValidEntity(): void
    {
        $this->assertHasValidationErrors($this->buildProductEntity());
    }
}
