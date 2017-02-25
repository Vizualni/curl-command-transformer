<?php

namespace CurlTransform\Request;

interface RequestInterface
{

    public function getHeaders();

    public function getUrl();

    public function getMethod();

    public function getData();

}
