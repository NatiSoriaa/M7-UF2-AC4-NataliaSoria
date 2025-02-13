<?php
$apiUrl = "http://localhost/api_rest/api.php";

// Obtener productos
$products = json_decode(file_get_contents($apiUrl), true);

// Agregar un nuevo producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["price"])) {
    $data = json_encode(["name" => $_POST["name"], "price" => $_POST["price"]]);
    $opts = ["http" => ["method" => "POST", "header" => "Content-Type: application/json", "content" => $data]];
    file_get_contents($apiUrl, false, stream_context_create($opts));
    header("Location: client.php");
    exit;
}

// Eliminar producto
if (isset($_GET["delete"])) {
    file_get_contents("$apiUrl?id=" . $_GET["delete"], false, stream_context_create(["http" => ["method" => "DELETE"]]));
    header("Location: client.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Productos</h1>
    <ul>
        <?php foreach ($products as $p): ?>
            <li>
                <?= htmlspecialchars($p["name"]) ?> - $<?= number_format($p["price"], 2) ?>
                <a href="?delete=<?= $p["id"] ?>">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <h2>Agregar Producto</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="number" step="0.01" name="price" placeholder="Precio" required>
        <button type="submit">Agregar</button>
    </form>
</body>
</html>
