<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header("Location: login.php");
    exit();
}

require_once 'db_connection.php';
$query = "SELECT * FROM questions";
$result = $pdo->query($query);
$questions = $result->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete_question'])) {
    $question_id = $_POST['question_id'];
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = :question_id");
    $stmt->execute(['question_id' => $question_id]);
    header("Location: admin_panel.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Panel Administracyjny</h1>
    <a href="logout.php">Wyloguj</a>

    <h2>Pytania</h2>
    <ul>
        <?php foreach ($questions as $question): ?>
            <li>
                <?php echo $question['question_text']; ?>
                <form method="post">
                    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                    <button type="submit" name="delete_question">Usuń pytanie</button> <!-- Dodaj przycisk usuwania pytania -->
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Dodaj nowe pytanie</h2>
    <form method="post" action="add_question.php"> <!-- Formularz dodawania nowego pytania -->
        <label for="new_question">Treść pytania:</label>
        <input type="text" id="new_question" name="new_question" required>
        <button type="submit">Dodaj pytanie</button>
    </form>
</body>
</html>
