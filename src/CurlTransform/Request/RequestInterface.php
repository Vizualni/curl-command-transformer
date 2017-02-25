<?php

namespace CurlTransform\Request;

interface RequestInterface
{

    /**
     * Must return key => value where key is header name and value is header value
     * @return array
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getData();

}
