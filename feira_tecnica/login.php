<?php
// login.php
include 'config.php';
$mensagem_erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // No mundo real, use password_verify() com senhas hasheadas!
    // Esta é uma simplificação perigosa apenas para o exemplo.
    // Crie usuários de teste direto no seu DB.
    
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verificação simplificada (use password_verify na prática!)
        if ($senha == $user['senha']) { 
            // Login com sucesso! Armazena na sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_tipo'] = $user['tipo_usuario'];
            
            header("Location: index.php"); // Redireciona para a lista de projetos
            exit;
        } else {
            $mensagem_erro = "Senha incorreta.";
        }
    } else {
        $mensagem_erro = "Email não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Feira Técnica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Acesso ao Sistema</h2>
                        <?php if ($mensagem_erro): ?>
                            <div class="alert alert-danger"><?php echo $mensagem_erro; ?></div>
                        <?php endif; ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>