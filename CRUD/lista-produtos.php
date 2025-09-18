<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Exclusão
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $sql_delete = "DELETE FROM produtos WHERE ProdutoID = $id";
    if (mysqli_query($conexao, $sql_delete)) {
        echo "<p style='color: green;'>Registro $id excluído com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
    }
}
?>

<main>
  <div class="container">
      <h1>Lista de Produtos</h1>
      <a href="salvar-produtos.php" class="btn btn-add">Incluir Produto</a>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT p.ProdutoID, p.Nome, p.Preco, c.Nome AS CategoriaNome 
                FROM produtos p
                LEFT JOIN categorias c ON p.CategoriaID = c.CategoriaID";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha["ProdutoID"] . "</td>";
                echo "<td>" . $linha["Nome"] . "</td>";
                echo "<td>" . $linha["CategoriaNome"] . "</td>";
                echo "<td>" . $linha["Preco"] . "</td>";
                echo "<td>
                        <a href='salvar-produtos.php?id=" . $linha["ProdutoID"] . "' class='btn btn-edit'>Editar</a>
                        <a href='lista-produtos.php?excluir=" . $linha["ProdutoID"] . "' class='btn btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir este produto?')\">Excluir</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum produto encontrado</td></tr>";
        }

        mysqli_close($conexao);
        ?>
        </tbody>
      </table>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
