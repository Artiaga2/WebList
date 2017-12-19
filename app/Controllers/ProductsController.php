<?php
namespace App\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Sirius\Validation\Validator;

class ProductsController extends BaseController {

    public function getNew(){
//        global $osTypeValues, $basedOnValues, $desktopValues, $originValues, $archValues, $categoryValues, $statusValues;

        $errors = array();  // Array donde se guardaran los errores de validación
        $error = false;     // Será true si hay errores de validación.

        $webInfo = [
            'h1'        => 'Añadir Producto',
            'submit'    => 'Añadir',
            'method'    => 'POST'
        ];

        // Se construye un array asociativo $product con todas sus claves vacías
        $product = array_fill_keys(["name","quantity", "price", "brand", "type", "description"], "");

        return $this->render('formproducts.twig', [
            'product'        => $product,
            'errors'        => $errors,
            'webInfo'       => $webInfo
        ]);
    }

    /**
     * Ruta [POST] /products/new que procesa la introducción de un nuevo producto.
     *
     * @return string Render de la web con toda la información.
     */
    public function postNew(){

        $webInfo = [
            'h1'        => 'Añadir product',
            'submit'    => 'Añadir',
            'method'    => 'POST'
        ];

        if (!empty($_POST)) {
            $validator = new Validator();

            $requiredFieldMessageError = "El {label} es requerido";

            $validator->add('name:Nombre', 'required',[],$requiredFieldMessageError);
            $validator->add('quantity:Cantidad', 'required', [], $requiredFieldMessageError);
            $validator->add('price:Precio', 'required',[], $requiredFieldMessageError);
            $validator->add('brand:Marca','required',[],$requiredFieldMessageError);

            // Extraemos los datos enviados por POST
            $product['name'] = htmlspecialchars(trim($_POST['name']));
            $product['quantity'] = htmlspecialchars(trim($_POST['quantity']));
            $product['price'] = htmlspecialchars(trim($_POST['price']));
            $product['brand'] = htmlspecialchars(trim($_POST['forum']));
            $product['type'] = htmlspecialchars(trim($_POST['type']));
            $product['description'] = htmlspecialchars(trim($_POST['description']));

            if ($validator->validate($_POST)) {
                $product = new product([
                    'quantity'      => $product['quantity'],
                    'name'          => $product['name'],
                    'price'       => $product['price'],
                    'type'           => $product['type'],
                    'brand'        => $product['brand'],
                    'description'   => $product['description']
                ]);
                $product->save();

                // Si se guarda sin problemas se redirecciona la aplicación a la página de inicio
                header('Location: ' . BASE_URL);
            }else{
                $errors = $validator->getMessages();
            }
        }

        return $this->render('formproducts.twig', [

            'product'        => $product,
            'errors'        => $errors,
            'webInfo'       => $webInfo
        ]);
    }

    /**
     * Ruta [GET] /products/edit/{id} que muestra el formulario de actualización de un nuevo producto.
     *
     * @param id Código de la distribución.
     *
     * @return string Render de la web con toda la información.
     */
    public function getEdit($id){


        $errors = array();  // Array donde se guardaran los errores de validación

        $webInfo = [
            'h1'        => 'Actualizar product',
            'submit'    => 'Actualizar',
            'method'    => 'PUT'
        ];

        // Recuperar datos
        $product = product::find($id);

        if( !$product ){
            header('Location: home.twig');
        }

        return $this->render('formproducts.twig',[
            'product'        => $product,
            'errors'        => $errors,
            'webInfo'       => $webInfo
        ]);
    }

    /**
     * Ruta [PUT] /products/edit/{id} que actualiza toda la información de una distribución. Se usa el verbo
     * put porque la actualización se realiza en todos los campos de la base de datos.
     *
     * @param id Código de la distribución.
     *
     * @return string Render de la web con toda la información.
     */
    public function putEdit($id){

        $errors = array();  // Array donde se guardaran los errores de validación

        $webInfo = [
            'h1'        => 'Actualizar product',
            'submit'    => 'Actualizar',
            'method'    => 'PUT'
        ];

        if( !empty($_POST)){
            $validator = new Validator();

            $requiredFieldMessageError = "El {label} es requerido";

            $validator->add('name:Nombre', 'required',[],$requiredFieldMessageError);
            $validator->add('quantity:Cantidad', 'required', [], $requiredFieldMessageError);
            $validator->add('price:Precio', 'required',[], $requiredFieldMessageError);
            $validator->add('brand:Marca','required',[],$requiredFieldMessageError);

            // Extraemos los datos enviados por POST
            $product['id'] = $id;
            $product['name'] = htmlspecialchars(trim($_POST['name']));
            $product['quantity'] = htmlspecialchars(trim($_POST['quantity']));
            $product['price'] = htmlspecialchars(trim($_POST['price']));
            $product['brand'] = htmlspecialchars(trim($_POST['forum']));
            $product['type'] = htmlspecialchars(trim($_POST['type']));
            $product['description'] = htmlspecialchars(trim($_POST['description']));

            if ( $validator->validate($_POST) ){
                $product = Product::where('id', $id)->update([
                    'id'            => $product['id'],
                    'quantity'      => $product['quantity'],
                    'name'          => $product['name'],
                    'price'         => $product['price'],
                    'type'          => $product['type'],
                    'brand'         => $product['brand'],
                    'description'   => $product['description']
                ]);

                // Si se guarda sin problemas se redirecciona la aplicación a la página de inicio
                header('Location: ' . BASE_URL);
            }else{
                $errors = $validator->getMessages();
            }
        }
        return $this->render('formproducts.twig', [
            'product'        => $product,
            'errors'        => $errors,
            'webInfo'       => $webInfo
        ]);
    }

    /**
     * Ruta raíz [GET] /products para la dirección home de la aplicación. En este caso se muestra la lista de distribuciones.
     *
     * @return string Render de la web con toda la información.
     *
     * Ruta [GET] /products/{id} que muestra la página de detalle de la distribución.
     * todo: La vista de detalle está pendiente de implementar.
     *
     * @param id Código de la distribución.
     *
     * @return string Render de la web con toda la información.
     */
    public function getIndex($id = null){
        if( is_null($id) ){
            $webInfo = [
                'title' => 'Página de Inicio - product'
            ];

            $products = product::query()->orderBy('id','desc')->get();

            return $this->render('home.twig', [
                'products' => $products,
                'webInfo' => $webInfo
            ]);

        }else{
            // Recuperar datos

            $webInfo = [
                'title' => 'Página de product - product'
            ];

            $product = product::find($id);
            $comments = Comment::where('product_id', $id)->orderBy('created_at','DESC')->get();

            if( !$product ){
                return $this->render('404.twig', ['webInfo' => $webInfo]);
            }

            //dameDato($product);
            return $this->render('product/product.twig', [
                'product'    => $product,
                'webInfo'   => $webInfo,
                'comments'  => $comments
            ]);
        }

    }

    public function postIndex($id){
        $errors = [];
        $validator = new Validator();

        $validator->add('name:Nombre','required', [], 'El {label} es necesario para comentar');
        $validator->add('name:Nombre','minlength', ['min' => 5], 'El {label} debe tener al menos 5 caracteres');
        $validator->add('email:Email','required', [], 'El {label} no es válido');
        $validator->add('email:Email','required', [], 'El {label} es necesario para comentar');
        $validator->add('comment:Comentario', 'required', [], 'No se permiten comentarios vacíos');

        if($validator->validate($_POST)){
            $comment = new Comment();

            $comment->product_id = $id;
            $comment->user = $_POST['name'];
            $comment->email = $_POST['email'];
            $comment->ip = getRealIP();
            $comment->text = $_POST['comment'];
            $comment->approved = true;

            $comment->save();

            header("Refresh: 0 " );
        }else{
            $errors = $validator->getMessages();
        }

        $webInfo = [
            'title' => 'Página de product - product'
        ];

        $product = product::find($id);
        $comments = Comment::all();
        $webInfo = [
            'title' => 'Página de product - product'
        ];

        if( !$product ){
            return $this->render('404.twig', ['webInfo' => $webInfo]);
        }

        return $this->render('product/product.twig', [
            'errors'    => $errors,
            'webInfo'   => $webInfo,
            'product'    => $product,
            'comments'  => $comments
        ]);
    }

    /**
     * Ruta [DELETE] /products/delete para eliminar la distribución con el código pasado
     */
    public function deleteIndex(){
        $id = $_REQUEST['id'];

        $product = product::destroy($id);

        header("Location: ". BASE_URL);
    }
}