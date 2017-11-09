<?php
include_once 'config.php';
include_once 'connectdb.php';
include_once 'helpers.php';
include_once 'dbhelpers.php';

$id = $_REQUEST['id'];

// Recuperar datos
$product = getProduct($id, $pdo);


if( !$product ){
    header('Location: index.php');
}

dameDato($product);