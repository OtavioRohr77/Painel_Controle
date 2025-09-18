<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';
?>
<main>
  <div class="container">
    <h1>Lista de Cargos</h1>
    <a href="./salvar-cargos.php" class="btn btn-add">Incluir</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Teto Salarial</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $conexao = mysqli_connect("localhost", "root", "", "painel_controle");
        if (!$conexao) {
            die("Erro na conexão: " . mysqli_connect_error());
        }

        // Exclusão
        if (isset($_GET['excluir'])) {
            $id = intval($_GET['excluir']);
            $sql_delete = "DELETE FROM cargos WHERE CargoID = $id";
            if (mysqli_query($conexao, $sql_delete)) {
                echo "<p style='color: green;'>Registro $id excluído com sucesso!</p>";
            } else {
                echo "<p style='color: red;'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
            }
        }

        // Seleciona os cargos
        $sql = "SELECT CargoID, Nome, TetoSalarial FROM cargos";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha["CargoID"] . "</td>";
                echo "<td>" . $linha["Nome"] . "</td>";
                echo "<td>" . $linha["TetoSalarial"] . "</td>";
                echo "<td>";
                echo "<a href='salvar-cargos.php?id=" . $linha["CargoID"] . "' class='btn btn-edit'>Editar</a> ";
                echo "<a href='?excluir=" . $linha["CargoID"] . "' class='btn btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir este registro?')\">Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum dado encontrado</td></tr>";
        }

        mysqli_close($conexao);
        ?> 
      </tbody>
    </table>
  </div> 
</main>

<?php include_once './include/footer.php'; ?>
