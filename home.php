<script>

  document.addEventListener("DOMContentLoaded", () => {
      const token = localStorage.getItem('token');
        fetch("api/validate.php", {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                window.location.href = "index.php";
            }
        });
    });

    function logout(){
      localStorage.removeItem('token');
    }

  </script>
  

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>FastPayment - Dashboard</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      min-height: 100vh;
      display: flex;
    }
    .sidebar {
      min-width: 220px;
      max-width: 220px;
      background-color: #343a40;
      color: white;
      flex-shrink: 0;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 10px 15px;
      display: block;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      flex-grow: 1;
      padding: 20px;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  
  <!-- Menu lateral -->
  <div class="sidebar">
    <h4 class="p-3">FastPayment</h4>
    <a href="/home.php">🏠 Home</a>
    <a href="/agendamentos.php">📅 Cadastrar de Eventos</a>
    <a href="#">🏠 Histórico</a>
    <a href="#">Configurações</a>
    <hr class="bg-light">
    <a href="logout.php" onclick="logout()">🚪 Sair</a>
  </div>

  <!-- Área de conteúdo -->
  <div class="content">
    <h3>Bem-vindo ao Dashboard</h3>
    <p>Escolha uma opção no menu à esquerda para navegar pelo sistema.</p>
  </div>

  

</body>
</html>

<?
require_once 'api/validate.php';
if (!isset($decoded)) {
    header('Location: index.html');
    exit;
}



?>