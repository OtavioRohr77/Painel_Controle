<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Inicializa variáveis
$id = $nome = $andar = $cor = "";

// Se veio um ID, estamos editando
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM setor WHERE SetorID = $id";
    $resultado = mysqli_query($conexao, $sql);
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $nome = $linha['Nome'];
        $andar = $linha['Andar'];
        $cor = $linha['Cor'];
    }
}

// Salvar ou atualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_post = mysqli_real_escape_string($conexao, $_POST['nome']);
    $andar_post = mysqli_real_escape_string($conexao, $_POST['andar']);
    $cor_post = mysqli_real_escape_string($conexao, $_POST['cor']);

    if (!empty($_POST['id'])) {
        // Atualizar
        $id_post = intval($_POST['id']);
        $sql_update = "UPDATE setor SET Nome='$nome_post', Andar='$andar_post', Cor='$cor_post' WHERE SetorID=$id_post";
        if (mysqli_query($conexao, $sql_update)) {
            echo "<p style='color: green;'>Registro atualizado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        // Inserir
        $sql_insert = "INSERT INTO setor (Nome, Andar, Cor) VALUES ('$nome_post', '$andar_post', '$cor_post')";
        if (mysqli_query($conexao, $sql_insert)) {
            echo "<p style='color: green;'>Setor cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }

    // Limpa campos
    $nome = $andar = $cor = "";
}
?>

<main>
  <div class="container">
    <h2><?php echo isset($id) && $id ? "Editar Setor" : "Cadastrar Setor"; ?></h2>
    <form method="post" class="crud-form">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="text" name="nome" placeholder="Nome do Setor" value="<?php echo $nome; ?>" required>
      <input type="text" name="andar" placeholder="Andar" value="<?php echo $andar; ?>" required>
      <input type="text" name="cor" placeholder="Cor" value="<?php echo $cor; ?>" required>
      <button type="submit">Salvar</button>
    </form>
    <a href="lista-setores.php" class="btn btn-back">Voltar</a>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
