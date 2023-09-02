<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];// Obtém o id do usuário logado
$session_id = session_id(); // Obtém o ID da sessão

    $sql_user = "SELECT username FROM usuarios WHERE id = $user_id";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        $username_logged = $row_user['username'];
    }

// Obtém mensagens recebidas com detalhes do remetente
    $sql = "SELECT m.id, m.remetente_id, u.username AS remetente_username, u.name AS remetente_name, m.mensagem, m.data_envio
            FROM mensagens m
            JOIN usuarios u ON m.remetente_id = u.id
            WHERE m.destinatario_id = $user_id
            ORDER BY m.data_envio DESC";
    $result = $conn->query($sql);

if (isset($_POST['enviar'])) {
    $destinatario_id = $_POST['destinatario_id'];
    $mensagem = $_POST['mensagem'];
    
    $mensagem = sanitize($conn, $mensagem);
    
    $sql = "INSERT INTO mensagens (remetente_id, destinatario_id, mensagem, data_envio)
            VALUES ($user_id, $destinatario_id, '$mensagem', NOW())";
    
    if ($conn->query($sql)) {
        $envio_sucesso = "Mensagem enviada com sucesso!";
    } else {
        $envio_erro = "Erro ao enviar a mensagem: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            margin-bottom: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .message-info {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Bem-vindo ao Dashboard, <?php echo $username_logged; ?></h1>
    
    <?php if (isset($envio_sucesso)) echo "<p>$envio_sucesso</p>"; ?>
    <?php if (isset($envio_erro)) echo "<p>$envio_erro</p>"; ?>
    
    <h2>Enviar Mensagem</h2>
    <form method="post" style="border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
        <label for="destinatario_id">Destinatário (ID):</label><br>
        <input type="number" name="destinatario_id" required><br><br>
        
        <label for="mensagem">Mensagem:</label><br>
        <textarea name="mensagem" rows="4" required style="width: 100%;"></textarea><br><br>
        
        <input type="submit" name="enviar" value="Enviar Mensagem" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; cursor: pointer;">
    </form>
    
    <h2>Mensagens Recebidas</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<p class='message-info'><strong>De:</strong> " . $row['remetente_name'] . " (" . $row['remetente_username'] . ")</p>";
                echo "<p><strong>Mensagem:</strong><br>" . nl2br($row['mensagem']) . "</p>";
                echo "<p><strong>Data:</strong> " . $row['data_envio'] . "</p>";
                echo "</li>";
            }
        } else {
            echo "<li>Nenhuma mensagem recebida.</li>";
        }
        ?>
    </ul>

    <p>CHAVE DE SESSÃO</p>
    <p>ID da Sessão: <span style="color: red;"><?php echo $session_id; ?></span></p>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
