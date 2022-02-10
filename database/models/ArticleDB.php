<?php

class ArticleDB {

  private PDOStatement $statementCreateOne;
  private PDOStatement $statementUpdateOne;
  private PDOStatement $statementReadOne;
  private PDOStatement $statementDeleteOne;
  private PDOStatement $statementReadAll;
  private PDOStatement $statementReadUserAll;

  public function __construct(private PDO $pdo)
  {
    // STATEMENT POUR LA CREATION D'UN ARTICLE
    $this->statementCreateOne = $pdo->prepare("

      INSERT INTO article (
        title,
        category,
        content,
        image, 
        author
      ) VALUES (
        :title,
        :category,
        :content,
        :image,
        :author
      )
    
    "); 

    // STATEMENT POUR LA MODIFICATION DE L'ARTICLE
    $this->statementUpdateOne = $pdo->prepare("
        UPDATE article
        SET 
          title = :title,
          category = :category,
          content = :content,
          image = :image,
          author = :author
        WHERE id = :id
    ");

    // STATEMENT DE L'ARTICLE POUR LA RECUPERATION
    $this->statementReadOne = $pdo->prepare("SELECT article.*, user.firstname, user.lastname FROM article LEFT JOIN user ON article.author = user.id WHERE article.id = :id");
    
    // STATEMENT POUR LA RECUPERATION DE TOUS LES ARTICLES
    $this->statementReadAll = $pdo->prepare("SELECT article.*, user.firstname, user.lastname FROM article LEFT JOIN user ON article.author = user.id");

    // STATEMENT POUR LA SUPRESSION D'UN ARTICLE
    $this->statementDeleteOne = $pdo->prepare("DELETE FROM article WHERE id = :id");

    // STATEMENT POUR LA RECUPERATION DES ARTICLES D'UN USER DONNE
    $this->statementReadUserAll = $pdo->prepare("SELECT * FROM article WHERE author = :authorId");

  }


  public function fetchAll() : array {

    $this->statementReadAll->execute();

    return $this->statementReadAll->fetchAll();

  }

  public function fetchOne(string $id) : array {

    $this->statementReadOne->bindValue(":id", $id);

    $this->statementReadOne->execute();

    return $this->statementReadOne->fetch();

  }

  public function deleteOne(string $id) : string {

    $this->statementDeleteOne->bindValue(":id", $id);

    $this->statementDeleteOne->execute();

    return $id;

  }

  public function createOne($article) : array {

    $this->statementCreateOne->bindValue(":title", $article["title"]);
    $this->statementCreateOne->bindValue(":image", $article["image"]);
    $this->statementCreateOne->bindValue(":category", $article["category"]);
    $this->statementCreateOne->bindValue(":content", $article["content"]);
    $this->statementCreateOne->bindValue(":author", $article["author"]);

    $this->statementCreateOne->execute();

    return $this->fetchOne($this->pdo->lastInsertId());

  }

  public function updateOne($article) : array {

    $this->statementUpdateOne->bindValue(":title", $article["title"]);
    $this->statementUpdateOne->bindValue(":image", $article["image"]);
    $this->statementUpdateOne->bindValue(":category", $article["category"]);
    $this->statementUpdateOne->bindValue(":content", $article["content"]);
    $this->statementUpdateOne->bindValue(":author", $article["author"]);
    $this->statementUpdateOne->bindValue(":id", $article["id"]);

    $this->statementUpdateOne->execute();

    return $article;
  }

  public function fetchUserArticles(string $authorId) : array {
    $this->statementReadUserAll->bindValue(":authorId", $authorId);
    $this->statementReadUserAll->execute();

    return $this->statementReadUserAll->fetchAll();
  }

}


return new ArticleDB($pdo);