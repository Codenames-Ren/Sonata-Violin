<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $cache = \Config\Services::cache();
        $key = 'throttle_' . $request->getIPAddress();
        
        $attempts = $cache->get($key) ?? 0;
        
        if ($attempts >= 10) { // Max 10 requests per minute
            return service('response')
                ->setStatusCode(429)
                ->setBody('Too many requests. Please try again later.');
        }
        
        $cache->save($key, $attempts + 1, 60); // 60 seconds
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}