<?php 
include_once './include/logado.php';
include_once './include/conexao.php';
include_once './include/header.php';

$conexao = mysqli_connect("localhost", "root", "", "painel_controle");
if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Inicializa variáveis
$id = $nome = $data_nascimento = $email = $salario = $sexo = $cpf = $rg = $CargoID = $SetorID = "";

// Se veio um ID, estamos editando
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM funcionarios WHERE FuncionarioID = $id";
    $resultado = mysqli_query($conexao, $sql);
    if ($linha = mysqli_fetch_assoc($resultado)) {
        $nome = $linha['Nome'];
        $data_nascimento = $linha['DataNascimento'];
        $email = $linha['Email'];
        $salario = $linha['Salario'];
        $sexo = $linha['Sexo'];
        $cpf = $linha['CPF'];
        $rg = $linha['RG'];
        $CargoID = $linha['CargoID'];
        $SetorID = $linha['SetorID'];
    }
}

// Salvar ou atualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_post = mysqli_real_escape_string($conexao, $_POST['nome']);
    $data_nascimento_post = mysqli_real_escape_string($conexao, $_POST['data_nascimento']);
    $email_post = mysqli_real_escape_string($conexao, $_POST['email']);
    $salario_post = floatval($_POST['salario']);
    $sexo_post = mysqli_real_escape_string($conexao, $_POST['sexo']);
    $cpf_post = mysqli_real_escape_string($conexao, $_POST['cpf']);
    $rg_post = mysqli_real_escape_string($conexao, $_POST['rg']);
    $CargoID_post = intval($_POST['CargoID']);
    $SetorID_post = intval($_POST['SetorID']);

    if (!empty($_POST['id'])) {
        // Atualizar
        $id_post = intval($_POST['id']);
        $sql_update = "UPDATE funcionarios SET 
                        Nome='$nome_post',
                        DataNascimento='$data_nascimento_post',
                        Email='$email_post',
                        Salario=$salario_post,
                        Sexo='$sexo_post',
                        CPF='$cpf_post',
                        RG='$rg_post',
                        CargoID=$CargoID_post,
                        SetorID=$SetorID_post
                       WHERE FuncionarioID=$id_post";
        if (mysqli_query($conexao, $sql_update)) {
            echo "<p style='color: green;'>Registro atualizado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao atualizar: " . mysqli_error($conexao) . "</p>";
        }
    } else {
        // Inserir
        $sql_insert = "INSERT INTO funcionarios (Nome, DataNascimento, Email, Salario, Sexo, CPF, RG, CargoID, SetorID) 
                       VALUES ('$nome_post', '$data_nascimento_post', '$email_post', $salario_post, '$sexo_post', '$cpf_post', '$rg_post', $CargoID_post, $SetorID_post)";
        if (mysqli_query($conexao, $sql_insert)) {
            echo "<p style='color: green;'>Funcionário cadastrado com sucesso!</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cadastrar: " . mysqli_error($conexao) . "</p>";
        }
    }

    // Limpa campos
    $nome = $data_nascimento = $email = $salario = $sexo = $cpf = $rg = $CargoID = $SetorID = "";
}
?>

<main>
  <div class="container">
    <h2><?php echo isset($id) && $id ? "Editar Funcionário" : "Cadastrar Funcionário"; ?></h2>
    <form method="post" class="crud-form">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="text" name="nome" placeholder="Nome" value="<?php echo $nome; ?>" required>
      <input type="date" name="data_nascimento" placeholder="Data de Nascimento" value="<?php echo $data_nascimento; ?>" required>
      <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
      <input type="number" name="salario" placeholder="Salário" value="<?php echo $salario; ?>" step="0.01" required>

      <select name="sexo" required>
        <option value="">Sexo</option>
        <option value="M" <?php if($sexo=='M') echo 'selected'; ?>>Masculino</option>
        <option value="F" <?php if($sexo=='F') echo 'selected'; ?>>Feminino</option>
      </select>

      <input type="text" name="cpf" placeholder="CPF" value="<?php echo $cpf; ?>" required>
      <input type="text" name="rg" placeholder="RG" value="<?php echo $rg; ?>" required>

      <select name="CargoID" required>
        <option value="">Selecione o Cargo</option>
        <?php
        $sql_cargos = "SELECT CargoID, Nome FROM cargos";
        $res_cargos = mysqli_query($conexao, $sql_cargos);
        if ($res_cargos && mysqli_num_rows($res_cargos) > 0) {
            while ($row = mysqli_fetch_assoc($res_cargos)) {
                $selected = ($row['CargoID'] == $CargoID) ? "selected" : "";
                echo '<option value="'.$row['CargoID'].'" '.$selected.'>'.$row['Nome'].'</option>';
            }
        }
        ?>
      </select>

      <select name="SetorID" required>
        <option value="">Selecione o Setor</option>
        <?php
        $sql_setores = "SELECT SetorID, Nome FROM setor";
        $res_setores = mysqli_query($conexao, $sql_setores);
        if ($res_setores && mysqli_num_rows($res_setores) > 0) {
            while ($row = mysqli_fetch_assoc($res_setores)) {
                $selected = ($row['SetorID'] == $SetorID) ? "selected" : "";
                echo '<option value="'.$row['SetorID'].'" '.$selected.'>'.$row['Nome'].'</option>';
            }
        }
        ?>
      </select>

      <button type="submit">Salvar</button>
    </form>
    <a href="lista-funcionarios.php" class="btn btn-back">Voltar</a>
  </div>
</main>

<?php include_once './include/footer.php'; ?>
