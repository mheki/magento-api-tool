<?php
require_once __DIR__ . '/../RemoteService.php';

try {
    $service = new RemoteService();

    $filters  = array(
        // Get orders made today
        'filter' => array(),
        'complex_filter' => array(
            array('key' => 'created_at', 'value' => array('key' => 'gteq', 'value' => date('Y-m-d', strtotime('-1 month'))))
        ),
    );

    // Get a sales order list by the applied filters
    $result = $service->customerCustomerList(array('filters' => $filters));
    print_r($result);
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) ."':\n";
    print_r($e);
}
