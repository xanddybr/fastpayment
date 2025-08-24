<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>FastPayment</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 900px;
            margin-top: 50px;
            margin-right: 400px;
            
        }

        /* Login centralizado */
        #login {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        /* Dashboard escondido inicialmente */
        #dashboard {
            display: none;
        }

        /* Tabela larga */
        table {
            width: 100%;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
        }

        /* Inputs e selects */
        input, select {
            min-width: 100%;
        }
    </style>
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
<div id="dashboardWrapper" style="width: 130%; margin: 0 auto;">

    <!-- Dashboard Wrapper -->
<div id="dashboardWrapper" style="display: flex; justify-content: center;">

    <!-- Dashboard Container -->
    <div id="dashboard" style="">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Mistura de Luz</h3>
            <button class="btn btn-danger" onclick="logout()">Sair</button>
        </div>

        <h5>Cadastro de Agendamento</h5>
        <form id="formAgendamento" class="row g-2 mb-4" style="width: 100%;">
            <div class="col-md-2">
                <input type="date" id="data" class="form-control" maxlength="10" placeholder="dd/mm/yyyy" required>
            </div>
            <div class="col-md-2">
                <input type="time" id="hora" class="form-control" placeholder="00:00" required>
            </div>
            <div class="col-md-3">
                <select id="evento" class="form-select" required></select>
            </div>
            <div class="col-md-2">
                <select id="tipo" class="form-select" required></select>
            </div>
            <div class="col-md-2">
                <select id="unidade" class="form-select" required></select>
            </div>
            <div class="col-md-1">
                <input type="number" id="vagas" class="form-control" placeholder="Qtd" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success w-100">Salvar</button>
            </div>
        </form>

        <h5>Agenda Mistura de Luz</h5>
        <div style="width: 100%; overflow-x:auto;">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Dia</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Evento</th>
                    <th>Tipo</th>
                    <th>Modo/Unidade</th>
                    <th>Preço</th>
                    <th>Vagas</th>
                    <th>Excluir</th>
                </tr>
                </thead>
                <tbody id="tabelaAgendamentos"></tbody>
            </table>
        </div>

    </div> <!-- Fim do Dashboard Container -->

</div> <!-- Fim do Dashboard Wrapper -->



<script>
const API_URL = "api";

// Mostra login ou dashboard dependendo do token
function checkLogin() {
    const token = localStorage.getItem("token");
    if (token) {
        mostrarDashboard();
        carregarEventos();
        carregarTipos();
        carregarAgendamentos();
        carregarUnidades();
    } else {
        mostrarLogin();
    }
}

function mostrarLogin() {
    document.getElementById("login").style.display = "block";
    document.getElementById("dashboard").style.display = "none";
}

function mostrarDashboard() {
    document.getElementById("login").style.display = "none";
    document.getElementById("dashboard").style.display = "block";
}

// Login
async function makeLogin() {
    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    const res = await fetch(`${API_URL}/login.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password: senha })
    });

    const data = await res.json();
    if (data.token) {
        localStorage.setItem("token", data.token);
        mostrarDashboard();
        carregarEventos();
        carregarTipos();
        carregarAgendamentos();
    } else {
        alert(data.error);
    }
}

// Logout
function logout() {
    localStorage.removeItem("token");
    mostrarLogin();
}

async function carregarEventos() {
    const res = await fetch(`${API_URL}/myevent.php`);
    const eventos = await res.json();
    let select = document.getElementById("evento");
    select.innerHTML =
    select.innerHTML = eventos.map(e => `<option value="${e.id_myevent}">${e.myevent}</option>`).join('');
}

async function carregarTipos() {
    const res = await fetch(`${API_URL}/typeevent.php`);
    const tipos = await res.json();
    let select = document.getElementById("tipo");
    select.innerHTML = tipos.map(t => `<option value="${t.id_tpevent}">${t.tpevent}</option>`).join('');
}

async function carregarUnidades() {
    const res = await fetch(`${API_URL}/unidade.php`);
    const unidade = await res.json();
    let select = document.getElementById("unidade");
    select.innerHTML = unidade.map(t => `<option value="${t.id_units}">${t.units}</option>`).join('');
}  

// Função utilitária para nome do dia
function getDayName(dateString) {
    const [day, month, year] = dateString.split('/');
    const date = new Date(year, month - 1, day);
    const dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    return dayNames[date.getDay()];
}

function formatDate(isoDate) {
    // isoDate vem no formato "YYYY-MM-DD"
    const partes = isoDate.split("-"); // ["2025", "08", "20"]
    const ano = partes[0];
    const mes = partes[1];
    const dia = partes[2];

    return `${dia}/${mes}/${ano}`;
}


// Carregar agendamentos
async function carregarAgendamentos() {
    const res = await fetch(`${API_URL}/schedule.php`);
    const agendamentos = await res.json();
    let tabela = document.getElementById("tabelaAgendamentos");
    tabela.innerHTML = agendamentos.map(a => `
        <tr>
            <td>${getDayName(a.date)}</td>
            <td>${a.date}</td>
            <td>${a.time}</td>
            <td>${a.myevent}</td>
            <td>${a.tpevent}</td>
            <td>${a.units}</td>
            <td>R$${a.price}</td>
            <td>${a.vacancies}</td>
            <td><button class="btn btn-danger btn-sm" onclick="deletarAgendamento(${a.id_schedule})">Excluir</button></td>
        </tr>
    `).join('');
}

// Deletar agendamento
function deletarAgendamento(id) {
    if (confirm("Tem certeza que deseja excluir este agendamento?")) {
        fetch(`${API_URL}/generic/delete.php`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ table: "schedule", id_field: "id_schedule", id_value: id })
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                alert("Agendamento excluído!");
                carregarAgendamentos();
            } else {
                alert("Erro ao excluir: " + result.error);
            }
        });
    }
}

// Salvar agendamento
document.getElementById("formAgendamento").addEventListener("submit", async function(e) {
    e.preventDefault();
    const token = localStorage.getItem("token");
    const data = {
        table: "schedule",
        values: {
            date: formatDate(document.getElementById("data").value),
            time: document.getElementById("hora").value,
            id_myevent: parseInt(document.getElementById("evento").value),
            id_tpEvent: parseInt(document.getElementById("tipo").value),
            id_units: parseInt(document.getElementById("unidade").value),
            vacancies: parseInt(document.getElementById("vagas").value)
        }
    };

    try {
        const response = await fetch(`${API_URL}/insert.php`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },
            body: JSON.stringify(data)
        });

        if(!response.ok) {
            alert('Não foi possível salvar seu agendamento');
        } else {
            alert("Agendamento realizado com sucesso!");
            carregarAgendamentos();
            this.reset();
        }
    } catch (error) {
        console.error("Erro ao inserir dados:", error);
        alert("Ocorreu um erro ao enviar os dados.");
    }
});

// Inicializa
checkLogin();
</script>
</body>
</html>
