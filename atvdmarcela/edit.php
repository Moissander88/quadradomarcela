<?php
require 'database.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lado = $_POST['lado'];
    $cor = $_POST['cor'];
    $unidade = $_POST['unidade'];

    if (is_numeric($lado) && $lado > 0 && preg_match('/^#[0-9A-Fa-f]{6}$/', $cor)) {
        $query = "UPDATE quadrado SET lado = ?, cor = ?, unidade = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("dssi", $lado, $cor, $unidade, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, insira valores válidos para o lado e a cor.";
    }
} else {
    $query = "SELECT * FROM quadrado WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quadrado = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Quadrado</title>
</head>
<body>
    <h1>Editar Quadrado</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="post">
        <label for="lado">Lado:</label>
        <input type="text" name="lado" id="lado" value="<?php echo $quadrado['lado']; ?>" required>
        <label for="cor">Cor:</label>
        <input type="color" name="cor" id="cor" value="<?php echo $quadrado['cor']; ?>" required>
        <label for="unidade">Unidade de Medida:</label>
        <select name="unidade" id="unidade" required>
            <option value="px" <?php if ($quadrado['unidade'] == 'px') echo 'selected'; ?>>Pixels</option>
            <option value="cm" <?php if ($quadrado['unidade'] == 'cm') echo 'selected'; ?>>Centímetros</option>
            <option value="mm" <?php if ($quadrado['unidade'] == 'mm') echo 'selected'; ?>>Milímetros</option>
            <option value="in" <?php if ($quadrado['unidade'] == 'in') echo 'selected'; ?>>Polegadas</option>
        </select>
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>
