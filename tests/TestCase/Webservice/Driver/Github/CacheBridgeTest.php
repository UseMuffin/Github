<?php
namespace Muffin\Github\Test\TestCase\Webservice\Driver\Github;

use Cake\Cache\Cache;
use Cake\TestSuite\TestCase;
use Guzzle\Http\Message\Response;
use Muffin\Github\Webservice\Driver\Github\CacheBridge;

class CacheBridgeTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Cache::config('muffin_test', ['engine' => 'File', 'path' => TMP . 'tests']);
    }

    public function tearDown()
    {
        parent::tearDown();
        Cache::clear(false, 'muffin_test');
        Cache::drop('muffin_test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorThrowsException()
    {
        new CacheBridge('github');
    }

    public function testBridge()
    {
        $bridge = new CacheBridge('muffin_test');
        $id = 'foo';
        $etag = 'bar';

        $this->assertFalse($bridge->has($id));

        $response = $this->getMock('Guzzle\Http\Message\Response', ['getHeader'], [200]);
        $response->expects($this->once())
            ->method('getHeader')
            ->with('ETag')
            ->will($this->returnValue($etag));

        $bridge->set($id, $response);

        $this->assertTrue($bridge->has($id));

        $this->assertInstanceOf('Guzzle\Http\Message\Response', $bridge->get($id));
        $this->assertEquals($etag, $bridge->getETag($id));
    }
}
