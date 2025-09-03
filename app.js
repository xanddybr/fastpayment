const API_URL = "api";

// Verifica login
function checkLogin() {
    const token = localStorage.getItem("token");
    if (token) {
        carregarEventos();
        carregarTipos();
        carregarAgendamentos();
        carregarUnidades();
        carregaHora();
        carregaMinuto();
        mostrarDashboard();
    } else {
        mostrarLogin();
    }
}

checkLogin();

function mostrarLogin() {
    document.getElementById("login").style.display = "block";
    document.getElementById("dashboard").style.display = "none";
    document.getElementById("navMistura").style.display = "none";
}

function mostrarDashboard() {
    document.getElementById("login").style.display = "none";
    document.getElementById("dashboard").style.display = "block";
     document.getElementById("navMistura").style.display = "block";
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
        carregarEventos(); carregarTipos(); carregarAgendamentos(); carregarUnidades();
        carregaHora(); carregaMinuto(); mostrarDashboard();
    } else {
        alert(data.error);
    }
}

function logout() { localStorage.removeItem("token"); mostrarLogin(); }

// Carregar selects
async function carregarEventos() {
    const res = await fetch(`${API_URL}/myevent.php`);
    const eventos = await res.json();
    let select = document.getElementById("evento");
    select.innerHTML = `<option value='' selected>Selecione um evento</option>` +
        eventos.map(e => `<option value="${e.id_myevent}">${e.myevent}</option>`).join('');
}

async function carregarTipos() {
    const res = await fetch(`${API_URL}/typeevent.php`);
    const tipos = await res.json();
    let select = document.getElementById("tipo");
    select.innerHTML = `<option value='' selected>Tipo</option>` +
        tipos.map(t => `<option value="${t.id_tpevent}">${t.tpevent}</option>`).join('');
}

async function carregarUnidades() {
    const res = await fetch(`${API_URL}/unidade.php`);
    const unidades = await res.json();
    let select = document.getElementById("unidade");
    select.innerHTML = `<option value='' selected>Unidade</option>` +
        unidades.map(u => `<option value="${u.id_units}">${u.units}</option>`).join('');
}

// Horário
function carregaHora() {
    const select = document.getElementById("hora");
    select.innerHTML = `<option value='' selected>H</option>`;
    for (let i = 0; i <= 23; i++) {
        let opt = document.createElement("option");
        opt.value = opt.text = i.toString().padStart(2, "0");
        select.appendChild(opt);
    }
}

function carregaMinuto() {
    const select = document.getElementById("minuto");
    select.innerHTML = `<option value='' selected>M</option>`;
    for (let i = 0; i <= 5; i++) {
        let opt = document.createElement("option");
        opt.value = opt.text = i + "0";
        select.appendChild(opt);
    }
}

// Utils
function getDayName(dateString) {
    const [day, month, year] = dateString.split('/');
    const date = new Date(year, month - 1, day);
    return ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'][date.getDay()];
}

function formatDate(isoDate) {
    let [ano, mes, dia] = isoDate.split("-");
    return `${dia}/${mes}/${ano}`;
}

function getDateTime() {
    const now = new Date();
    return now.toISOString().slice(0,19).replace("T"," ");
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
            if (result.success) { alert("Agendamento excluído!"); carregarAgendamentos(); }
            else { alert("Erro ao excluir: " + result.error); }
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
            time: document.getElementById("hora").value + ":" + document.getElementById("minuto").value + ":00",
            id_myevent: parseInt(document.getElementById("evento").value),
            id_tpEvent: parseInt(document.getElementById("tipo").value),
            id_units: parseInt(document.getElementById("unidade").value),
            vacancies: parseInt(document.getElementById("vagas").value),
            created_at: getDateTime()
        }
    };

    try {
        const response = await fetch(`${API_URL}/insert.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
            body: JSON.stringify(data)
        });

        if (!response.ok) alert('Não foi possível salvar seu agendamento');
        else {
            alert("Agendamento realizado com sucesso!");
            carregarAgendamentos();
            this.reset();
        }
    } catch (error) {
        console.error("Erro ao inserir dados:", error);
        alert("Ocorreu um erro ao enviar os dados.");
    }
});
