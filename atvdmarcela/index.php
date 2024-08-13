<?php
require 'database.php'; // Inclui o arquivo de conexão com o banco de dados
require 'Quadrado.php'; // Inclui o arquivo da classe Quadrado

$search = ''; // Variável para armazenar a consulta de busca

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search']; // Obtém o valor da busca se fornecido
}

// Consulta SQL para buscar quadrados
$query = "SELECT * FROM quadrado WHERE lado LIKE ? OR cor LIKE ?";
$searchParam = "%{$search}%";
$stmt = $conn->prepare($query); // Prepara a consulta SQL
$stmt->bind_param("ss", $searchParam, $searchParam); // Vincula os parâmetros à consulta
$stmt->execute(); // Executa a consulta
$result = $stmt->get_result(); // Obtém o resultado da consulta
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Quadrados</title>
    <style>
        .quadrado {
            border: 1px solid #000;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 10px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            position: relative;
        }
        .acoes {
            position: absolute;
            bottom: 5px;
            right: 5px;
            font-size: 12px;
        }
        .quadrado-container {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Quadrado</h1>
    <form action="create.php" method="post">
        <label for="lado">Lado:</label>
        <input type="text" name="lado" id="lado" required>
        <label for="cor">Cor:</label>
        <input type="color" name="cor" id="cor" value="#FFFFE0" required>
        <label for="unidade">Unidade de Medida:</label>
        <select name="unidade" id="unidade" required>
            <option value="px">Pixels</option>
            <option value="cm">Centímetros</option>
            <option value="mm">Milímetros</option>
            <option value="in">Polegadas</option>
        </select>
        <input type="submit" value="Cadastrar">
    </form>

    <h2>Buscar Quadrados</h2>
    <form action="index.php" method="get">
        <label for="search">Buscar por Lado ou Cor:</label>
        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Buscar">
    </form>

    <h2>Listagem de Quadrados</h2>
    <div class="quadrado-container">
        <?php
        while ($row = $result->fetch_assoc()) {
            $quadrado = new Quadrado($row['lado'], $row['unidade'], $row['id']); // Cria uma instância da classe Quadrado
            $lado = $quadrado->getLado();
            $unidade = $quadrado->getUnidade();
            $cor = $row['cor'];
            echo "<div class='quadrado' style='width: {$lado}{$unidade}; height: {$lado}{$unidade}; background-color: {$cor};'>";
            echo "ID: " . $quadrado->getId() . "<br>";
            echo "Lado: " . $lado . $unidade . "<br>";
            echo "Área: " . $quadrado->calcularArea() . " " . ($unidade == 'px' ? 'px²' : ($unidade == 'cm' ? 'cm²' : ($unidade == 'mm' ? 'mm²' : 'in²'))) . "<br>";
            echo "Perímetro: " . $quadrado->calcularPerimetro() . " " . ($unidade == 'px' ? 'px' : ($unidade == 'cm' ? 'cm' : ($unidade == 'mm' ? 'mm' : 'in'))) . "<br>";
            echo "<div class='acoes'>";
            echo "<a href='edit.php?id=" . $quadrado->getId() . "'>Editar</a> | ";
            echo "<a href='delete.php?id=" . $quadrado->getId() . "'>Excluir</a>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
