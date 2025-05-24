<?php
require_once('import_class.php');
require 'vendor/autoload.php';
$class = new DataImport();

$reader =  \PhpOffice\PhpSpreadsheet\IOFactory::load('users.xlsx');
$worksheet = $reader->setActiveSheetIndexByName('users');
$rows = $worksheet->toArray();
$header = 0;
foreach($rows as $key => $value){

    if($header > 0){
        $class->insert_data($value);
    }else{
        $header = 1;
    }
    
}