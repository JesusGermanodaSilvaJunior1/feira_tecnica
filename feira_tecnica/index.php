<?php
// index.php
include 'config.php';

// Proteção: se não estiver logado, volta para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Projetos - Feira Técnica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Feira Técnica</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Olá, <?php echo htmlspecialchars($_SESSION['user_nome']); ?>!
                        </span>
                    </li>
                    <?php if ($_SESSION['user_tipo'] == 'professor'): ?>
                    <li class="nav-item">
                        <a class="btn btn-success" href="resultados.php">Ver Resultados</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Projetos para Avaliação</h1>
        <div class="row">
            <?php
            // Busca todos os projetos
            $result = $conn->query("SELECT * FROM projetos ORDER BY sala, titulo");
            while ($projeto = $result->fetch_assoc()):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($projeto['titulo']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Sala: <?php echo htmlspecialchars($projeto['sala']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($projeto['descricao']); ?></p>
                        <p class="card-text"><small><strong>Participantes:</strong> <?php echo htmlspecialchars($projeto['participantes']); ?></small></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="avaliar.php?id=<?php echo $projeto['id']; ?>" class="btn btn-primary w-100">Avaliar Projeto</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>