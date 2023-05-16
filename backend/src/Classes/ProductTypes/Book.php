<?php

namespace src\Classes\ProductTypes;

use InvalidArgumentException;

class Book extends AbstractProduct
{
    private float $weight;

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): Book
    {
        if ($weight < 0) {
            throw new InvalidArgumentException('Book weight cannot be negative.');
        }

        $this->weight = $weight;
        return $this;
    }

    public function serialize(): array
    {
        return [
            "sku" => $this->getSku(),
            "name" => $this->getName(),
            "price" => $this->getPrice() . " $",
            "attribute" => "Weight: " . $this->getWeight() . " KG"
        ];
    }
}
