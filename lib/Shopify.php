<?php

/**
 * Core handler for the Shopify API
 *
 * @author Robert Wittman <bugattiboi1k1@gmail.com>
 * @license MIT
 * @link https://help.shopify.com/api/reference
 */

namespace Shopify;

use GuzzleHttp\Client;

use Shopify\Exception;
use Shopify\Util;

class Shopify
{
    /**
     * Singleton instance of the SDK
     * @var Shopify
     */
    protected static $instance = NULL;

    /**
     * Handle for the HTTP Client
     * @var Client
     */
    protected $client;

    /**
     * Shopify APP Key
     * @var string
     */
    public static $api_key;

    /**
     * Shopify APP Secret
     * @var string
     */
    public static $api_secret;

    /**
     * URL to redirect after Authentication
     * @var string
     */
    public static $redirect_uri;

    /**
     * Comma separated string of application permissions
     * @var string
     */
    public static $permissions = 'read_products';

    /**
     * @var array
     */
    public static $shop_data = array();

    const API_KEY_ENV_NAME = 'SHOPIFY_API_KEY';

    const API_SECRET_ENV_NAME = 'SHOPIFY_API_SECRET';

    /**
     * Instantiate our API
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set our API options
     * @param  array  $opts
     * @return Shopify
     */
    public static function init($appId, $appSecret, array $shopData)
    {
        $api = new static($client, $shopData);
        static::setInstance($api);
        return $api;
    }

    /**
     * Fetch the instance of our API
     * @return \Shopify\Shopify
     */
    public static function instance()
    {
        if(is_null(static::$instance))
        {
            throw new \Shopify\Exception\ApiException("SDK has not been intialized. Start with \Shopify\Shopify::init()");
        }
        return static::$instance;
    }

    /**
     * Set our API instance
     * @param \Shopify\Shopify $instance
     */
    public static function setInstance(\Shopify\Shopify $instance)
    {
        static::$instance = $instance;
    }

    /**
     * Make a call to our client
     * @param  url $path
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public static function call($path, $method = 'GET', $params)
    {
        $path = self::baseUrl().$path.'.json';
        $req = new \Shopify\Http\Request($path, $method, $params, [
            'X-Shopify-Access-Token' => self::access_token(),
            'Content-Type' => 'application/json'
        ]);
        $data = self::instance()->getClient()->request($req);
        return $data->getJsonBody();
    }

    /**
     * Fetch our client handler
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set options for our API SDK
     * @param string|array $key
     * @param mixed $value
     */
    public static function setOpt($key, $value = NULL)
    {
        if(is_array($key))
        {
            foreach($key as $k => $v)
            {
                self::setOpt($k, $v);
            }
        } else {
            if(!property_exists(__CLASS__, $key))
            {
                return;
            }
            self::$$key = $value;
        }
    }

    /**
     * Allow dynamic accessibility to static attributes
     * @param  string $method
     * @param  mixed $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if(property_exists(__CLASS__, $method))
        {
            return self::$$method;
        }
        throw new Exception\ApiException("Call to undefined function {$method}");
    }

    /**
     * Verify that HMAC hash of parameters matches HMAC signature
     * @return [type] [description]
     */
    public static function validateHmac()
    {
        $params = [];
        foreach($_GET as $param => $value) {
    	    if ($param != 'signature' && $param != 'hmac') {
    		$params[$param] = "{$param}={$value}";
    	    }
    	}
        asort($params);
        $params = implode("&", $params);

        return $_GET['hmac'] === hash_hmac('sha256', $params, self::$api_secret);
    }

    /**
     * Return the root API url based on the authenticated store
     * @return string
     */
    public static function baseUrl()
    {
        return sprintf('https://%s/admin/', self::$store);
    }
}
