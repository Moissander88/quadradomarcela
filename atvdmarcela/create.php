<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lado = $_POST['lado'];
    $cor = $_POST['cor'];
    $unidade = $_POST['unidade'];

    if (is_numeric($lado) && $lado > 0 && preg_match('/^#[0-9A-Fa-f]{6}$/', $cor)) {
        $query = "INSERT INTO quadrado (lado, cor, unidade) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("dss", $lado, $cor, $unidade);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, insira valores válidos para o lado e a cor.";
    }
}

$conn->close();
?>
