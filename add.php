<?php
include_once 'config.php';
include_once 'connectdb.php';
include_once 'helpers.php';

// Array donde se guardaran los errores de validación
$errors = array();
// Será true si hay errores de validación.
$error = false;

// Se construye un array asociativo $productos con todas sus claves vacías
$productos = array_fill_keys(["name", "quantity", "price", "description", "brand", "type"], "");

if(!empty($_POST)){
    // Extraemos los datos enviados por POST
    $productos['name'] = htmlspecialchars(trim($_POST['name']));
    $productos['quantity'] = htmlspecialchars(trim($_POST['quantity']));
    $productos['price'] = htmlspecialchars(trim($_POST['price']));
    $productos['description'] = htmlspecialchars(trim($_POST['description']));
    $productos['brand'] = htmlspecialchars(trim($_POST['brand']));
    $productos['type'] = htmlspecialchars(trim($_POST['type']));

    // Comprobar que se han enviado los campos requeridos

    if( $productos['name'] == "" ){
        $errors['name']['required'] = "El campo nombre es requerido";
    }

//    if ($_POST['name'] === $pro['name']){
//        $errors['name']['required'] = "El producto ya esta añadido";
//    }

    if( $productos['quantity'] == "" ){
        $errors['quantity']['required'] = "El campo cantidad es requerido";
    }

    if( $productos['price'] == "" ){
        $errors['price']['required'] = "El campo Precio es requerido";
    }

    if( $productos['description'] == "" ){
        $errors['description']['required'] = "El campo descripcion es requerido";
    }

    if( $productos['brand'] == "" ){
        $errors['brand']['required'] = "El campo marca es requerido";
    }

    if( $productos['type'] == "" ){
        $errors['type']['required'] = "El campo tipo es requerido";
    }


    if (empty($errors)){

        $tabla = array();
        $tabla = "SELECT name FROM productos";
        $resultado = $pdo->prepare($tabla);
        $resultado->execute([
                'name' => $productos['name']
        ]);

        for ($i = 0; $i = array_walk($tabla); $i++  ){

            if ($tabla = $productos['name']){
                $errors['name']['required'] = "El campo nombre ya  esta en uso";
            }
        }
    };


//        if ($productos['name'] != $tabla) {
            // Si no tengo errores de validación
            // Guardo en la BD
            $sql = "INSERT INTO productos (name, quantity, price, description, brand, type) VALUES (:name, :quantity, :price, :description, :brand, :type)";

            $result = $pdo->prepare($sql);

            $result->execute([
                'name'          => $productos['name'],
                'quantity'      => $productos['quantity'],
                'price'         => $productos['price'],
                'description'   => $productos['description'],
                'brand'         => $productos['brand'],
                'type'          => $productos['type']
            ]);


//        }else{
//            $error = true;
//        }
        // Si se guarda sin problemas se redirecciona la aplicación a la página de inicio
        header('Location: index.php');
    }else{
        // Si tengo errores de validación
        $error = true;
}

$error = !empty($errors)?true:false;

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Starter Template for Bootstrap</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Web List</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Inicio</a></li>
                <li><a href="add.php">Añadir Producto</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="container">
    <h1>Add New Product</h1>
    <form action="" method="post">
        <div class="form-group<?php echo (isset($errors['name']['required'])?" has-error":""); ?>">
            <label for="inputName">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" placeholder="name" value="<?=$productos['name']?>">
        </div>

        <div class="form-group<?php echo (isset($errors['quantity']['required'])?" has-error":""); ?>">
            <label for="inputQuantity">Quantity</label>
            <input type="text" class="form-control" id="inputQuantity" name="quantity" placeholder="quantity" value="<?=$productos['quantity']?>">
        </div>

        <div class="form-group<?php echo (isset($errors['price']['required'])?" has-error":""); ?>">
            <label for="inputPrice">Price</label>
            <input type="text" class="form-control" id="inputPrice" name="price" placeholder="price" value="<?=$productos['price']?>">
        </div>

        <div class="form-group<?php echo (isset($errors['description']['required'])?" has-error":""); ?>">
            <label for="inputDescription">Description</label>
            <input type="text" class="form-control" id="inputDescription" name="description" placeholder="description" value="<?=$productos['description']?>">
        </div>

        <div class="form-group<?php echo (isset($errors['brand']['required'])?" has-error":""); ?>">
            <label for="inputBrand">Brand</label>
            <input type="text" class="form-control" id="inputBrand" name="brand" placeholder="brand" value="<?=$productos['brand']?>">
        </div>

        <div class="form-group<?php echo (isset($errors['type']['required'])?" has-error":""); ?>">
            <label for="inputType">Type</label>
            <input type="text" class="form-control" id="inputType" name="type" placeholder="type" value="<?=$productos['type']?>">
        </div>

        <?=generarAlert($errors, 'name')?>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div><!-- /.container -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
