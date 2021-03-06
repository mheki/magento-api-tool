<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    $service = new RemoteService();

    // Get a sales order list by the applied filters
    $result = $service->catalogCategoryTree(array());
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
