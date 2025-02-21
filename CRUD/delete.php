<?php
session_start();
require_once('./connexion.php');
ob_start();

$id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);

if($id){
    try {
        $sql = "DELETE FROM users
            WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $affectedRows = $stmt->rowCount();
        if($affectedRows > 0){
            $_SESSION['msg'] = "<p style='color: #086'>Usuário apagado com sucesso!</p>";
            header("Location: index.php");
            return;
        }
    }catch (Exception $e) {
    }
}
