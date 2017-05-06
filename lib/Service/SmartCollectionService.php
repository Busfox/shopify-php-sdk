<?php

namespace Shopify\Service;

use GuzzleHttp\Psr7\Request;
use Shopify\Object\SmartCollection;
use Shopify\Options\SmartCollection\GetOptions;
use Shopify\Options\SmartCollection\ListOptions;
use Shopify\Options\SmartCollection\OrderOptions;

class SmartCollectionService extends AbstractService
{
    /**
     * Receive a list of all SmartCollection
     * @link https://help.shopify.com/api/reference/smartcollection#index
     * @param  ListOptions $options
     * @return SmartCollection[]
     */
    public function all(ListOptions $options = null)
    {
        $request = $this->createRequest('/admin/smart_collections.json');
        return $this->getEdge($request, $options, SmartCollection::class);
    }

    /**
     * Receive a count of smart collections
     * @link https://help.shopify.com/api/reference/smartcollection#count
     * @return integer
     */
    public function count()
    {
        $request = $this->createRequest('/admin/smart_collections/count.json');
        return $this->getCount($request);
    }

    /**
     * Receive a single smart collection
     * @link https://help.shopify.com/api/reference/smartcollection#show
     * @param  integer $smartCollectionId
     * @param  GetOptions $options
     * @return SmartCollection
     */
    public function get($smartCollectionId, GetOptions $options = null)
    {
        $request = $this->createRequest('/admin/smart_collections/'.$smartCollectionId.'.json');
        return $this->getNode($request, $options, SmartCollection::class);
    }

    /**
     * Create a new smart collection
     * @link https://help.shopify.com/api/reference/smartcollection#create
     * @param  SmartCollection $smartCollection
     * @return void
     */
    public function create(SmartCollection &$smartCollection)
    {
        $data = $smartCollection->exportData();
        $request = $this->createRequest('/admin/smart_collections.json', static::REQUEST_METHOD_POST);
        $response = $this->send($request, array(
            'smart_collection' => $data
        ));
        $smartCollection->setData($response->smart_collection);
    }

    /**
     * Modify an existing smart collection
     * @link https://help.shopify.com/api/reference/smartcollection#update
     * @param  SmartCollection $smartCollection
     * @return void
     */
    public function update(SmartCollection &$smartCollection)
    {
        $data = $smartCollection->exportData();
        $request = $this->createRequest('/admin/smart_collections/'.$smart_collection->getId()'.json', static::REQUEST_METHOD_PUT);
        $response = $this->send($request, array(
            'smart_collection' => $data
        ));
        $smartCollection->setData($response->smart_collection);
    }

    /**
     * Delete an existing smart_collection
     * @link https://help.shopify.com/api/reference/smartcollection#destroy
     * @param  SmartCollection $smartCollection
     * @return void
     */
    public function delete(SmartCollection $smartCollection)
    {
        $request = $this->createRequest('/admin/smart_collections/'.$smartCollection->getId().'.json', static::REQUEST_METHOD_DELETE);
        $this->send($request);
    }

    /**
     * Set the ordering type or manual order of products
     * @link https://help.shopify.com/api/reference/smartcollection#order
     * @param  integer       $smatCollectionId
     * @param  OrderOptions $options
     * @return void
     */
    public function order($smatCollectionId, OrderOptions $options)
    {

    }
}
