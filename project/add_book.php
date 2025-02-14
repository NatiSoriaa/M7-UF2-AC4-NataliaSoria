<?php
require_once 'vendor/autoload.php';//carga del twig

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');//configuramos el twig al igual que en el index
$twig = new \Twig\Environment($loader);

//Formulario para agregar libros
if ($_SERVER["REQUEST_METHOD"] === "POST") {//comprueba que el formulario se envia por metodo POST
    $books = json_decode(file_get_contents("books.json"), true);//si fue enviado carga los libros desde el JSON books.json
    
    //Agrega un nuevo libro por metodo POST que incluya titulo autor y año
    $books[] = ["title" => $_POST["title"], "author" => $_POST["author"], "year" => $_POST["year"]];
    //se guardan los libros creados en el JSON 
    file_put_contents("books.json", json_encode($books, JSON_PRETTY_PRINT));
    header("Location: index.php");//Redirige a la pagina principal despues de agregar un libro
    exit;
}
// Renderiza el formulario para agregar libros
echo $twig->render("add_book.html.twig");
?>
