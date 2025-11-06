<?php
// resultados.php
include 'config.php';

// Proteção: Apenas professores podem ver esta página
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'professor') {
    die("Acesso negado. Esta página é restrita a professores.");
}

// ---- AQUI ESTÁ A LÓGICA DE PESOS ----
// Assumindo:
// Peso Aluno = 2
// Peso Professor = 5 (Você pode mudar este valor)

$peso_aluno = 2;
$peso_professor = 5;

// Query SQL complexa para calcular a pontuação final ponderada
$sql = "
    SELECT
        p.id,
        p.titulo,
        p.sala,
        
        -- Soma total dos pesos (denominador)
        SUM(CASE 
            WHEN u.tipo_usuario = 'aluno' THEN $peso_aluno 
            WHEN u.tipo_usuario = 'professor' THEN $peso_professor 
        END) AS peso_total,
        
        -- Soma total das notas ponderadas (numerador)
        SUM(
            (a.criterio_originalidade + a.criterio_apresentacao + a.criterio_aplicabilidade) * (CASE 
                WHEN u.tipo_usuario = 'aluno' THEN $peso_aluno 
                WHEN u.tipo_usuario = 'professor' THEN $peso_professor 
            END)
        ) AS nota_ponderada_total,

        -- Média Ponderada Final
        -- (Nota Ponderada Total / (Total de Critérios * Peso Total)) * 5 (para normalizar para 5 estrelas)
        (
            SUM(
                (a.criterio_originalidade + a.criterio_apresentacao + a.criterio_aplicabilidade) * (CASE 
                    WHEN u.tipo_usuario = 'aluno' THEN $peso_aluno 
                    WHEN u.tipo_usuario = 'professor' THEN $peso_professor 
                END)
            ) / 
            SUM(
                3 * -- 3 é o número de critérios
                (CASE 
                    WHEN u.tipo_usuario = 'aluno' THEN $peso_aluno 
                    WHEN u.tipo_usuario = 'professor' THEN $peso_professor 
                END)
            )
        ) * 5 AS media_final_ponderada, -- Média de 1 a 5

        -- Contagem de votos
        COUNT(CASE WHEN u.tipo_usuario = 'aluno' THEN 1 END) AS votos_alunos,
        COUNT(CASE WHEN u.tipo_usuario = 'professor' THEN 1 END) AS votos_professores

    FROM 
        avaliacoes AS a
    JOIN 
        projetos AS p ON a.id_projeto = p.id
    JOIN 
        users AS u ON a.id_usuario_avaliador = u.id
    GROUP BY
        p.id, p.titulo, p.sala
    ORDER BY
        media_final_ponderada DESC -- Ordena do melhor para o pior
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultados - Feira Técnica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-3">Voltar</a>
        <h1 class="mb-4">Resultados Finais (Ponderados)</h1>
        
        <div class="alert alert-info">
            <strong>Regra de Cálculo:</strong> As avaliações de Alunos têm <strong>peso <?php echo $peso_aluno; ?></strong> e as de Professores têm <strong>peso <?php echo $peso_professor; ?></strong>.
        </div>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Rank</th>
                    <th>Projeto</th>
                    <th>Sala</th>
                    <th>Votos (Alunos)</th>
                    <th>Votos (Prof.)</th>
                    <th>Média Final Ponderada (1-5)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><strong>#<?php echo $rank++; ?></strong></td>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($row['sala']); ?></td>
                    <td><?php echo $row['votos_alunos']; ?></td>
                    <td><?php echo $row['votos_professores']; ?></td>
                    <td class="fs-5"><strong><?php echo number_format($row['media_final_ponderada'], 2, ',', '.'); ?></strong></td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhuma avaliação registrada ainda.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>