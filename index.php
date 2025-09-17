
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
      <button class="btn btn-success" id="submitButton">Comprar item selecionado!</button>
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
      if (selected) {
        const selectedId = selected.value;
        alert(`Evento selecionado com id_schedule: ${selectedId}`);
        // Aqui você pode redirecionar para a próxima etapa, por exemplo:
        // window.location.href = `checkout.php?id=${selectedId}`;
      } else {
        alert("Por favor, selecione um evento para continuar.");
      }
    });

    function getDayName(dateString) {
      const [day, month, year] = dateString.split('/');
        const date = new Date(year, month - 1, day);
          return ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'][date.getDay()];
    }

  </script>

</body>
</html>
