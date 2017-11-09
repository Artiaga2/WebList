<?php

function getProduct($id, $pdo){
    $sql = "SELECT * FROM productos WHERE id = :id";
    $result = $pdo->prepare($sql);

    $result->execute([ 'id' => $id]);

    return $result->fetch(PDO::FETCH_ASSOC);
}
?>