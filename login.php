<?php
session_start();
require_once 'db.php';


$message = "";

if (isset($_POST['login'])) {
    $username = sanitize($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, password, name FROM usuarios WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username; // Armazena o nome de usuário na sessão
            $_SESSION['name'] = $row['name']; // Armazena o nome completo na sessão
            header("Location: dashboard.php"); // Redirecionar para a página do usuário logado
        } else {
            $message = "Senha incorreta.";
        }
    } else {
        $message = "Usuário não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f5f5f5;
        }
        .container {
            margin-top: 150px;
        }
        .form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form label, .form input {
            display: block;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .by {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php echo $message; ?>
    <form method="post">
        <label for="username">Usuário:</label>
        <input type="text" name="username" required><br>
        <label for="password">Senha:</label>
        <input type="password" name="password" required><br>
        <br>
        <input type="submit" class="btn" name="login" value="Login">
    </form>
</body>
</html>
