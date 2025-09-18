<?php
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// --- Inserção ou atualização ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $produtoID = intval($_POST['ProdutoID']);
    $funcionarioID = intval($_POST['FuncionarioID']);
    $dataProducao = $_POST['DataProducao'];

    if ($id > 0) {
        // Atualização
        $sql_update = "UPDATE producao 
                       SET ProdutoID=$produtoID, FuncionarioID=$funcionarioID, DataProducao='$dataProducao' WHERE ProducaoID=$id";
        if (mysqli_query($conexao, $sql_update)) {
            echo "<p style='color: green;'>Produção atualizada com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        // Inserção
        $sql_insert = "INSERT INTO producao (ProdutoID, FuncionarioID, DataProducao, Quantidade)
                       VALUES ($produtoID, $funcionarioID, '$dataProducao', $quantidade)";
        if (mysqli_query($conexao, $sql_insert)) {
            echo "<p style='color: green;'>Produção cadastrada com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }
}

// --- Carregar dados para edição ---
$producao = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM producao WHERE ProducaoID=$id";
    $res = mysqli_query($conexao, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $producao = mysqli_fetch_assoc($res);
    }
}

// Buscar produtos
$sql_produtos = "SELECT ProdutoID, Nome FROM produtos";
$res_produtos = mysqli_query($conexao, $sql_produtos);

// Buscar funcionários
$sql_funcionarios = "SELECT FuncionarioID, Nome FROM funcionarios";
$res_funcionarios = mysqli_query($conexao, $sql_funcionarios);
?>

<main>
  <div id="producao" class="tela">
      <form class="crud-form" method="post" action="">
        <h2><?php echo $producao ? "Editar Produção" : "Cadastro de Produção"; ?></h2>

        <?php if ($producao) { ?>
          <input type="hidden" name="id" value="<?php echo $producao['ProducaoID']; ?>">
        <?php } ?>

        <label>Funcionário</label>
        <select name="FuncionarioID" required>
          <option value="">Selecione o Funcionário</option>
          <?php
          if ($res_funcionarios && mysqli_num_rows($res_funcionarios) > 0) {
              while ($row = mysqli_fetch_assoc($res_funcionarios)) {
                  $selected = ($producao && $producao['FuncionarioID'] == $row['FuncionarioID']) ? "selected" : "";
                  echo '<option value="'.$row['FuncionarioID'].'" '.$selected.'>'.$row['Nome'].'</option>';
              }
          }
          ?>
        </select>

        <label>Produto</label>
        <select name="ProdutoID" required>
          <option value="">Selecione o Produto</option>
          <?php
          if ($res_produtos && mysqli_num_rows($res_produtos) > 0) {
              while ($row = mysqli_fetch_assoc($res_produtos)) {
                  $selected = ($producao && $producao['ProdutoID'] == $row['ProdutoID']) ? "selected" : "";
                  echo '<option value="'.$row['ProdutoID'].'" '.$selected.'>'.$row['Nome'].'</option>';
              }
          }
          ?>
        </select>

        <label>Data da Produção</label>
        <input type="date" name="DataProducao" value="<?php echo $producao ? $producao['DataProducao'] : ''; ?>" required>

        <button type="submit">Salvar</button>
      </form>
  </div>
</main>

<?php
mysqli_close($conexao);
include_once './include/footer.php';
?>
