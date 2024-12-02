<?php
require 'db_connection.php';
require 'header.php';
if (!(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete_user'])) {
    $userId = intval($_GET['delete_user']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    header("Location: admin_customers.php");
    exit;
}

$customers = $pdo->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);

$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['customer-edit'])) {
        $customerId = intval($_POST['id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $notes = trim($_POST['notes']);

        if ($customerId > 0) {
            $stmt = $pdo->prepare("UPDATE customers SET name = :name, email = :email, phone = :phone, notes = :notes WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'notes' => $notes,
                'id' => $customerId
            ]);
            echo "<p class='success-message'>Dane klienta zostały zaktualizowane.</p>";
        }
    }

    if (isset($_POST['user-edit'])) {
        $userId = intval($_POST['id']);
        $username = trim($_POST['username']);
        $role = trim($_POST['role']);

        if ($userId > 0) {
            $stmt = $pdo->prepare("UPDATE users SET username = :username, role = :role WHERE id = :id");
            $stmt->execute([
                'username' => $username,
                'role' => $role,
                'id' => $userId
            ]);
            echo "<p class='success-message'>Dane użytkownika zostały zaktualizowane.</p>";
        }
    }

    if (isset($_POST['user-create'])) {
        $username = trim($_POST['username']);
        $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
        $role = trim($_POST['role']);
    
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $password,
                'role' => $role
            ]);
            
            
            header("Location: admin_customers.php?success=user_created");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                echo "<p class='error-message'>Nazwa użytkownika już istnieje.</p>";
            } else {
                echo "<p class='error-message'>Wystąpił błąd: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie klientami i użytkownikami</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 4em;
        }

        h2 {
            font-size: 32px;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
        .col {
            display: flex;
            flex-direction: column;
        }
        .row {
            display: flex;
            flex-direction: row;
        }
    </style>
</head>
<body>
<section>
    <h2>Zarządzanie klientami i użytkownikami</h2>

    <div>
        <h2>Klienci</h2>
        <form method="POST" id="customer-form">
            <input type="hidden" name="id" id="customer-id">
            <label>Imię i nazwisko:
                <input type="text" name="name" id="customer-name" required>
            </label>
            <label>Email:
                <input type="email" name="email" id="customer-email" required>
            </label>
            <label>Telefon:
                <input type="text" name="phone" id="customer-phone">
            </label>
            <label>Notatki:
                <textarea style="width: 200px; height: 30px; resize: none;" name="notes" id="customer-notes"></textarea>
            </label>
            <button type="submit" name="customer-edit" id="form-submit">Zaktualizuj klienta</button>
        </form>
    </div>

    <table style="margin-top:20px;" border="1">
        <tr>
            <th>ID</th>
            <th>Imię i nazwisko</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Notatki</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?= $customer['id'] ?></td>
            <td><?= htmlspecialchars($customer['name']) ?></td>
            <td><?= htmlspecialchars($customer['email']) ?></td>
            <td><?= htmlspecialchars($customer['phone']) ?></td>
            <td><?= htmlspecialchars($customer['notes']) ?></td>
            <td>
                <button onclick="editCustomer(
                    <?= $customer['id'] ?>,
                    '<?= htmlspecialchars($customer['name'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($customer['email'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($customer['phone'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($customer['notes'], ENT_QUOTES) ?>'
                )">Edytuj</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Użytkownicy</h2>

    <div class="row" style="gap: 2em;">
        <div class="col">
            <h2>Dodaj nowego użytkownika</h2>
            <form method="POST" id="user-create-form">
                <label>Nazwa użytkownika:
                    <input type="text" name="username" required>
                </label>
                <label>Hasło:
                    <input type="password" name="password" required>
                </label>
                <label>Rola:
                    <select name="role" required>
                        <option value="user">Użytkownik</option>
                        <option value="employee">Pracownik</option>
                        <option value="admin">Administrator</option>
                    </select>
                </label>
                <button type="submit" name="user-create">Dodaj użytkownika</button>
            </form>
        </div>

        <div class="col">
            <h2>Edytuj użytkownika</h2>
            <form method="POST" id="user-edit-form">
                <input type="hidden" name="id" id="user-id">
                <label>Nazwa użytkownika:
                    <input type="text" name="username" id="user-username" required>
                </label>
                <label>Rola:
                    <select name="role" id="user-role">
                        <option value="user">Użytkownik</option>
                        <option value="employee">Pracownik</option>
                        <option value="admin">Administrator</option>
                    </select>
                </label>
                <button type="submit" name="user-edit" id="user-submit">Zaktualizuj użytkownika</button>
            </form>
        </div>
    </div>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nazwa użytkownika</th>
            <th>Rola</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <button onclick="editUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>', '<?= htmlspecialchars($user['role'], ENT_QUOTES) ?>')">Edytuj</button>
                <a href="?delete_user=<?= $user['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">Usuń</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

        <a href="admin_panel.php"><button style="margin-top: 20px;" class="nav-btn">Powrót do panelu</button></a>
    </section>

    <script>
        function editCustomer(id, name, email, phone, notes) {
            document.getElementById('customer-id').value = id;
            document.getElementById('customer-name').value = name;
            document.getElementById('customer-email').value = email;
            document.getElementById('customer-phone').value = phone;
            document.getElementById('customer-notes').value = notes;

            document.getElementById('form-submit').textContent = 'Zaktualizuj klienta';
        }

        function editUser(id, username, role) {
            document.getElementById('user-id').value = id;
            document.getElementById('user-username').value = username;
            document.getElementById('user-role').value = role;

            document.getElementById('user-submit').textContent = 'Zaktualizuj użytkownika';
            document.getElementById('user-edit-form').scrollIntoView();
        }
    </script>
</body>
</html>
<?php require 'footer.php'; ?>
