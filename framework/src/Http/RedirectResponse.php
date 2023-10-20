<?php

namespace Somecode\Framework\Http;

class RedirectResponse extends Response
{
    public function __construct($url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    public function send()
    {
        header("Location: {$this->getHeader('location')}", true, $this->getStatusCode());
        exit;
    }
}
