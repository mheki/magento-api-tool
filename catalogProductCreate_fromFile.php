<?php
require_once 'RemoteService.php';

try {
    if (!isset($argv[1])) {
        die("Please supply CSV file with products to import");
    }

    $file = $argv[1];

    $service = new RemoteService();

    $fh = fopen($file, 'r');

    $headers = fgetcsv($fh);

    while (false !== ($row = fgetcsv($fh))) {
        $data = array_combine($headers, $row);

        $sku = $data['sku'];
        echo "SKU '{$sku}': ";
        unset($data['sku']);

        $data = array_merge(array(
            'websites'               => array('base'), // Websites the product should show up on
            'category_ids'           => '',
            'visibility'             => 4,
            'status'                 => 1,
            'weight'                 => 1,
            'tax_class_id'           => 2,
        ), $data);

        $data['websites'] = explode(',', $data['websites']);
        $data['category_ids'] = explode(',', $data['category_ids']);

        $products = $service->catalogProductList(
            array('filters' => array(
                'filter' => array(array('key' => 'sku', 'value' => $sku))
            ))
        );

        if (!empty($products)) {
            $productId = $products[0]->product_id;

            $service->catalogProductUpdate(array(
                'product_id' => $productId,
                'data'       => $data
            ));

            echo "Updated Product ID: " . $productId . "\n";
        } else {
            $productId = $service->catalogProductCreate(array(
                'type' => 'simple',
                'set'  => 4, // attribute set ID
                'sku'  => $sku,
               'productData' => $data
            ));

            echo "Created Product ID: " . $productId . "\n";
        }
    }
} catch (SoapFault $e) {
    echo "Exception '" . get_class($e) . "':\n";
    print_r($e);
}