
<script>
localStorage.removeItem('token');
</script>

<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>FastPayment - Login</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow" style="width: 350px;">
    <div class="card-body">
      <h3 class="card-title text-center mb-4">FastPayment</h3>
      <div class="mb-3">
        <input type="email" id="email" class="form-control" placeholder="Seu e-mail">
      </div>
      <div class="mb-3">
        <input type="password" id="senha" class="form-control" placeholder="Senha">
      </div>
      <button class="btn btn-primary w-100" onclick="login()">Entrar</button><br><br>
      <button class="btn btn-primary w-100" onclick="carregarToken()">Mostrar token</button>
    </div>
  </div>
  <script>
    function login() {
      fetch('api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          email: document.getElementById('email').value,
          password: document.getElementById('senha').value
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.token) {
          localStorage.setItem('token', data.token);
          
        } else {
          alert("Error credentials!");
        }
      });
      window.location.href = 'home.php';
    }
    function carregarToken(){
      const token = localStorage.getItem('token')
      alert(token);
    }
  </script>

</body>
</html>
