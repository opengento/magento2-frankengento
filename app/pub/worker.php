<?php
require __DIR__ . '/../app/autoload.php';

use Opengento\Frankengento\Model\FrankenPHPHandler;
try {

    for (;;) {
        $request = \frankenphp_handle_request(function() {
            FrankenPHPHandler::handleRequest();
        });

        // Si pas de nouvelle requête, on sort de la boucle
        if (!$request) {
            break;
        }

        // Le worker peut être recyclé ici si nécessaire
        gc_collect_cycles();
    }
} catch (\Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
}

