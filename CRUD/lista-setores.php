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
    $sql_delete = "DELETE FROM setor WHERE SetorID = $id";
    if (mysqli_query($conexao, $sql_delete)) {
        echo "<p style='color: green;'>Registro $id excluído com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao excluir: " . mysqli_error($conexao) . "</p>";
    }
}
?>
<main>
  <div class="container">
    <h1>Lista de Setores</h1>
    <a href="salvar-setores.php" class="btn btn-add">Incluir</a>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Andar</th>
          <th>Cor</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT SetorID, Nome, Andar, Cor FROM setor";
        $resultado = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $linha["SetorID"] . "</td>";
                echo "<td>" . $linha["Nome"] . "</td>";
                echo "<td>" . $linha["Andar"] . "</td>";
                echo "<td>" . $linha["Cor"] . "</td>";
                echo "<td>";
                echo "<a href='salvar-setores.php?id=" . $linha["SetorID"] . "' class='btn btn-edit'>Editar</a> ";
                echo "<a href='lista-setores.php?excluir=" . $linha["SetorID"] . "' class='btn btn-delete' onclick=\"return confirm('Tem certeza que deseja excluir este registro?')\">Excluir</a>";
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
