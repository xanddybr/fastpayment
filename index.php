<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FastPayment</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 50px; }
        #dashboard { display: none; }
    </style>
</head>
<body>
    <div class="container">

        <!-- Login -->
        <div id="login">
            <h3 class="mb-3">Login</h3>
            <div class="mb-3">
                <input type="email" id="email" class="form-control" placeholder="Seu e-mail">
            </div>
            <div class="mb-3">
                <input type="password" id="senha" class="form-control" placeholder="Senha">
            </div>
            <button class="btn btn-primary w-100" onclick="login()">Entrar</button>
        </div>

        <!-- Dashboard -->
        <div id="dashboard">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Dashboard</h3>
                <button class="btn btn-danger" onclick="logout()">Sair</button>
            </div>

            <h5>Cadastrar Agendamento</h5>
            <form id="formAgendamento" class="row g-2 mb-4">
                <div class="col-md-2">
                    <input type="date" id="data" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="time" id="hora" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <select id="evento" class="form-select" required></select>
                </div>
                <div class="col-md-3">
                    <select id="tipo" class="form-select" required></select>
                </div>
                <div class="col-md-2">
                    <input type="number" id="vagas" class="form-control" placeholder="Vagas" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
            <h5>Agenda Mistura de Luz</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Hora</th>
                        <th>Evento</th>
                        <th>Tipo</th>
                        <th>Preço</th>
                        <th>Vagas</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody id="tabelaAgendamentos"></tbody>
            </table>
        </div>
     </div>
     <script>
        const API_URL = "api"; // ajuste conforme sua pasta

        // Função para validar token
        async function validarToken() {
            const token = localStorage.getItem("token");
            if (!token) {
                mostrarLogin();
                return;
            }

            try {
                const res = await fetch(`${API_URL}/validate.php`, {
                    method: "GET",
                    headers: { "Authorization": `Bearer ${token}` }
                });
                const data = await res.json();

                if (data.success) {
                    mostrarDashboard();
                    carregarEventos();
                    carregarTipos();
                    carregarAgendamentos();
                } else {
                    mostrarLogin();
                }
            } catch {
                mostrarLogin();
            }
        }

        // Login
        async function login() {
            const email = document.getElementById("email").value;
            const senha = document.getElementById("senha").value;

            const res = await fetch(`${API_URL}/login.php`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: email, password: senha })
            });

            const data = await res.json();

            if (data.token) {
                localStorage.setItem("token", data.token);
                mostrarDashboard();
                carregarEventos();
                carregarTipos();
                carregarAgendamentos();
            } else {
                alert("Credenciais inválidas");
            }
        }

        // Logout
        function logout() {
            localStorage.removeItem("token");
            mostrarLogin();
        }

        function deletarAgendamento(id) {
            if (confirm("Tem certeza que deseja excluir este agendamento? ")) {
                fetch('api/generic/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ table: "schedule", id_field: "id_schedule", id_value: id })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert("Agendamento excluído com sucesso!");
                        carregarAgendamentos();
                    } else {
                        alert("Erro ao excluir: " + result.error);
                    }
                });
            }
        }

        // Mostrar/Ocultar
        function mostrarLogin() {
            document.getElementById("login").style.display = "block";
            document.getElementById("dashboard").style.display = "none";
        }

        function mostrarDashboard() {
            document.getElementById("login").style.display = "none";
            document.getElementById("dashboard").style.display = "block";
        }

        // Carregar opções de Evento
        async function carregarEventos() {
           const res = await fetch(`${API_URL}/myevent.php`);
            const eventos = await res.json();
            let select = document.getElementById("evento");
            select.innerHTML = "";
            eventos.forEach(e => {
              select.innerHTML += `<option value="${e.id_myevent}">${e.myevent}</option>`;
            console.log(e); 
            });
        }

        // Carregar opções de Tipo
        async function carregarTipos() {
            const res = await fetch(`${API_URL}/typeevent.php`);
            const tipos = await res.json();
            let select = document.getElementById("tipo");
            select.innerHTML = "";
            tipos.forEach(t => {
                select.innerHTML += `<option value="${t.id_tpevent}">${t.tpevent}</option>`;
            });
        }

        // Carregar agendamentos
        async function carregarAgendamentos() {
            const res = await fetch(`${API_URL}/schedule.php`);
            const agendamentos = await res.json();
            let tabela = document.getElementById("tabelaAgendamentos");
            tabela.innerHTML = "";
            agendamentos.forEach(a => {
                tabela.innerHTML += `
                    <tr>
                        <td>${a.date}</td>
                        <td>${a.time}</td>
                        <td>${a.myevent}</td>
                        <td>${a.tpevent}</td>
                        <td>R$ ${a.price}</td>
                        <td>${a.vacancies}</td><td>
                        <button class="btn btn-danger btn-sm" onclick="deletarAgendamento(${a.id_schedule})">
                            Excluir
                        </button>
                    </td>
                </tr>
                `;
            });
        }

        // Salvar agendamento
        document.getElementById("formAgendamento").addEventListener("submit", async function(e) {
            e.preventDefault();
            const token = localStorage.getItem("token");

            const data = {
                table: "schedule",
                values: {
                    date: document.getElementById("data").value,
                    time: document.getElementById("hora").value,
                    id_myevent: parseInt(document.getElementById("evento").value),
                    id_tpEvent: parseInt(document.getElementById("tipo").value),
                    vacancies: parseInt(document.getElementById("vagas").value)
                }
            };

            await fetch(`${API_URL}/insert.php`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify(data)
            });

            carregarAgendamentos();
            this.reset();
        });

        // Valida token ao carregar
        validarToken();
    </script>
</body>
</html>
