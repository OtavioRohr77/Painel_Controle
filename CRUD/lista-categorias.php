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
    $sql_delete = "DELETE FROM categorias WHERE CategoriaID = $id";
    if (mysqli_query($conexao, $sql_delete)) {
        echo "<p style='color: green;'>Registro $id excluído com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
    }
}
?>
<main>
  <div class="container">
      <h1>Lista de Categorias</h1>
      <a href="salvar-categorias.php" class="btn btn-add">Incluir</a>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT CategoriaID, Nome, Descricao FROM categorias";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha["CategoriaID"] . "</td>";
                echo "<td>" . $linha["Nome"] . "</td>";
                echo "<td>" . $linha["Descricao"] . "</td>";
                echo "<td>
                        <a href='salvar-categorias.php?id=" . $linha["CategoriaID"] . "' class='btn btn-edit'>Editar</a>
                        <a href='lista-categorias.php?excluir=" . $linha["CategoriaID"] . "' class='btn btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir esta categoria?')\">Excluir</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhuma categoria encontrada</td></tr>";
        }

        mysqli_close($conexao);
        ?>
        </tbody>
      </table>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
