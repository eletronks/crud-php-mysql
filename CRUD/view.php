<?php
session_start();
ob_start();
require_once('./connexion.php');
$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View CRUD</title>
</head>
<body>
    <a href="index.php">Listar</a><br>
    <a href="update.php?id=<?php echo $id;?>">Editar</a><br>
    <a href="delete.php?id=<?php echo $id;?>">Apagar</a><br>

    <h1>Visualizar Usuário</h1>

    <?php
    if (isset($_SESSION['msg'])) { // Verificar se existe a mensagem de sucesso ou erro
        echo $_SESSION['msg']; // Imprimir a mensagem de sucesso ou erro

        unset($_SESSION['msg']); // Destruir a mensagem de sucesso ou erro
    }
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT id, name, email FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    $row_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row_user ?? false) {
        extract($row_user);
        echo "ID: $id<br>";
        echo "Nome: $name<br>";
        echo "Email: $email<br>";
    } else {
        $_SESSION['msg'] = "<p style='color: #f00'>Usuário não encontrado!</p>";
        header("Location: index.php");
    }
    ?>
</body>
</html>