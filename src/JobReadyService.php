<?php

namespace Yurich84\JobReadyApi;

use Illuminate\Support\Str;

class JobReadyService extends JobReady
{

    public $perPage = 100;
    public $entity;

    /**
     * JobReadyService constructor.
     */
    public function __construct()
    {
        $this->entity = $this->entity();
        parent::__construct();
    }

    /**
     * @param $param_name
     * @param $param_value
     * @return $this
     */
    public function where($param_name, $param_value) {
        $this->params[$param_name] = $param_value;
        return $this;
    }

    /**
     * @param int $limit
     * @return object
     */
    public function get(int $limit = null) : object
    {
        $limit = $limit ?: $this->perPage;
        $response = $this->getResponse($this->entity, $limit);
        $data = (!empty($response['data']) && key_exists(Str::singular($this->entity), $response['data']))
            ? collect($response['data'][Str::singular($this->entity)])
            : [];
        if($response['total'] == 1) {
            $data = [$data];
        }
        return (object) [
            'data' => $data,
            'total' => $response['total']
        ];
    }


    /**
     * @param $payload
     * @return array
     */
    public function create($payload)
    {
        $entityPayload[Str::singular($this->entity)] = $payload;
        return $this->postResponse($this->entity, $entityPayload);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $response = $this->getResponse($this->entity . '/' . $id);
        return $response['data'];
    }

    /**
     * @param $uri_array
     * @return array
     */
    public function findBulk($uri_array)
    {
        return $this->getAsyncResponse($uri_array);
    }


    /**
     * @param $id
     * @param $payload
     * @return array
     */
    public function update($id, $payload)
    {
        $entityPayload[Str::singular($this->entity)] = $payload;
        return $this->postResponse($this->entity . '/' . $id, $entityPayload);
    }


    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        return $this->postResponse($this->entity . '/' . $id);
    }

    /**
     * @param null $limit
     * @param null $offset
     * @return string
     */
    public function uri($limit = null, $offset = null)
    {
        if($offset) $this->params['offset'] = $offset;
        if ($limit) $this->params['limit'] = $limit;

        $query = (count($this->params) > 0) ? '?' . http_build_query($this->params) : '';

        return $this->entity . $query;
    }

    /**
     * @param null $limit
     * @param null $offset
     * @return string
     */
    public function url($limit = null, $offset = null)
    {
        return $this->config[self::CONFIG_BASE_URL] . $this->uri(...func_get_args());
    }

}