<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Services\Data\DataSourceFactory;

$dataSource = DataSourceFactory::create();
$documents = $dataSource->getDocuments();
foreach ($documents as $filename => $content) {
    $lines = explode("\n", $content);
    $headers = str_getcsv($lines[0]);
    $firstRow = str_getcsv($lines[1]);

    echo "Headers:\n";
    foreach ($headers as $i => $h)
        echo "[$i] $h\n";

    echo "\nFirst Row:\n";
    foreach ($firstRow as $i => $v)
        echo "[$i] $v\n";
    break;
}
