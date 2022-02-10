<?php

require_once __DIR__."/database/database.php";
$authDB = require_once __DIR__."/database/security.php";
$currentUser = $authDB->isLoggedin();
$articleDB = require_once __DIR__."/database/models/ArticleDB.php";
$articles = $articleDB->fetchAll();
$categories = [];

// protection du paramètre
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);

$selectedCat = $_GET["cat"] ?? "";

//je teste si il y a du contenu dans le tableau d'article
if (count($articles)) {
  $cattmp = array_map(fn($a) => $a["category"], $articles);

  // le nombre d'article par catégorie
  $categories = array_reduce($cattmp, function($acc, $cat) {

    if(isset($acc[$cat])) {
      $acc[$cat]++;
    } else {
      $acc[$cat] = 1;
    }

    return $acc;

  }, []);

  // grouper toutes les articles par catégorie
  $articlePerCategories = array_reduce($articles, function($acc, $article) {

    if(isset($acc[$article["category"]])) {

      $acc[$article["category"]] = [...$acc[$article["category"]], $article];

    } else {
      $acc[$article["category"]] = [$article];
    }

    return $acc;

  }, []);

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <?php require_once "includes/head.php" ?> 
  <link rel="stylesheet" href="/public/css/index.css">
  <title>Blog</title>
</head>
<body>
  <div class="container">
    <?php require_once "includes/header.php" ?>
    <div class="content">
      <h1 style="font-weight: 700; font-size: 7rem;">Oops une erreur est survenue</h1>
    </div>
    <?php require_once "includes/footer.php" ?>
  </div>
</body>
</html>