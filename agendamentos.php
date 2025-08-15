
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
                window.location.href = "login.php";
            }
        });
    });

</script>

<?
require_once "api/validate.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Agendamento</title>
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
    <a href="#" onclick="logout()">🚪 Sair</a>
  </div>

  <!-- Área de conteúdo -->
  <div class="content">
    <h3>Cadastrar Novo Agendamento</h3>
    <form id="formAgendamento" onsubmit="event.preventDefault(); cadastrar();">
      <div class="row g-3 align-items-center">
        <div class="col-md-2">
          <input type="date" id="date" class="form-control">
        </div>
        <div class="col-md-2">
          <input type="time" id="time" class="form-control">
        </div>
        <div class="col-md-3">
          <select id="id_myevent" class="form-select"></select>
        </div>
        <div class="col-md-3">
          <select id="id_tpEvent" class="form-select"></select>
        </div>
        <div class="col-md-1">
          <input type="number" id="vacancies" class="form-control" placeholder="Vagas">
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </div>
    </form>
  </div>

  <script>

    // Carrega eventos
    fetch('api/generic/list.php', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ table: 'myevent' })
    })
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('id_myevent');
      data.forEach(ev => {
        select.innerHTML += `<option value="${ev.id}">${ev.name}</option>`;
      });
    });

    // Carrega tipos
    fetch('api/generic/list.php', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ table: 'typeevent' })
    })
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('id_tpEvent');
      data.forEach(tp => {
        select.innerHTML += `<option value="${tp.id}">${tp.name}</option>`;
      });
    });

    function cadastrar() {
      const agendamento = {
        table: "schedule",
        values: {
          date: document.getElementById('date').value,
          time: document.getElementById('time').value,
          id_myevent: parseInt(document.getElementById('id_myevent').value),
          id_tpEvent: parseInt(document.getElementById('id_tpEvent').value),
          vacancies: parseInt(document.getElementById('vacancies').value)
        }
      };

      fetch('api/create.php', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('token'),
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(agendamento)
      })
      .then(res => res.json())
      .then(resp => {
        alert("Agendamento criado com sucesso!");
        document.getElementById('formAgendamento').reset();
      });
    }

    function logout() {
      localStorage.removeItem('token');
      window.location.href = 'index.php';
    }



  </script>
</body>
</html>
