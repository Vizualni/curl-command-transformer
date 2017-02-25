<?php
namespace tests\CurlTransform\Transform;
require __DIR__ . './../../../vendor/autoload.php';

use CurlTransform\Exception\CurlTransformException;
use CurlTransform\Request\RequestInterface;
use CurlTransform\Transform\Transform;

class TransformTest extends \PHPUnit_Framework_TestCase
{
    /** @var Transform */
    protected $transformer;

    protected $request;

    public function setUp()
    {
        $this->transformer = new Transform();
        $this->request = $this->getMockBuilder(RequestInterface::class)->getMock();

    }

    /**
     *
     * @dataProvider curlDataProvider
     * @param $url
     * @param $method
     * @param $headers
     * @param $data
     * @param $expected
     */
    public function testProvidedData($url, $method, $headers, $data, $expected)
    {
        $this->request->method('getUrl')->willReturn($url);
        $this->request->method('getMethod')->willReturn($method);
        $this->request->method('getHeaders')->willReturn($headers);
        $this->request->method('getData')->willReturn($data);

        $output = $this->transformer->getCurlCommand($this->request);

        $this->assertEquals($expected, $output);

    }

    public function testThatUnknownMethodThrowsAnException()
    {
        $this->expectException(CurlTransformException::class);
        $this->request->method('getMethod')->willReturn('UNKNOWN METHOD');
        $this->transformer->getCurlCommand($this->request);
    }

    public function curlDataProvider()
    {
        return [

            ['http://google.com', 'GET', [], '', 'curl -XGET "http://google.com"'],
            ['http://google.com', 'POST', [], '', 'curl -XPOST "http://google.com"'],
            ['http://google.com', 'PATCH', [], '', 'curl -XPATCH "http://google.com"'],
            ['http://google.com', 'DELETE', [], '', 'curl -XDELETE "http://google.com"'],
            ['http://google.com', 'PUT', [], '', 'curl -XPUT "http://google.com"'],
            ['http://google.com', 'HEAD', [], '', 'curl -XHEAD "http://google.com"'],

            ['http://google.com', 'POST', ['key'=> 'value'], '', 'curl -XPOST -H "key:value" "http://google.com"'],
            ['http://google.com', 'POST', ['key'=> 'value with "quote"'], '', 'curl -XPOST -H "key:value with \"quote\"" "http://google.com"'],
            ['http://google.com', 'POST', ['key1'=> 'value1', 'key2'=>'value2'], '', 'curl -XPOST -H "key1:value1" -H "key2:value2" "http://google.com"'],

            ['http://google.com', 'POST', ['key'=> 'value'], 'data', 'curl -XPOST -H "key:value" --data "data" "http://google.com"'],
            ['http://google.com', 'POST', ['key'=> 'value'], '"quote"', 'curl -XPOST -H "key:value" --data "\"quote\"" "http://google.com"'],

        ];
    }
}
