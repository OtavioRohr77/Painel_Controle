<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Inicializa variáveis
$id = $nome = $teto = "";

// Se veio um ID, estamos editando
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM cargos WHERE CargoID = $id";
    $resultado = mysqli_query($conexao, $sql);
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $nome = $linha['Nome'];
        $teto = $linha['TetoSalarial'];
    }
}

// Salvar ou atualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_post = mysqli_real_escape_string($conexao, $_POST['nome']);
    $teto_post = floatval($_POST['teto']);

    if (!empty($_POST['id'])) {
        // Atualizar
        $id_post = intval($_POST['id']);
        $sql_update = "UPDATE cargos SET Nome='$nome_post', TetoSalarial=$teto_post WHERE CargoID=$id_post";
        if (mysqli_query($conexao, $sql_update)) {
            echo "<p style='color: green;'>Registro atualizado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        // Inserir
        $sql_insert = "INSERT INTO cargos (Nome, TetoSalarial) VALUES ('$nome_post', $teto_post)";
        if (mysqli_query($conexao, $sql_insert)) {
            echo "<p style='color: green;'>Cargo cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }
    // Limpa campos
    $nome = $teto = "";
}
?>

<main>
  <div class="container">
    <h2><?php echo isset($id) && $id ? "Editar Cargo" : "Cadastrar Cargo"; ?></h2>
    <form method="post" class="crud-form">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="text" name="nome" placeholder="Nome do Cargo" value="<?php echo $nome; ?>" required>
      <input type="number" name="teto" placeholder="Teto Salarial" value="<?php echo $teto; ?>" required step="0.01">
      <button type="submit">Salvar</button>
    </form>
    <a href="lista-cargos.php" class="btn btn-back">Voltar</a>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
