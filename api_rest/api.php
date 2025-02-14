<?php
//api.php es donde se ejecuta el GET, POST y DELETE

header("Content-Type: application/json");// Permite que el contenido de respuesta sea JSON

$file = "products.json";// Ruta del archivo JSON con los productos

function readProducts() {// Funcion para leer los productos desde el JSON
    global $file;
    if (!file_exists($file)) return []; // Si el archivo no existe, retorna un array vacio
    $data = file_get_contents($file); // Lee el contenido del archivo
    return json_decode($data, true) ?? []; // Convierte JSON a array PHP
}

function saveProducts($products) {// Funcion para guardar productos en el JSON
    global $file;
    file_put_contents($file, json_encode($products, JSON_PRETTY_PRINT)); // Guarda el array como JSON
}


$method = $_SERVER['REQUEST_METHOD'];// Determina el metodo HTTP de la solicitud

switch ($method) {
    case "GET":
        echo json_encode(readProducts()); // Devuelve la lista de productos
        break;
    case "POST":

        $data = json_decode(file_get_contents("php://input"), true);// Obtiene los datos enviados en formato JSON
        
        if (!isset($data["name"]) || !isset($data["price"])) {// Verifica que los campos obligatorios existan
            echo json_encode(["error" => "Faltan datos"]);
            exit;
        }
        $products = readProducts();// Carga los productos actuales y agrega el nuevo producto
        $newProduct = [
            "id" => count($products) + 1,
            "name" => $data["name"],
            "price" => $data["price"]
        ];
        $products[] = $newProduct;
        
        saveProducts($products);// Guarda los productos actualizados en JSON
        echo json_encode($newProduct); // Retorna el nuevo producto
        break;

    case "DELETE":
        if (!isset($_GET["id"])) {
            echo json_encode(["error" => "ID no proporcionado"]);
            exit;
        }

        $id = intval($_GET["id"]);//convierte el id a numero entero
        $products = readProducts();
        $products = array_filter($products, fn($p) => $p["id"] !== $id);// Filtra los productos para eliminar el que coincida con el ID
        saveProducts(array_values($products)); // Guarda los productos actualizados

        echo json_encode(["message" => "Producto eliminado"]);
        break;

    default:
        http_response_code(405); // en caso de no ser valido, significa un metodo no permitido (codigo http 405)
        echo json_encode(["error" => "Metodo no permitido"]);
        break;
}
?>
