<?php
session_start();
require_once 'db.php';


$message = "";

if (isset($_POST['signup'])) {
    $username = sanitize($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (username, password) VALUES ('$username', '$password')";
    if ($conn->query($sql)) {
                echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php';</script>";

    } else {
        echo "<script>alert('Cadastro realizado com sucesso!')" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
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
    <h1>Cadastro</h1>
    <?php echo $message; ?>
    <form method="post">
        <label for="name">Nome:</label>
        <input type="text" name="name" required><br>        
        <label for="username">Usuário:</label>
        <input type="text" name="username" required><br>
        <label for="password">Senha:</label>
        <input type="password" name="password" required><br>
        <br>
        <input type="submit" class="btn" name="signup" value="Cadastrar">
    </form>
</body>
</html>
