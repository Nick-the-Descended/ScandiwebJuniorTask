<?php

namespace src\Classes;

use src\Classes\ProductTypes\Book;
use src\Classes\ProductTypes\DVD;
use src\Classes\ProductTypes\Furniture;
use src\Classes\ProductTypes\ProductInterface;

class ProductService
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    /**
     * @throws \Exception
     */
    public function findSku(string $sku): bool
    {
        $query = "SELECT * FROM products WHERE sku = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param('s', $sku);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @throws \Exception
     */
    public function getAllProducts(): array
    {
        $query = "SELECT * FROM products ORDER BY createdOn";
        $result = $this->database->execute($query);
        $products = [];

        if (!$result) {
            return [];
        }

        while ($row = $result->fetch_assoc()) {
            $product = $this->createProductFromRow($row);
            if ($product) {
                $products[] = $product;
            }
        }

        return $products;
    }

    public function isValidSku(string $sku): bool
    {
        $query = "SELECT * from products where sku = ?";
        try {
            $stmt = $this->database->prepare($query);
        } catch (\Exception $e) {

        }
        $stmt->bind_param('s', $sku);
        $stmt->execute();

        return !$stmt->fetch();
    }

    private function createProductFromRow($row): ? ProductInterface
    {
        $productType = $row['product_type'];
        $method = 'create' . $productType;
        if (method_exists($this, $method)) {
            return $this->$method($row);
        }
        return null;
    }

    public function deleteProducts(array $skus): int
    {
        $skuList = implode(',', array_fill(0, count($skus), '?'));

        $query = "DELETE FROM products WHERE sku IN ({$skuList})";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param(str_repeat('s', count($skus)), ...$skus);
        $stmt->execute();

        return $stmt->affected_rows;
    }

    public function createProduct(array $post): bool
    {
        $query = "INSERT INTO products
                    (`sku`, `name`, `price`, `product_type`, `attribute`) 
                    VALUES (?, ?, ?, ?, ?)";
        echo "query ?=  ";
        echo $query;
        $stmt = $this->database->prepare($query);
        $stmt->bind_param('ssdss', $post['sku'], $post['name'], $post['price'], $post['type'], $post['attribute']);
        $result = $stmt->execute();

        if (!$result) {
            throw new \Exception('Failed to create product');
        }

        return true;
    }

    private function addDefaultParameters(ProductInterface $product, $row): ProductInterface
    {
        return $product
            ->setSku($row['sku'])
            ->setName($row['name'])
            ->setPrice($row['price']);
    }

    private function createBook($row): ProductInterface
    {
        $product = new Book();
        $product->setWeight(floatval($row['attribute']));

        return $this->addDefaultParameters($product, $row);
    }

    private function createDVD($row): ProductInterface
    {
        $product = new DVD();
        $product->setSize(floatval($row['attribute']));

        return $this->addDefaultParameters($product, $row);
    }

    private function createFurniture($row): ProductInterface
    {
        $product = new Furniture();
        $product->setDimensions($row['attribute']);

        return $this->addDefaultParameters($product, $row);
    }
}
