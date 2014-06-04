<?php

namespace Zyncro;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Storage\Memory;
use OAuth\ServiceFactory;

class Client
{
    private $storage;

    private $credentials;

    private $service;

    public function __construct($user, $password)
    {
        $this->storage = new Memory();
        $this->credentials = new Credentials($user, $password, 'nobrowser');
        $this->service = (new ServiceFactory())->createService(
            'zyncro',
            $this->credentials,
            $this->storage,
            array('users/profile')
        );
    }

    public function publishPersonalFeed($message)
    {
        $this->service->request(
            '/api/v1/rest/wall/personalfeed',
            'POST',
            [
                'comment' => $message
            ]
        );
    }

    function __call($name, $arguments)
    {
        if (!$this->service) {
            throw new \RuntimeException('No service');
        }
        return call_user_func_array([$this->service, $name], $arguments);
    }
}