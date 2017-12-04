<?php
namespace Module;

use Module\Template\TemplateEngine as Template;
use Module\Http\Response;
use Module\Http\Request;

/**
 * @class Pider
 * Handle all spider operation 
 */
abstract class Pider {
    use Template;
    protected $urls;
    protected $domains;
    private $responses;

    public function __construct() {
    }

    final public function go() {

       $requests  = $this->start_requests();
       if (!is_array($requests)) {
           $requests = [$requests];
       }
       foreach($requests as $request) {
           $response = '';
           if ($request instanceof Request) {
               $response = $request->request('GET');
           } else {
               $httpRequest = new Request();
               $url = '';
               $response = $httpRequest->request('GET',$request,[
                   'on_stats' => function ($stats) use (&$url) {
                       $url = $stats->getEffectiveUri();
                   }
               ]);
               $response->url = $url;
           }
          if (!empty($response)) {
               $items = $this->parse($response);
               //$this->export($items);
           }
        }
    }

    /**
     * @method parse() Parse information from response of requests
     * @param  Response $response the response of requests 
     * @return array | urls | Requests
     */
    public abstract function parse(Response $response); 

    /**
     *@method start_requests()
     */
    public function start_requests():array {
        $start_requests = [];
        if (!isset($this->urls) || empty($this->urls)) {
            return [];
        }
        if (is_string($this->urls)) {
            $this->urls = [$this->urls];
        }

        foreach($this->urls as $url) {
            $start_requests[] = new Request(['base_uri'=> $url]);
        }
        return $start_requests;
    }

    /**
     * @method export() Export data parsed in different ways.
     *
     */
    public function export(Item $items) {

    }


}

