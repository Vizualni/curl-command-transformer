<?php

namespace CurlTransform\Transform;

use CurlTransform\Exception\CurlTransformException;
use CurlTransform\Request\RequestInterface;

class Transform
{

    /**
     * @param RequestInterface $request
     * @return string
     */
    public function getCurlCommand(RequestInterface $request)
    {
        $url = $request->getUrl();
        $headers = $request->getHeaders();
        $data = $request->getData();
        $method = $request->getMethod();

        // validate...

        return $this->getOutput($url, $method, $headers, $data);
    }

    /**
     * @param $url
     * @param $method
     * @param $headers
     * @param $data
     * @return string
     * @throws CurlTransformException
     */
    protected function getOutput($url, $method, $headers = [], $data = '')
    {
        $string = 'curl ';

        // Adding method
        if (false === $this->isMethodValid($method)) {
            throw new CurlTransformException('Unknown method');
        }

        $string .= $this->sprintf('-X%s ', mb_strtoupper($method));

        // Adding headers
        foreach ($headers as $headerName => $headerValue) {
            $string .= $this->sprintf('-H "%s:%s" ', $headerName, $headerValue);
        }

        // Adding data
        if ('' !== (string) $data) {
            $string .= $this->sprintf('--data "%s" ', $data);
        }

        // Adding url
        $string .= $this->sprintf('"%s"', $url);

        return $string;
    }

    /**
     * @param $format
     * @param array ...$arguments
     * @return string
     */
    private function sprintf($format, ...$arguments)
    {
        foreach ($arguments as &$argument) {
            $argument = addslashes($argument);
        }

        return sprintf($format, ...$arguments);
    }

    /**
     * @param $method
     * @return bool
     */
    private function isMethodValid($method)
    {
        return in_array(
            mb_strtoupper($method),
            [
                'GET',
                'POST',
                'PATCH',
                'DELETE',
                'HEAD',
                'PUT',
            ]
        );
    }
}
