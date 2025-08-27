<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FastPayment</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- estilos próprios -->
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
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
        <button class="btn btn-primary w-100" onclick="makeLogin()">Entrar</button>
    </div>

    <!-- Dashboard Wrapper -->
    <div id="dashboardWrapper">
        <div id="dashboard" style="display: flex; justify-content: center; margin: 0 -280px 0 -100px">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Agenda - Mistura de Luz</h3>
                <button class="btn btn-danger" onclick="logout()">Sair</button>
            </div>

            <h5>Preencha as informações do evento:</h5>
            <form id="formAgendamento" class="row g-1 mb-4">
                <div class="col-md-2">
                    <input type="date" id="data" class="form-control" maxlength="10" required>
                </div>
                <div class="col-md-1"><select id="hora" class="form-control" required></select></div>
                <div class="col-md-1"><select id="minuto" class="form-control" required></select></div>
                <div class="col-md-3"><select id="evento" class="form-select" required></select></div>
                <div class="col-md-2"><select id="tipo" class="form-select" required></select></div>
                <div class="col-md-2"><select id="unidade" class="form-select" required></select></div>
                <div class="col-md-1"><input type="number" id="vagas" class="form-control" placeholder="Qtd" required></div>
                <div class="col-md-1"><button type="submit" class="btn btn-success">Salvar</button></div>
                <div class="col-md-1"><button type="button" class="btn btn-success" id="openModal">Event</button></div>
            </form>

            <h5>Datas disponibilizadas em sua agenda:</h5>
            <div style="width: 100%; overflow-x:auto;">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Dia</th><th>Data</th><th>Hora</th><th>Evento</th>
                            <th>Tipo</th><th>Modo/Unidade</th><th>Preço</th>
                            <th>Vaga(s)</th><th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaAgendamentos"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h6>Cadastrar Evento</h6>
        <form id="formAdd">
            <input type="text" name="myevent" placeholder="Nome do Evento" required>
            <input type="number" step="0.01" name="price" placeholder="Preço" required>
            <button type="submit">Adicionar</button>
        </form>
        <form id="formDelete">
            <select id="eventList" name="eventList" size="5" required></select>
            <button type="submit">Deletar</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="app.js"></script>
<script src="modal.js"></script>
</body>
</html>
