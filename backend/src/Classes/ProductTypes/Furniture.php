<?php

namespace src\Classes\ProductTypes;

class Furniture extends AbstractProduct
{
    private string $dimensions;

    public function getDimensions(): string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): Furniture
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    public function serialize(): array
    {
        return [
            "sku" => $this->getSku(),
            "name" => $this->getName(),
            "price" => $this->getPrice() . " $",
            "attribute" => "Dimensions: " . $this->getDimensions()
        ];
    }
}
