<?php
$nome = "";
$email = "";
$telefone = "";
$mensagem = "";
$tipoMensagem = "";

$db_url = getenv("DATABASE_URL");

if (!$db_url) {
    die("Erro: variável DATABASE_URL não encontrada.");
}

$conn = pg_connect($db_url);

if (!$conn) {
    die("Erro ao conectar no banco de dados.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    if ($nome === "" || $email === "" || $telefone === "") {
        $mensagem = "Preencha todos os campos.";
        $tipoMensagem = "erro";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Digite um e-mail válido.";
        $tipoMensagem = "erro";
    } else {
        $query = "INSERT INTO usuarios (nome, email, telefone) VALUES ($1, $2, $3)";
        $result = pg_query_params($conn, $query, [$nome, $email, $telefone]);

        if ($result) {
            $mensagem = "Usuário cadastrado com sucesso no banco de dados.";
            $tipoMensagem = "sucesso";

            $nome = "";
            $email = "";
            $telefone = "";
        } else {
            $mensagem = "Erro ao salvar no banco de dados.";
            $tipoMensagem = "erro";
        }
    }
}

$queryLista = "SELECT id, nome, email, telefone FROM usuarios ORDER BY id DESC";
$resultLista = pg_query($conn, $queryLista);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Web II - Cadastro de Usuário</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background: #f4f6f8;
      color: #222;
    }

    .container {
      background: #fff;
      padding: 24px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($telefone); ?></p>
  <?php endif; ?>

</body>
</html>
