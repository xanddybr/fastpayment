<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FastPayment by Mistura de Luz</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- estilos próprios -->
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark" id="navMistura">
  <div class="container-fluid">
    <a class="navbar-brand nav-link" data-target="home" href="#">Mistura de Luz</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarText" aria-controls="navbarText" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" data-target="dashboard" href="#">Agenda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-target="inscricoes" href="#">Inscrições</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-target="historico" href="#">Histórico</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div id="inscricoes" style="display:none">
  <h3 class="mb-3 text-center">Construção inscrições</h3>
</div>

<div id="historico" style="display:none">
  <h3 class="mb-3 text-center">Construção histórico</h3>
</div>

<div class="container">
    <!-- Login -->
    <div id="login">
        <h3 class="mb-3 text-center">FastPayment</h3>
        <div class="mb-3">
            <input type="email" id="email" class="form-control" placeholder="Seu e-mail" required>
        </div>
        <div class="mb-3">
            <input type="password" id="senha" class="form-control" placeholder="Senha" required>
        </div>
        <button class="btn btn-primary w-100" onclick="makeLogin()">Entrar</button><br><br>
        <center>v 1.0.0b</center>
    </div>

    <!-- Dashboard Wrapper -->

        <div id="dashboard" style="display: flex; justify-content: center; margin: 0 -280px 0 -100px">
            <div class="d-flex justify-content-left align-items-center mb-3">
                <h3>Agenda</h3>&nbsp;&nbsp;
                <button class="btn btn-danger" onclick="logout()">Sair</button>
            </div>
    <!-- Wrapper para scroll horizontal -->
    <div style="width: 130%; overflow-x: auto;">
    <h5>Preencha as informações do evento:</h5>

    <!-- Wrapper para scroll horizontal -->
    <div style="width: 100%; overflow-x: auto;">
    <form id="formAgendamento" class="d-flex flex-nowrap align-items-center mb-4" style="min-width: 1200px;">

    <div class="me-2" style="width:150px;">
      <input type="date" id="data" class="form-control" required>
    </div>

    <div class="me-2" style="width:80px;">
      <select id="hora" class="form-control" required></select>
    </div>

    <div class="me-2" style="width:80px;">
      <select id="minuto" class="form-control" required></select>
    </div>

    <!-- Evento -->
    <div class="me-2" style="width:220px;">
      <select id="evento" class="form-select" required></select>
    </div>

    <!-- Botão entre evento e tipo -->
    <div class="me-2" style="width:50px;">
      <button type="button" class="btn btn-sm btn-primary" onclick="openGenericModal({
          title: 'Eventos',
          inputPlaceholder1: 'Nome do Evento',
          inputPlaceholder2: 'Preço',
          showPrice: true,
          table: 'myevent',
          idField: 'id_myevent',
          selectId: 'genericSelect',
          fieldsToLoad: ['myevent','price']
        });">+</button>
    </div>

    <!-- Tipo -->
    <div class="me-2" style="width:180px;">
      <select id="tipo" class="form-select" required></select>
    </div>

    <!-- Botão entre tipo e unidade -->
    <div class="me-2" style="width:50px;">
      <button type="button" class="btn btn-sm btn-primary" onclick="openGenericModal({
          title: 'Tipo de Evento',
          inputPlaceholder1: 'Tipo do Evento',
          showPrice: false,
          table: 'typeevent',
          idField: 'id_tpevent',
          selectId: 'genericSelect',
          fieldsToLoad: ['tpevent']
        });">+</button>
    </div>

    <!-- Unidade -->
    <div class="me-2" style="width:200px;">
      <select id="unidade" class="form-select" required></select>
    </div>

    <!-- Botão entre unidade e quantidade -->
    <div class="me-2" style="width:50px;">
      <button type="button" class="btn btn-sm btn-primary" onclick="openGenericModal({
          title: 'Unidades',
          inputPlaceholder1: 'Nome da Unidade',
          showPrice: false,
          table: 'units',
          idField: 'id_units',
          selectId: 'genericSelect',
          fieldsToLoad: ['units']
        });">+</button>
    </div>

    <!-- Vagas -->
    <div class="me-2" style="width:100px;">
      <input type="number" id="vagas" class="form-control" placeholder="Qtd" required>
    </div>

    <!-- Salvar -->
    <div style="width:120px;">
      <button type="submit" class="btn btn-success w-100">Salvar</button>
    </div>
  </form>
</div>

<h5>Datas disponibilizadas em sua agenda:</h5>
<div style="width: 100%; overflow-x:auto;">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Dia</th><th>Data</th><th>Hora</th><th>Evento</th><th>Tipo</th><th>Preço</th><th>Modo/Unidade</th><th>Vaga(s)</th><th>Excluir</th>
      </tr>
    </thead>
    <tbody id="tabelaAgendamentos"></tbody>
  </table>
</div>

  <!-- Modal Genérico -->
  <div class="modal fade" id="genericModal" data-bs-backdrop="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="genericModalTitle">Cadastro</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
              <form id="genericFormAdd">
                  <input type="text" id="genericInput1" name="" placeholder="" required>
                  <input type="number" step="0.01" id="genericInput2" name="" placeholder="" style="display:none;">
                  <button type="submit">Adicionar</button>
              </form>
              <form id="genericFormDelete">
                  <select id="genericSelect" name="genericSelect" size="5" required></select>
                  <button type="submit">Deletar</button>
              </form>
              <div id='config'></div>
          </div>
      </div>
    </div>

    


  <script>
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();

      // remove "active" from all links
      document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

      // add "active" only to the clicked one
      this.classList.add('active');

      // hide all sections
      document.querySelectorAll('#dashboard, #inscricoes, #historico')
              .forEach(sec => sec.style.display = 'none');

      // show target section
      const targetId = this.getAttribute('data-target');
      document.getElementById(targetId).style.display = 'block';
    });
  });
</script>


<!-- Scripts -->
<script src="app.js"></script>
<script src="modal.js"></script>
</body>
</html>
