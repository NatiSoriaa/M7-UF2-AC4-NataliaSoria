<?php
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

$books = json_decode(file_get_contents("books.json"), true);
echo $twig->render("book_list.html.twig", ["books" => $books]);
?>
