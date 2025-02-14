<?php

namespace VenelinIliev\Borica3ds\RequestTypes;

use VenelinIliev\Borica3ds\Response;

abstract class RequestType
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return RequestType
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  array $data
     * @return RequestType
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return Response|string|void
     */
    abstract public function send();

    /**
     * @return array|string
     */
    abstract public function generateForm();
}
