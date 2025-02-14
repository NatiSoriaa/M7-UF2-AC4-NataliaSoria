<?php
$apiUrl = "http://localhost/M7/UF2/AC4/M7-UF2-AC4-NataliaSoria/api_rest/api.php";

$products = json_decode(file_get_contents($apiUrl), true);// Obtener productos desde la lista de productos del json adjuntado en la apiUrl

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["price"])) {// Agregar un nuevo producto utilizando el metodo POST
    $data = json_encode(["name" => $_POST["name"], "price" => $_POST["price"]]);
    $opts = ["http" => ["method" => "POST", "header" => "Content-Type: application/json", "content" => $data]];
    file_get_contents($apiUrl, false, stream_context_create($opts));
    header("Location: client.php");
    exit;
}

if (isset($_GET["delete"])) {// Eliminar producto con metodo DELETE 
    file_get_contents("$apiUrl?id=" . $_GET["delete"], false, stream_context_create(["http" => ["method" => "DELETE"]]));
    header("Location: client.php");
    exit;
}
?>

<!-- formulario de productos, en donde se muestran los que estan por defecto, se pueden eliminar y se crean nuevos -->
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Productos</h1>
    <ul>
         <?php foreach ($products as $p): ?> <!--variable para cada producto almacenado -->
            <li>
                 <?= htmlspecialchars($p["name"]) ?> - $<?= number_format($p["price"], 2) ?> <!--Nombre y precio de cada producto -->
                 <a href="?delete=<?= $p["id"] ?>">Eliminar</a> <!--Creamos un enlace para eliminar cada producto que queramos -->
            </li>
        <?php endforeach; ?>
    </ul>
    
     <h2>Agregar Producto</h2> <!--Agregamos un nuevo producto -->
    <form method="POST">
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="number" step="0.01" name="price" placeholder="Precio" required>
        <button type="submit">Agregar</button>
    </form>
</body>
</html>
