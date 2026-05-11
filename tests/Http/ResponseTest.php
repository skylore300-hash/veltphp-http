<?php

use PHPUnit\Framework\TestCase;
use Velt\Http\Response;
use Velt\Http\JsonResponse;

class ResponseTest extends TestCase
{
    /**
     * Teste une réponse HTML
     */
    public function testHtmlResponse()
    {
        $response = Response::html('<h1>Bonjour</h1>', 200);
        
        $this->assertEquals(200, $response->status());
        $this->assertEquals('<h1>Bonjour</h1>', $response->body());
        $this->assertArrayHasKey('Content-Type', $response->headers());
        $this->assertStringContainsString('text/html', $response->headers()['Content-Type']);
    }

    /**
     * Teste une réponse JSON
     */
    public function testJsonResponse()
    {
        $data = ['ok' => true, 'message' => 'Succès'];
        $response = JsonResponse::json($data, 200);
        
        $this->assertEquals(200, $response->status());
        $this->assertEquals(json_encode($data), $response->body());
        $this->assertStringContainsString('application/json', $response->headers()['Content-Type']);
    }

    /**
     * Teste le chaînage de status()
     */
    public function testStatusChaining()
    {
        $response = Response::html('Bonjour')->status(201);
        
        $this->assertEquals(201, $response->status());
    }

    /**
     * Teste le chaînage de header()
     */
    public function testHeaderChaining()
    {
        $response = Response::html('Bonjour')
            ->header('X-Custom', 'valeur')
            ->header('X-Another', 'test');
        
        $this->assertEquals('valeur', $response->headers()['X-Custom']);
        $this->assertEquals('test', $response->headers()['X-Another']);
    }
}