<?php
// avaliar.php
include 'config.php';

// Proteção
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_projeto = $_GET['id'] ?? 0;
$id_usuario = $_SESSION['user_id'];
$mensagem = '';

// Verifica se o projeto existe
$stmt = $conn->prepare("SELECT * FROM projetos WHERE id = ?");
$stmt->bind_param("i", $id_projeto);
$stmt->execute();
$result_projeto = $stmt->get_result();

if ($result_projeto->num_rows == 0) {
    die("Projeto não encontrado.");
}
$projeto = $result_projeto->fetch_assoc();

// Verifica se já avaliou
$stmt_check = $conn->prepare("SELECT id FROM avaliacoes WHERE id_projeto = ? AND id_usuario_avaliador = ?");
$stmt_check->bind_param("ii", $id_projeto, $id_usuario);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $mensagem = '<div class="alert alert-warning">Você já avaliou este projeto.</div>';
    $ja_avaliou = true;
} else {
    $ja_avaliou = false;
}

// Processa o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$ja_avaliou) {
    $c1 = $_POST['criterio_originalidade'];
    $c2 = $_POST['criterio_apresentacao'];
    $c3 = $_POST['criterio_aplicabilidade'];
    $comentarios = $_POST['comentarios'];

    $sql_insert = "INSERT INTO avaliacoes (id_projeto, id_usuario_avaliador, criterio_originalidade, criterio_apresentacao, criterio_aplicabilidade, comentarios) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iiiiis", $id_projeto, $id_usuario, $c1, $c2, $c3, $comentarios);
    
    if ($stmt_insert->execute()) {
        $mensagem = '<div class="alert alert-success">Avaliação enviada com sucesso!</div>';
        $ja_avaliou = true;
    } else {
        $mensagem = '<div class="alert alert-danger">Erro ao enviar avaliação: ' . $conn->error . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Avaliar Projeto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="index.php" class="btn btn-secondary mb-3">Voltar para a lista</a>
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Avaliação do Projeto: <?php echo htmlspecialchars($projeto['titulo']); ?></h2>
                    </div>
                    <div class="card-body">
                        <?php echo $mensagem; ?>

                        <?php if (!$ja_avaliou): ?>
                        <p class="fst-italic">Dê sua nota de 1 (Ruim) a 5 (Excelente) para cada critério.</p>
                        <form action="avaliar.php?id=<?php echo $id_projeto; ?>" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>1. Originalidade e Inovação</strong></label>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="criterio_originalidade" id="c1_<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label class="form-check-label" for="c1_<?php echo $i; ?>"><?php echo $i; ?></label>
                                </div>
                                <?php endfor; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>2. Apresentação e Clareza</strong></label>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="criterio_apresentacao" id="c2_<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label class="form-check-label" for="c2_<?php echo $i; ?>"><?php echo $i; ?></label>
                                </div>
                                <?php endfor; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>3. Aplicabilidade e Relevância</strong></label>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="criterio_aplicabilidade" id="c3_<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label class="form-check-label" for="c3_<?php echo $i; ?>"><?php echo $i; ?></label>
                                </div>
                                <?php endfor; ?>
                            </div>

                            <div class="mb-3">
                                <label for="comentarios" class="form-label"><strong>Comentários Adicionais (Opcional)</strong></label>
                                <textarea class="form-control" name="comentarios" id="comentarios" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">Enviar Avaliação</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>