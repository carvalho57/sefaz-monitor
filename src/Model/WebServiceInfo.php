<?php

namespace App\Model;

class WebServiceInfo
{
    public function __construct(
        public readonly string $soapAction,
        public readonly string $urlPortal,
        public readonly string $urlService,
        public readonly string $urlNamespace,
        public readonly string $version,
    ) {
    }
}
