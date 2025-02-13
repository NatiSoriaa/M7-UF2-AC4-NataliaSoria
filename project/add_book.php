<?php
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $books = json_decode(file_get_contents("books.json"), true);
    $books[] = ["title" => $_POST["title"], "author" => $_POST["author"], "year" => $_POST["year"]];
    file_put_contents("books.json", json_encode($books, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

echo $twig->render("add_book.html.twig");
?>
