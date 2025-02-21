<?php
session_start();
ob_start();
require_once('./connexion.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read CRUD</title>
</head>
<body>
    <a href="create.php">Cadastrar</a><br>
    <h1>Listar Usuários</h1>

    <?php
    if (isset($_SESSION['msg'])) { // Verificar se existe a mensagem de sucesso ou erro
        echo $_SESSION['msg']; // Imprimir a mensagem de sucesso ou erro

        unset($_SESSION['msg']); // Destruir a mensagem de sucesso ou erro
    }

    $sql = "SELECT id, name, email FROM users"; // Criar a QUERY listar usuários

    $stmt = $conn->prepare($sql); // Preparar a QUERY

    $stmt->execute();

    while($row_user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row_user);

        echo "ID: $id<br>";
        echo "Nome: $name<br>";
        echo "Email: $email<br>";

        echo "<a href='view.php?id=$id'>Visualizar</a><br>";
        echo "<a href='update.php?id=$id'>Editar</a><br>";
        echo "<a href='delete.php?id=$id'>Apagar</a><br>";

        echo "<hr>";
    }
    ?>
</body>
</html>