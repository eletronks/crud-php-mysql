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
    <title>Update CRUD </title>
</head>
<body>
    <a href="index.php">Listar</a><br>
    <a href="view.php?id=<?php echo $id;?>">Visualizar</a><br>

    <h1>Editar Usuário</h1>
    <?php
    $sql = "SELECT id, name, email FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    $row_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row_user){
        $_SESSION['msg'] = "<p style='color: #f00'>Usuário não encontrado!</p>";
        header("Location: index.php");
        return;
    }

    extract($row_user);

    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if(isset($data['csrf_token']) && hash_equals($_SESSION['csrf_tokens']
    ['form_update_user'], $data['csrf_token'])){
        try {
            $sql = "UPDATE users
                    SET name = :name, email = :email
                    WHERE id = :id";
            
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            
            if($stmt->execute()){
                $_SESSION['msg'] = "<p style='color: #086;'>Usuário editado com sucesso!</p>";
                header("Location: view.php?id=$id");
                return;
            }else{
                echo "<p style='color: #f00'>Usuário não editado</p>";
            }
        } catch(Exception $e) {
            echo "<p style='color: #f00;'>Usuário não editado</p>";
        }
    }
    ?>
    <form method="POST" action="">
        <?php 
        // A função random_bytes gera uma sequência de 32 bytes aleatórios.
        // A função bin2hex converte os bytes binários gerados pela random_bytes em uma representação hexadecimal.
        $token = bin2hex(random_bytes(32));

        // Salvar o token CSRF na sessão
        $_SESSION['csrf_tokens']['form_update_user'] = $token;
        ?>
        <input type="hidden" name="csrf_token" value="<?php echo $token;?>">

        <input type="hidden" name="id" value="<?php echo $id;?>">

        <label>Nome:</label>
        <input type="text" name="name" placeholder="Nome Completo" required value="<?php echo $data['name'] ?? $name;  ?>"><br><br>

        <label>E-mail:</label>
        <input type="email" name="email" placeholder="Seu melhor email" required value="<?php echo $data['email'] ?? $email;  ?>"><br><br>

        <input type="submit" value="Salvar"><br><br>
    </form>
</body>
</html>