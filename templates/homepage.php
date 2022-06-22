<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Le blog de l'AVBN</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>
    <h1>Le super blog de l'AVBN !</h1>
    <p>Derniers billets du blog :</p>

    <?php
    foreach ($posts as $post) {
    ?>
        <div class="news">
            <h3>
                <?php echo htmlspecialchars($post['title']); ?>
                <em>le <?php echo $post['french_creation_date']; ?></em>
            </h3>
            <p>
                <?php
                // On affiche le contenu du billet
                echo nl2br(htmlspecialchars($post['content']));
                ?>
                <br />
                <em><a href="#">Commentaires</a></em>
            </p>
        </div>
    <?php
    } // Fin de la boucle des billets
    ?>
</body>

</html>