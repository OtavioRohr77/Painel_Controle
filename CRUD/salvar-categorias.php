<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Inicializa variáveis
$id = $nome = $descricao = "";

// Se veio um ID, estamos editando
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM categorias WHERE CategoriaID = $id";
    $resultado = mysqli_query($conexao, $sql);
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $nome = $linha['Nome'];
        $descricao = $linha['Descricao'];
    }
}

// Salvar ou atualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_post = mysqli_real_escape_string($conexao, $_POST['nome']);
    $descricao_post = mysqli_real_escape_string($conexao, $_POST['descricao']);

    if (!empty($_POST['id'])) {
        // Atualizar
        $id_post = intval($_POST['id']);
        $sql_update = "UPDATE categorias SET Nome='$nome_post', Descricao='$descricao_post' WHERE CategoriaID=$id_post";
        if (mysqli_query($conexao, $sql_update)) {
            echo "<p style='color: green;'>Categoria atualizada com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        // Inserir
        $sql_insert = "INSERT INTO categorias (Nome, Descricao) VALUES ('$nome_post', '$descricao_post')";
        if (mysqli_query($conexao, $sql_insert)) {
            echo "<p style='color: green;'>Categoria cadastrada com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }

    // Limpa campos
    $nome = $descricao = "";
}
?>

<main>
  <div class="container">
    <h2><?php echo isset($id) && $id ? "Editar Categoria" : "Cadastrar Categoria"; ?></h2>
    <form method="post" class="crud-form">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="text" name="nome" placeholder="Nome da Categoria" value="<?php echo $nome; ?>" required>
      <textarea name="descricao" placeholder="Descrição" required><?php echo $descricao; ?></textarea>
      <button type="submit">Salvar</button>
    </form>
    <a href="lista-categorias.php" class="btn btn-back">Voltar</a>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
