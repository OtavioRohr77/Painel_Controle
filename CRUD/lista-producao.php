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
    $sql_delete = "DELETE FROM producao WHERE ProducaoID = $id";
    if (mysqli_query($conexao, $sql_delete)) {
        echo "<p style='color: green;'>Registro $id excluído com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
    }
}

// Consulta com JOIN para exibir nomes corretos
$sql = "SELECT p.ProducaoID, pr.Nome AS ProdutoNome, f.Nome AS FuncionarioNome, p.DataProducao
        FROM producao p
        LEFT JOIN produtos pr ON p.ProdutoID = pr.ProdutoID
        LEFT JOIN funcionarios f ON p.FuncionarioID = f.FuncionarioID";

$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    die("Erro na consulta: " . mysqli_error($conexao));
}
?>

<main>
  <div class="container">
      <h1>Lista de Produções</h1>
      <a href="salvar-producao.php" class="btn btn-add">Incluir Produção</a>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Funcionário</th>
            <th>Data</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha["ProducaoID"] . "</td>";
                echo "<td>" . $linha["ProdutoNome"] . "</td>";
                echo "<td>" . $linha["FuncionarioNome"] . "</td>";
                echo "<td>" . $linha["DataProducao"] . "</td>";
                echo "<td>
                        <a href='salvar-producao.php?id=" . $linha["ProducaoID"] . "' class='btn btn-edit'>Editar</a>
                        <a href='lista-producao.php?excluir=" . $linha["ProducaoID"] . "' class='btn btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir esta produção?')\">Excluir</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhuma produção encontrada</td></tr>";
        }

        mysqli_close($conexao);
        ?>
        </tbody>
      </table>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
