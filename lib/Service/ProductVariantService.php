<?php

namespace Shopify\Service;

use GuzzleHttp\Psr7\Request;
use Shopify\Object\ProductVariant;
use Shopify\Options\ProductVariant\GetOptions;
use Shopify\Options\ProductVariant\ListOptions;

class ProductVariantService extends AbstractService
{
    /**
     * Receive a list of Product Variants
     * @link https://help.shopify.com/api/reference/product_variant#index
     * @param  integer  $productId
     * @param  ListOptions $options
     * @return ProductVariant[]
     */
    public function all($productId, ListOptions $options = null)
    {
        $request = $this->createRequest('/admin/products/'.$productId.'/variants.json');
        return $this->getEdge($request, $options, ProductVariant::class);
    }

    /**
     * Receive a count of all Variant
     * @link https://help.shopify.com/api/reference/product_variant#count
     * @param  integer $productId
     * @return integer
     */
    public function count($productId)
    {
        $request = $this->createRequest('/admin/products/'.$product->getId().'/variants/count.json');
        return $this->getCount($request);
    }

    /**
     * Receive a single product variant
     * @link https://help.shopify.com/api/reference/product_variant#show
     * @param  integer $productVariantId
     * @param  GetOptions $options
     * @return ProductVariant
     */
    public function get($productVariantId, GetOptions $options = null)
    {
        $endpoint = '/admin/variants/'.$productVariantId.'.json';
        $request = $this->createRequest($endpoint);
        return $this->getNode($request, $options, ProductVariant::class);
    }

    /**
     * Create a new product variant
     * @link https://help.shopify.com/api/reference/product_variant#create
     * @param  integer         $productId
     * @param  ProductVariant $productVariant
     * @return void
     */
    public function create($productId, ProductVariant &$productVariant)
    {
        $data = $productVariant->exportData();
        $endpoint = '/admin/products/'.$productId.'/variants.json';
        $request = $this->createRequest($endpoint, static::REQUEST_METHOD_POST);
        $response = $this->send($request, array(
            'variant' => $data
        ));
        $productVariant->setData($response->variant);
    }

    /**
     * Modify an existing product variant
     * @link https://help.shopify.com/api/reference/product_variant#update
     * @param  ProductVariant $productVariant
     * @return void
     */
    public function update(ProductVariant &$productVariant)
    {
        $data = $productVariant->exportData();
        $endpoint = '/admin/variants/'.$productVariant->getId().'.json';
        $request = $this->createRequest($endpoint, static::REQUEST_METHOD_PUT);
        $response = $this->send($request, array(
            'variant' => $data
        ));
        $productVariant->setData($response->variant);
    }

    /**
     * Delete a product variant
     * @link https://help.shopify.com/api/reference/product_variant#destroy
     * @param  integer         $productId
     * @param  ProductVariant $productVariant
     * @return void
     */
    public function delete($productId, ProductVariant &$productVariant)
    {
        $endpoint = '/admin/products/'.$productId.'/variants/'.$productVariant->getId().'.json';
        $request = $this->createRequest($endpoint, static::REQUEST_METHOD_DELETE);
        return $this->send($request);
    }
}
