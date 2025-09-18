<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Inicializa variáveis
$id = $nome = $preco = $peso = $descricao = $CategoriaID = "";

// Se veio um ID, estamos editando
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM produtos WHERE ProdutoID = $id";
    $resultado = mysqli_query($conexao, $sql);
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $nome = $linha['Nome'];
        $preco = $linha['Preco'];
        $peso = $linha['Peso'];
        $descricao = $linha['Descricao'];
        $CategoriaID = $linha['CategoriaID'];
    }
}

// Salvar ou atualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_post = mysqli_real_escape_string($conexao, $_POST['nome']);
    $preco_post = floatval($_POST['preco']);
    $peso_post = floatval($_POST['peso']);
    $descricao_post = mysqli_real_escape_string($conexao, $_POST['descricao']);
    $CategoriaID_post = intval($_POST['CategoriaID']);

    if (!empty($_POST['id'])) {
        // Atualizar
        $id_post = intval($_POST['id']);
        $sql_update = "UPDATE produtos SET 
                        Nome='$nome_post', 
                        Preco=$preco_post, 
                        Peso=$peso_post, 
                        Descricao='$descricao_post', 
                        CategoriaID=$CategoriaID_post 
                       WHERE ProdutoID=$id_post";
        if (mysqli_query($conexao, $sql_update)) {
            echo "<p style='color: green;'>Produto atualizado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        // Inserir
        $sql_insert = "INSERT INTO produtos (Nome, Preco, Peso, Descricao, CategoriaID) 
                       VALUES ('$nome_post', $preco_post, $peso_post, '$descricao_post', $CategoriaID_post)";
        if (mysqli_query($conexao, $sql_insert)) {
            echo "<p style='color: green;'>Produto cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }

    // Limpa campos
    $nome = $preco = $peso = $descricao = $CategoriaID = "";
}
?>

<main>
  <div class="container">
    <h2><?php echo isset($id) && $id ? "Editar Produto" : "Cadastrar Produto"; ?></h2>
    <form method="post" class="crud-form">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="text" name="nome" placeholder="Nome do Produto" value="<?php echo $nome; ?>" required>
      <input type="number" name="preco" placeholder="Preço" value="<?php echo $preco; ?>" step="0.01" required>
      <input type="number" name="peso" placeholder="Peso (g)" value="<?php echo $peso; ?>" step="0.01" required>
      <textarea name="descricao" placeholder="Descrição" required><?php echo $descricao; ?></textarea>

      <select name="CategoriaID" required>
        <option value="">Selecione a Categoria</option>
        <?php
        $sql_cat = "SELECT CategoriaID, Nome FROM categorias";
        $res_cat = mysqli_query($conexao, $sql_cat);
        if ($res_cat && mysqli_num_rows($res_cat) > 0) {
            while ($row = mysqli_fetch_assoc($res_cat)) {
                $selected = ($row['CategoriaID'] == $CategoriaID) ? "selected" : "";
                echo '<option value="'.$row['CategoriaID'].'" '.$selected.'>'.$row['Nome'].'</option>';
            }
        }
        ?>
      </select>

      <button type="submit">Salvar</button>
    </form>
    <a href="lista-produtos.php" class="btn btn-back">Voltar</a>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
