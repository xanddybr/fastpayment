<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>FastPayment - Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="navbar">
    <span id="nomeUsuario"></span>
    <a href="#" onclick="logout()">Logout</a>
  </div>
  <div class="container">
    <h2>Agenda</h2>
    <p>Aqui você verá seus compromissos.</p>
  </div>
  <script>
    document.getElementById('nomeUsuario').innerText = localStorage.getItem('userName');
    function logout() {
      localStorage.clear();
      window.location.href = "index.html";
    }
  </script>
</body>
</html>
