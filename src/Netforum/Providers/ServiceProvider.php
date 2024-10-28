<?php

namespace Netforum\Providers;

use Netforum\Request;

class ServiceProvider {

    public function __construct(array $config) {
        $wsdl = $config['wsdl'];
        $this->simple = new Request($wsdl, $config);
    }

}
