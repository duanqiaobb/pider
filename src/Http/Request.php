<?php
namespace Pider\Http;

use GuzzleHttp\Psr7\Request as BaseRequest;
use GuzzleHttp\Psr7\Response as BaseResponse;
use GuzzleHttp\Client  as Client;
use GuzzleHttp\Exception\ConnectException;


class Request {

    private static $proxy_callback = '';
    private static $client;
    private $proxy = '';
    private $uri = '';
    private $org_uri = '';
    public function __construct(array $config = []) {
        if (array_key_exists('base_uri',$config)) {
            $this->org_uri = $config['base_uri'];
        }
        //Keep client being a singleton
        self::$client = empty(self::$client)? new Client($config):self::$client;
    }
    public static function proxy_handler(callable $proxy_callback) {
        self::$proxy_callback = $proxy_callback;
    }

    public function proxy(string $proxy) {
        $this->$proxy = $proxy;
    }

    public function request($method, $uri = '', array $options = []) {
        if(!empty(self::$proxy_callback)) {
            $proxy_callback = self::$proxy_callback;
            $options['proxy'] = $proxy_callback(); 
        }
        if (!empty($this->proxy)) {
            $options['proxy'] = $this->proxy;
        }
        if (!empty($uri)) {
            $this->org_uri = $uri;
        }

        //add tracker for tracing uri
        $uri_tracker = &$this->uri;
        $options ['on_stats'] = function ($stats) use (&$uri_tracker) {
            $uri_tracker = $stats->getEffectiveUri();
        };
        $response = '';
        try {
            $response = self::$client->request($method,$this->org_uri,$options);
        } catch(ConnectException $e) {
            throw new \Exception($e->getMessage());
        } finally {
            if (! $response instanceof BaseResponse ) {
                $response = new BaseResponse();
            }
            return new Response($response);
        }
    }

    public function getOrgUri() {
        return $this->org_uri;
    }
    public function getUri() {
        return $this->uri;
    }
    public function __call($method,$args) {
        return self::$client->__call($method,$args);
    }
}
