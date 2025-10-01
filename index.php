
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Mistura de Luz</title>
 <link rel="stylesheet" href="css/bootstrap.min.css">
 <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

  <div class="container py-5">
    <h2 class="mb-4 text-center">📅 Agenda Mistura de Luz</h2>

    <div class="table-responsive shadow rounded">
      <table class="table table-hover table-bordered bg-white align-middle">
        <thead class="table-primary text-center">
          <tr>
            <th>Selecionar</th>
            <th>Dia</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Evento</th>
            <th>Tipo</th>
            <th>Preço</th>
            <th>Modo/Unidade</th>
            <th>Vagas</th>
          </tr>
        </thead>
        <tbody id="scheduleTableBody">
          <!-- Dados serão inseridos aqui -->
        </tbody>
      </table>
    </div>

    <div class="mt-4 text-center">
      <button class="btn btn-success" id="submitButton">Participar do evento!</button>
    </div>

    <div id="registrationForm" class="mt-4" style="display:none;">
      <h4>Inscreva-se no evento</h4>
      <form id="formClient">
        <div class="mb-3">
          <label>Nome</label>
          <input type="text" class="form-control" id="firstName" required>
        </div>
        <div class="mb-3">
          <label>Sobrenome</label>
          <input type="text" class="form-control" id="lastName" required>
        </div>
        <div class="mb-3">
          <label>Telefone</label>
          <input type="text" class="form-control" id="phone" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" class="form-control" id="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Validar meus dados!</button>
  </form>

  <div id="codeVerify" class="mt-4" style="display:none;">
    <label>Digite o código recebido por email:</label>
    <input type="text" id="codeInput" class="form-control mb-3">
    <button class="btn btn-success" id="verifyButton">Validar Código</button>
  </div>
</div>

    <div id="message" class="mt-4 text-center text-danger fw-bold"></div>
  </div>

  <script>
    fetch('./api/schedule.php')
      .then(response => response.json())
      .then(data => {
        const tbody = document.getElementById("scheduleTableBody");
        tbody.innerHTML = "";

        if (data.length === 0) {
          document.getElementById("message").innerText = "Nenhum agendamento encontrado.";
          return;
        }

        data.forEach((a, index) => {
          const tr = document.createElement("tr");
          tr.classList.add('text-center');
          tr.innerHTML = `
            <td>
              <input type="radio" name="selectedEvent" value="${a.id_schedule}" class="form-check-input">
            </td>
            <td>${getDayName(a.date)}</td>
             <td>${a.date}</td>
             <td>${a.time}</td>
            <td>${a.myevent}</td>
            <td>${a.tpevent}</td>
            <td>R$${a.price}</td>
            <td>${a.units}</td>
            <td>${a.vacancies}</td>`;
          tbody.appendChild(tr);
        });
      })
      .catch(error => {
        document.getElementById("message").innerText = "Erro ao carregar os dados.";
        console.error(error);
      });

    // Botão para capturar o checkbox marcado
    document.getElementById('submitButton').addEventListener('click', () => {
      const selected = document.querySelector('input[name="selectedEvent"]:checked');
     
    });

    function getDayName(dateString) {
      const [day, month, year] = dateString.split('/');
        const date = new Date(year, month - 1, day);
          return ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'][date.getDay()];
    }

  </script>

  <script>
      document.getElementById('submitButton').addEventListener('click', () => {
        const selected = document.querySelector('input[name="selectedEvent"]:checked');
        if (selected) {
          const selectedId = selected.value;

          // Esconde a tabela
          document.querySelector('.table-responsive').style.display = 'none';
          document.getElementById('submitButton').style.display = 'none';

          // Mostra o formulário
          document.getElementById('registrationForm').style.display = 'block';

          // Armazena o evento selecionado em uma variável global
          window.selectedEventId = selectedId;
        } else {
          alert("Por favor, selecione um evento para continuar.");
        }
      });

      // Envio do formulário
      document.getElementById('formClient').addEventListener('submit', function(e){
        e.preventDefault();

        const data = {
          firstName: document.getElementById('firstName').value,
          lastName: document.getElementById('lastName').value,
          phone: document.getElementById('phone').value,
          email: document.getElementById('email').value,
          id_schedule: window.selectedEventId
        };

        fetch('./api/send_code.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(data)
        })
        .then(res => res.text())
        .then(response => {
          if(response.success){
            alert("✅ Código enviado para o seu email!");
            document.getElementById('codeVerify').style.display = 'block';
          } else {
            alert("❌ Erro ao enviar código.");
          }
        });
      });

      // Validação do código
      document.getElementById('verifyButton').addEventListener('click', () => {
        const code = document.getElementById('codeInput').value;

        fetch('./api/verify_code.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(response => {
          if(response.success){
            alert("🎉 Código validado! Você foi inscrito no evento.");
            // Aqui você pode redirecionar ou mostrar mensagem final
          } else {
            alert("⚠️ Código inválido!");
          }
        });
      });

  </script>

</body>
</html>
