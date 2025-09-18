<?php 
// include dos arquivox
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';
?>

<main>

  <div class="container">
      <h1>Lista de Produtos</h1>
      <a href="./salvar-usuarios.php" class="btn btn-add">Incluir</a> 
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php
                $conexao = mysqli_connect("localhost", "root", "", "painel_controle");
                if (!$conexao) {
                    die("Erro na conexão: " . mysqli_connect_error());
                }

                $sql = "SELECT ClienteID, Nome, Telefone, Email FROM clientes";
                $resultado = mysqli_query($conexao, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($linha = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $linha["ClienteID"] . "</td>";
                        echo "<td>" . $linha["Nome"] . "</td>";
                        echo "<td>" . $linha["Telefone"] . "</td>";
                        echo "<td>" . $linha["Email"] . "</td>";
                        echo "<td>";
                        echo "<a href='salvar-cargos.php?id=" . $linha["ClienteID"] . "' class='btn btn-edit'>Editar</a> ";
                        echo "<a href='excluir-cargos.php?id=" . $linha["ClienteID"] . "' class='btn btn-delete'>Excluir</a>";
                        echo "</td>";
                    }
                } else {
                    echo "<tr><td'>Nenhum dado encontrado</td></tr>";
                }

                mysqli_close($conexao);
                ?></td>
          </tr>
        </tbody>
      </table>
    </div>

<?php 
  // include dos arquivox
  include_once './include/footer.php';
  ?>  