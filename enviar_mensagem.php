<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

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
    <title>Enviar Mensagem</title>
</head>
<body>
    <h1>Enviar Mensagem</h1>
    <?php if (isset($envio_sucesso)) echo "<p>$envio_sucesso</p>"; ?>
    <?php if (isset($envio_erro)) echo "<p>$envio_erro</p>"; ?>
    
    <form method="post">
        <label for="destinatario_id">Destinatário (ID):</label>
        <input type="number" name="destinatario_id" required><br>
        <label for="mensagem">Mensagem:</label>
        <textarea name="mensagem" rows="4" required></textarea><br>
        <input type="submit" name="enviar" value="Enviar Mensagem">
    </form>
</body>
</html>
