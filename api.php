<?php
header("Content-Type: application/json");

$file = "products.json";

// Leer el archivo JSON
function readProducts() {
    global $file;
    if (!file_exists($file)) return [];
    $data = file_get_contents($file);
    return json_decode($data, true) ?? [];
}

// Guardar productos en JSON
function saveProducts($products) {
    global $file;
    file_put_contents($file, json_encode($products, JSON_PRETTY_PRINT));
}

// Manejo de métodos HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        echo json_encode(readProducts());
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data["name"]) || !isset($data["price"])) {
            echo json_encode(["error" => "Faltan datos"]);
            exit;
        }
        $products = readProducts();
        $newProduct = [
            "id" => count($products) + 1,
            "name" => $data["name"],
            "price" => $data["price"]
        ];
        $products[] = $newProduct;
        saveProducts($products);
        echo json_encode($newProduct);
        break;

    case "DELETE":
        if (!isset($_GET["id"])) {
            echo json_encode(["error" => "ID no proporcionado"]);
            exit;
        }
        $id = intval($_GET["id"]);
        $products = readProducts();
        $products = array_filter($products, fn($p) => $p["id"] !== $id);
        saveProducts(array_values($products));
        echo json_encode(["message" => "Producto eliminado"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
