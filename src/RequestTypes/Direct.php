<?php

namespace VenelinIliev\Borica3ds\RequestTypes;

use VenelinIliev\Borica3ds\Exceptions\SendingException;

class Direct extends RequestType
{
    /**
     * @var false|resource
     */
    private $ch;

    /**
     * Direct constructor.
     */
    public function __construct()
    {
        $this->ch = curl_init();
    }

    /**
     * @return boolean|string
     * @throws SendingException
     */
    public function send()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->getData()));
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($this->ch);
        if (curl_error($this->ch)) {
            throw new SendingException(curl_error($this->ch));
        }
        curl_close($this->ch);

        return $response;
    }

    /**
     * @return array
     */
    public function generateForm()
    {
        return $this->getData();
    }
}
