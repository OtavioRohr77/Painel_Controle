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
    $sql_delete = "DELETE FROM funcionarios WHERE FuncionarioID = $id";
    if (mysqli_query($conexao, $sql_delete)) {
        echo "<p style='color: green;'>Registro $id excluído com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
    }
}
?>
<main>
  <div class="container">
      <h1>Lista de Funcionários</h1>
      <a href="./salvar-funcionarios.php" class="btn btn-add">Incluir</a> 
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Setor</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT f.FuncionarioID, f.Nome, c.Nome AS CargoNome, s.Nome AS SetorNome 
                FROM funcionarios f
                LEFT JOIN cargos c ON f.CargoID = c.CargoID
                LEFT JOIN setor s ON f.SetorID = s.SetorID";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha["FuncionarioID"] . "</td>";
                echo "<td>" . $linha["Nome"] . "</td>";
                echo "<td>" . $linha["CargoNome"] . "</td>";
                echo "<td>" . $linha["SetorNome"] . "</td>";
                echo "<td>";
                echo "<a href='salvar-funcionarios.php?id=" . $linha["FuncionarioID"] . "' class='btn btn-edit'>Editar</a> ";
                echo "<a href='lista-funcionarios.php?excluir=" . $linha["FuncionarioID"] . "' class='btn btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir este registro?')\">Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum dado encontrado</td></tr>";
        }

        mysqli_close($conexao);
        ?>
        </tbody>
      </table>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
