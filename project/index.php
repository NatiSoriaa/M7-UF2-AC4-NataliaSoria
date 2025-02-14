<?php
//el index.php lista los libros utilizando Twig
//instalacion de twig en el cmd: composer require twig/twig   se hace dentro de la carpeta project

require_once 'vendor/autoload.php'; // Carga del Twig desde el archivo autoload

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');// se configura el Twig para usar la carpeta templates
$twig = new \Twig\Environment($loader);

$books = json_decode(file_get_contents("books.json"), true);// Carga la lista de libros desde el JSON

echo $twig->render("book_list.html.twig", ["books" => $books]);// Se carga la plantilla Twig con la lista de libros
?>
