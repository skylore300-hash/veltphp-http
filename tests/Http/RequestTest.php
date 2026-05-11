<?php

use PHPUnit\Framework\TestCase;
use Velt\Http\Request;

class RequestTest extends TestCase
{
    /**
     * Teste la capture d'une requête GET
     */
    public function testCanCaptureGetRequest()
    {
        $request = new Request('GET', '/api/preview/123', ['page' => '1']);
        
        $this->assertEquals('GET', $request->method());
        $this->assertEquals('/api/preview/123', $request->path());
        $this->assertEquals('1', $request->query('page'));
    }

    /**
     * Teste la capture d'une requête POST
     */
    public function testCanCapturePostRequest()
    {
        $request = new Request('POST', '/login', [], ['email' => 'test@test.com']);
        
        $this->assertEquals('POST', $request->method());
        $this->assertEquals('test@test.com', $request->input('email'));
    }

    /**
     * Teste la valeur par défaut pour query
     */
    public function testQueryWithDefault()
    {
        $request = new Request('GET', '/');
        
        $this->assertEquals(1, $request->query('page', 1));
    }

    /**
     * Teste si c'est une requête POST
     */
    public function testIsPost()
    {
        $request = new Request('POST', '/');
        $this->assertTrue($request->isPost());
    }

    /**
     * Teste si c'est une requête GET
     */
    public function testIsGet()
    {
        $request = new Request('GET', '/');
        $this->assertTrue($request->isGet());
    }
}