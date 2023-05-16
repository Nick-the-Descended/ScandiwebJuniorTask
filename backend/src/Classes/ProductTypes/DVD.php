<?php

namespace src\Classes\ProductTypes;

use InvalidArgumentException;

class DVD extends AbstractProduct
{
    private int $size;

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): DVD
    {
        if ($size < 0) {
            throw new InvalidArgumentException('DVD size cannot be negative.');
        }

        $this->size = $size;
        return $this;
    }

    public function serialize(): array
    {
        return [
            "sku" => $this->getSku(),
            "name" => $this->getName(),
            "price" => $this->getPrice() . " $",
            "attribute" => "Size: " . $this->getSize() . " MB"
        ];
    }
}
