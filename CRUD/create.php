<?php
session_start();
require_once('./connexion.php');
ob_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create CRUD</title>
</head>
<body>
    <a href="index.php">Listar</a><br>

    <h1>Cadastrar Usuário</h1>
    <?php
    // Receber dados do formulario
    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    // Verificar se o token CSRF é válido
    if(isset($data['csrf_token']) && hash_equals($_SESSION['csrf_tokens']
    ['form_create_user'], $data['csrf_token'])){

        // Tratar exceções e erros
        try{
            // Criar a QUERY cadastrar usuário
            $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";

            // Preparar a QUERY
            $stmt = $conn->prepare($sql);
            
            // Substituir os links da QUERY pelos valores
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);

            // Executar a QUERY
            $stmt->execute();
            
            // Acessa o IF quando cadastrar o registro no banco de dados
            if ($stmt->rowCount()){
                // Recuperar o ID do registro cadastrado
                $lastID = $conn->lastInsertId();

                // Excluir os dados da variável $data
                unset($data);
                
                echo "<p style='color: #086'>Usuário cadastrado com sucesso! ID do registro: $lastID</p>";
            }else{
                echo "<p style='color: #f00>Usuário não cadastrado</p>";
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    ?>

    <form method="POST" action="">
        <?php 
        // A função random_bytes gera uma sequência de 32 bytes aleatórios.
        // A função bin2hex converte os bytes binários gerados pela random_bytes em uma representação hexadecimal.
        $token = bin2hex(random_bytes(32));

        // Salvar o token CSRF na sessão
        $_SESSION['csrf_tokens']['form_create_user'] = $token;
        ?>
        <input type="hidden" name="csrf_token" value="<?php echo $token;?>">

        <label>Nome:</label>
        <input type="text" name="name" placeholder="Nome Completo" required value="<?php echo $data['name'] ?? '';  ?>"><br><br>

        <label>E-mail:</label>
        <input type="email" name="email" placeholder="Seu melhor email" required value="<?php echo $data['email'] ?? '';  ?>"><br><br>

        <input type="submit" value="Cadastrar"><br><br>
    </form>
</body>
</html>