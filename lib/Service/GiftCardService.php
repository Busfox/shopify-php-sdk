<?php

namespace Shopify\Service;

use Shopify\Object\GiftCard;
use Shopify\Options\GiftCard\GetOptions;
use Shopify\Options\GiftCard\ListOptions;
use Shopify\Options\GiftCard\SearchOptions;

class GiftCardService extends AbstractService
{
    /**
     * Receive a list of Gift Cards
     * @link https://help.shopify.com/api/reference/gift_card#index
     * @param  ListOptions $options
     * @return GiftCard[]
     */
    public function all(ListOptions $options = null)
    {
        $endpoint = '/admin/gift_cards.json';
        $request = $this->createRequest($endpoint);
        return $this->getEdge($request, $options, GiftCard::class);
    }

    /**
     * Receive a count of Gift Cards
     * @link https://help.shopify.com/api/reference/gift_card#count
     * @param  CountOptions $options
     * @return integer
     */
    public function count(CountOptions $options = null)
    {
        $endpoint = '/admin/gift_cards/count.json';
        $request = $this->createRequest($endpoint);
        return $this->getCount($request, $options);
    }

    /**
     * Receive a single Gift Card
     * @link https://help.shopify.com/api/reference/gift_card#show
     * @param  integer $giftCardId
     * @param  GetOptions $options
     * @return GiftCard
     */
    public function get($giftCardId, GetOptions $options = null)
    {
        $endpoint = '/admin/gift_cards/'.$giftCardId.'.json';
        $request = $this->createRequest($endpoint);
        return $this->getNode($request, $options, GiftCard::class);
    }

    /**
     * Create a gift card
     * @link https://help.shopify.com/api/reference/gift_card#create
     * @param  GiftCard $giftCard
     * @return void
     */
    public function create(GiftCard &$giftCard)
    {
        $data = $giftCard->exportData();
        $endpoint = '/admin/gift_cards.json';
        $request = $this->createRequest($endpoint, static::REQUEST_METHOD_POST);
        $response = $this->send($request, array(
            'gift_card' => $data
        ));
        $giftCard->setData($response->gift_card);
    }

    /**
     * Update a Gift Card
     * @link https://help.shopify.com/api/reference/gift_card#update
     * @param  GiftCard $giftCard
     * @return void
     */
    public function update(GiftCard &$giftCard)
    {
        $data = $giftCard->exportData();
        $endpoint = '/admin/gift_cards/'.$giftCard->getId().'.json';
        $request = $this->createRequest($endpoint, static::REQUEST_METHOD_PUT);
        $response = $this->send($request, array(
            'gift_card' => $data
        ));
        $giftCard->setData($response->gift_card);
    }

    /**
     * Disable a Gift Card
     * @link https://help.shopify.com/api/reference/gift_card#disable
     * @param  GiftCard $giftCard
     * @return void
     */
    public function disable(GiftCard &$giftCard)
    {
        $data = $giftCard->exportData();
        $endpoint = '/admin/gift_cards/'.$giftCard->getId().'/disable.json';
        $request = $this->createRequest($endpoint, static::REQUEST_METHOD_PUT);
        $response = $this->send($request);
        $giftCard->setData($response->gift_card);
    }

    /**
     * Search for gift cards matching supplied query
     * @link https://help.shopify.com/api/reference/gift_card#search
     * @param  SearchOptions $options
     * @return GiftCard[]
     */
    public function search(SearchOptions $options = null)
    {
        $endpoint = '/admin/gift_cards/search.json';
        $request = $this->createRequest($endpoint);
        return $this->getEdge($request, $options, GiftCard::class);
    }
}
