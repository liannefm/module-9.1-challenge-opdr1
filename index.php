<?php

require_once 'vendor/autoload.php';

require_once 'functions.php';

$error = '';
$name = '';
$email = '';
$comment = '';

// Alleen verwerken als het formulier ook echt verzonden is (POST-request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal de ingevulde waarden op uit het formulier
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $comment = trim($_POST['comment']);

    // Validatie: check of de velden niet leeg zijn, en of de email geldig is
    if (empty($name) || empty($email) || empty($comment)) {
        $error = 'Vul alle velden in.';
    } elseif (!is_valid_email($email)) {
        $error = 'Vul een geldig e-mailadres in.';
    } else {
        // Alles is geldig: comment opslaan
        save_comment($pdo, $name, $email, $comment);

        // Reset de velden zodat het formulier weer leeg is na een succesvolle post
        $name = '';
        $email = '';
        $comment = '';

        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}

// Comments ophalen om straks te tonen (dit doen we altijd, ongeacht POST of niet)
$comments = get_comments($pdo);

?>

<!DOCTYPE html>
<html lang="nl"></html>
<head>
        <meta charset="UTF-8">
    <title>Video met comments</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Mijn video</h1>

    <!-- YouTube embed -->
<iframe width="560" height="315"
    src="https://www.youtube.com/embed/yaapnjOofXI"
    title="YouTube video"
    frameborder="0"
    allowfullscreen>
</iframe>

<h2> Plaats een reactie</h2>

<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

        <form method="post">
        <label for="name">Naam:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>

        <label for="comment">Reactie:</label><br>
        <textarea id="comment" name="comment"><?php echo htmlspecialchars($comment); ?></textarea><br>

        <button type="submit">Versturen</button>
    </form>

<h2>Reacties</h2>

    <?php if (empty($comments)): ?>
        <p>Nog geen reacties. Wees de eerste!</p>
    <?php else: ?>
        <?php foreach ($comments as $c): ?>
            <div class="comment">
                <strong><?php echo htmlspecialchars($c['name']); ?></strong>
                <span>
                    <?php echo \Carbon\Carbon::parse($c['created_at'])->diffForHumans(); ?>
                </span>
                <p><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>