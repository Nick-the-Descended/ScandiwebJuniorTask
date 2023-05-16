<?php

namespace src\Classes\ProductTypes;

abstract class AbstractProduct implements ProductInterface
{
    private string $sku;
    private string $name;
    private float $price;


    public function getSku(): string
    {
        return $this->sku;
    }


    public function setSku(string $sku): AbstractProduct
    {
        $this->sku = $sku;
        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): AbstractProduct
    {
        $this->name = $name;
        return $this;
    }


    public function getPrice(): float
    {
        return $this->price;
    }


    public function setPrice(float $price): AbstractProduct
    {
        $this->price = $price;
        return $this;
    }

}
