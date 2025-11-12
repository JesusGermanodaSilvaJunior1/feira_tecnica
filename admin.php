<?php include('./backend/config.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
    <div class="container py-5">
  <h1 class="text-center mb-4">Cadastrar Projeto</h1>
  
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $nome_projeto = $_POST["nome_projeto"];
      $integrantes = $_POST["integrantes"];
      $professor = $_POST["professor_responsavel"];
      
      // Upload da foto
      $foto = $_FILES["foto"]["name"];
      $target_dir = "uploads/";
      $target_file = $target_dir . basename($foto);

      if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
          $sql = "INSERT INTO projetos (nome_projeto, integrantes, professor_responsavel, foto) 
                  VALUES ('$nome_projeto', '$integrantes', '$professor', '$foto')";
          
          if ($conn->query($sql) === TRUE) {
              echo "<div class='alert alert-success'>Projeto cadastrado com sucesso!</div>";
          } else {
              echo "<div class='alert alert-danger'>Erro: " . $conn->error . "</div>";
          }
      } else {
          echo "<div class='alert alert-warning'>Erro ao enviar a foto.</div>";
      }
  }
  ?>

  <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Nome do Projeto</label>
      <input type="text" name="nome_projeto" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Integrantes</label>
      <textarea name="integrantes" class="form-control" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Professor Responsável</label>
      <input type="text" name="professor_responsavel" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Foto do Projeto</label>
      <input type="file" name="foto" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Cadastrar</button>
  </form>
</div>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escola Estadual Presidente Dutra</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
</head>
<body class="bg-light">
  <div class="container py-5">
  <h1 class="text-center mb-4">Feira Técnica - Projetos</h1>
  <div class="row">
    <?php
    $sql = "SELECT * FROM projetos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '
            <div class="col-md-4 mb-4">
              <div class="card shadow-sm">
                <img src="uploads/'.$row["foto"].'" class="card-img-top" alt="Foto do projeto">
                <div class="card-body">
                  <h5 class="card-title">'.$row["nome_projeto"].'</h5>
                  <p><strong>Integrantes:</strong> '.$row["integrantes"].'</p>
                  <p><strong>Professor:</strong> '.$row["professor_responsavel"].'</p>
                </div>
              </div>
            </div>';
        }
    } else {
        echo "<p class='text-center'>Nenhum projeto cadastrado ainda.</p>";
    }
    ?>
  </div>
</div>
  <!-- HEADER -->
  <header class="bg-info text-white">
    
    
    <nav class="navbar navbar-expand-lg navbar-dark container align-items-end">
 <!-- Logo and Title -->
    <div class="d-flex align-items-center p-3">   
      <img src="./img/logo.png" alt="Logo da Escola" class="me-3" style="height: 200px;">
      <h1 class="h2 mb-0">Escola Estadual Presidente Dutra</h1>
    </div>  

  <!-- Botão hamburguer -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Menu -->
  <div class="collapse navbar-collapse justify-content-end" id="menuNav">
    <ul class="navbar-nav align-items-end">
      <li class="nav-item"><a class="nav-link text-white fw-semibold" href="./frontend/index.php">Início</a></li>
      <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Cursos</a></li>
      <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Feira Técnica</a></li>
      <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Matrícula</a></li>
      <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Blog</a></li>
      <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Contato</a></li>
    </ul>
  </div>
</nav>

    <!-- Slogan -->
    <div class="text-center pb-3">
      <p class="mb-0"><h2>Educação de qualidade para um futuro melhor</h2></p>
    </div>
  </header>
<!-- CARROSSEL VERTICAL 
  <div class="swiper mySwiper mt-4">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="interclasse.jpg" alt="Interclasse" class="img-fluid">
        <h5 class="text-center mt-2">Interclasse</h5>
      </div>
      <div class="swiper-slide">
        <img src="assunto2.jpg" alt="Assunto 2" class="img-fluid">
        <h5 class="text-center mt-2">Assunto 2</h5>
      </div>
      <div class="swiper-slide">
        <img src="assunto3.jpg" alt="Assunto 3" class="img-fluid">
        <h5 class="text-center mt-2">Assunto 3</h5>
      </div>
    </div>
     Paginação 
    <div class="swiper-pagination"></div>
     Botões 
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div> --> 

  <!-- FOOTER -->
  <footer class="bg-info text-white pt-4 pb-2 mt-5">
    <div class="container">
      <div class="row">
        <!-- Redes sociais -->
        <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
          <h5 class="fw-bold">Redes Sociais</h5>
          <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
          <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
          <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
          <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
        </div>

        <!-- Informações -->
        <div class="col-md-8 text-center">
          <p class="mb-1">CNPJ: 00.000.000/0001-00</p>
          <p class="mb-1 fw-bold text-uppercase">Escola Estadual Presidente Dutra</p>
          <p class="mb-1">
            Rua Exemplo, CEP: 00000-000, Bairro Centro, Belo Horizonte, Minas Gerais - Brasil
          </p>
        </div>
      </div>

      <!-- Créditos -->
      <div class="mt-4 text-center">
        <h6 class="mb-0">
          Desenvolvido pelos alunos <strong>EEPD-2025</strong> @ Todos os direitos reservados à
          Escola Estadual Presidente Dutra.
        </h6>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper(".mySwiper", {
      direction: "vertical",
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      mousewheel: true,
    });
  </script>
</body>
</html>