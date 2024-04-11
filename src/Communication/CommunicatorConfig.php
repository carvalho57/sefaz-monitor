<?php

namespace App\Communication;

class CommunicatorConfig
{
    public function __construct(
        public readonly string $environment,
        public readonly string $certificateClientPEMPath,
        public readonly string $certificateKeyPEMPath,
        public readonly string $uf,
        public readonly string $version
    ) {
        if (!file_exists($certificateClientPEMPath) || !file_exists($certificateKeyPEMPath)) {
            throw new \Exception('Canno access certificate files.');
        }
    }
}
