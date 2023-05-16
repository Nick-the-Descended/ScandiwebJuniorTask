<?php

namespace src\Classes;

class Router
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function route(array $request): array
    {
        $response = [
            'status' => 200,
            'headers' => [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => '*',
                'Access-Control-Allow-Headers' => '*',
            ],
            'body' => '💀',
        ];

        $path = strtolower(parse_url($request['uri'], PHP_URL_PATH));
        $method = $request['method'];

        if ($path === '/products/getall' && $method === 'GET') {
            return $this->getAllProducts($request, $response);
        } elseif ($method === 'OPTIONS') {
            return $response;
        } elseif ($path === '/products/create' && $method === 'POST') {
            return $this->createProduct($request, $response);
        } elseif ($path === '/products/delete' && $method === 'DELETE') {
            return $this->deleteProducts($request, $response);
        } else {
            $response['status'] = 404;
            return $response;
        }
    }

    private function getAllProducts(array $request, array $response): array
    {
        $products = $this->productService->getAllProducts();
        $response['body'] = json_encode(array_map(fn($x) => $x->serialize(), $products));

        $response['headers'] = [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ];

        return $response;
    }

    /**
     * @throws \Exception
     */
    private function createProduct(array $request, array $response): array
    {
        $data = json_decode($request['body'], true);
        $resultProduct = $this->productService->createProduct($data);

        $response['status'] = 400;


        if ($resultProduct) {
            $response['status'] = 200;
            $response['body'] = "Product created successfully.";
        } else {
            $response['body'] = "Failed to create product.";
        }

        return $response;
    }

    private function deleteProducts(array $request, array $response): array
    {
        $queryParams = [];
        parse_str(parse_url($request['uri'], PHP_URL_QUERY), $queryParams);

        $response['status'] = 400;

        if (isset($queryParams['skus'])) {
            $skus = json_decode($queryParams['skus'], true);

            if (is_array($skus)) {
                $result = $this->productService->deleteProducts($skus);

                if ($result > 0) {
                    $response['status'] = 204;
                    return $response;
                } else {
                    $response['body'] = "Failed to delete products.";
                }
            } else {
                $response['body'] = "Invalid request. 'skus' parameter is not a valid JSON array.";
            }
        } else {
            $response['body'] = "Invalid request. 'skus' parameter is missing.";
        }

        return $response;
    }
}