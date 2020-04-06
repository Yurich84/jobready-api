<?php

namespace Yurich84\JobReadyApi;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use function GuzzleHttp\Promise\unwrap;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class JobReady {

    const CONFIG = 'jobready';
    const CONFIG_KEY = 'key';
    const CONFIG_USER = 'user';
    const CONFIG_BASE_URL = 'base_url';

    const PARAMETER_OFFSET = 0;
    const PARAMETER_LIMIT = 100;

    const DATE_FORMAT = 'Y-m-d';
    const TIME_FORMAT = 'HH:mm';
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uP T';

    public $errors = [];
    private $client;
    public $config;
    public $params = [];

    public $entity;

    /**
     * JobReadyInit constructor.
     */
    public function __construct()
    {
        $this->config = config(self::CONFIG);
        $this->client = new Client([
            'base_uri' => $this->config[self::CONFIG_BASE_URL],
            'auth' => [
                $this->config[self::CONFIG_USER],
                $this->config[self::CONFIG_KEY]
            ]
        ]);
    }


    /**
     * @param $uri
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function getResponse($uri, $limit = self::PARAMETER_LIMIT, $offset = self::PARAMETER_OFFSET)
    {
        try {
            $uri = $this->buildUri(...func_get_args());
            $response = $this->client->request('GET', $uri);
            if ($response->getStatusCode() === 200) {
                $result = $response->getBody()->getContents();
                $xml = simplexml_load_string($result);
                $total = (string) $xml->attributes()->total;
                $data = self::xml2array($xml);
            } else {
                throw new Exception(Response::$statusTexts[$response->getStatusCode()]);
            }
        } catch (RequestException $e) {
            Log::error("Fetching $uri error.", $e->getTrace());
            throw new Exception(simplexml_load_string($e->getResponse()->getBody()->getContents())->message);
        }

        return [
            'data' => $data,
            'total' => $total,
            'uri' => $uri,
        ];
    }

    /**
     * @param $uri_array
     * @return array
     */
    public function getAsyncResponse($uri_array)
    {
        $promises = [];
        foreach ($uri_array as $uri_item) {
            $uri = $this->buildUri($uri_item);
            $promises[] = $this->client->getAsync($uri);
        }

        $responses = unwrap($promises);

        $data = [];
        foreach ($responses as $response) {
            $result = $response->getBody()->getContents();
            $xml = simplexml_load_string($result);
            $data[] = self::xml2array($xml);
        }

        return $data;
    }


    /**
     * @param $uri
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function postResponse($uri, $payload = [])
    {
        try {
            $uri = $this->buildUri($uri);
            $response = $this->client->request('POST', $uri, [
                'form_params' => $payload
            ]);
            if (in_array($response->getStatusCode(), [200, 201, 202])) {
                $result = $response->getBody()->getContents();
            } else {
                throw new Exception(Response::$statusTexts[$response->getStatusCode()]);
            }
        } catch (RequestException $e) {
            $response = simplexml_load_string($e->getResponse()->getBody()->getContents());
            $message = '';
            if($response->{'validation-errors'}) {
                foreach ($response->{'validation-errors'} as $error) {
                    $message .= $error->{'validation-error'}->message . '. ';
                }
                $message = substr($message, 0, -2);
            }
            throw new Exception($response->message . ': ' . $message);
        }
        $xml = simplexml_load_string($result);
        return [
            'data' => self::xml2array($xml)
        ];
    }


    /**
     * @param $uri
     * @param null $limit
     * @param null $offset
     * @return string
     */
    private function buildUri($uri, $limit = null, $offset = null)
    {
        $query = '';
        if($limit) {
            $this->params['offset'] = $offset;
            $this->params['limit'] = $limit;
            $query = '?' . http_build_query($this->params);
        }
        return $uri . $query;
    }


    /**
     * Convert SimpleXMLElement's into a array.
     *
     * @param $xmlObject object
     * @return mixed
     */
    private static function xml2array($xmlObject)
    {
        $json = json_encode($xmlObject, JSON_FORCE_OBJECT);
        $json_upd = str_replace('{}', '""', $json);
        return json_decode($json_upd, TRUE);
    }
}