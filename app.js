// check login
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

//make Login
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

    const res = await fetch(`api/login.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password: senha })
    });

    const data = await res.json();
    if (data.token) {
        localStorage.setItem("token", data.token);
        carregarEventos(); 
        carregarTipos();
        carregarAgendamentos(); 
        carregarUnidades();
        carregaHora(); 
        carregaMinuto(); 
        mostrarDashboard();
    } else {
        alert(data.error);
    }
}

function logout() { localStorage.removeItem("token"); mostrarLogin(); }

// Carregar selects
async function carregarEventos() {
    const res = await fetch("./api/generic/list.php?table=myevent");
    const eventos = await res.json();
    let select = document.getElementById("evento");
    select.innerHTML = `<option value='' selected>Selecione um evento</option>` +
        eventos.map(e => `<option value="${e.id_myevent}">${e.myevent}</option>`).join('');
}
// load typeevent
async function carregarTipos() {
    const res = await fetch("./api/generic/list.php?table=typeevent");
    const tipos = await res.json();
    let select = document.getElementById("tipo");
    select.innerHTML = `<option value='' selected>Tipo</option>` +
        tipos.map(t => `<option value="${t.id_tpevent}">${t.tpevent}</option>`).join('');
}
// load units
async function carregarUnidades() {
    const res = await fetch("./api/generic/list.php?table=units");
    const unidades = await res.json();
    let select = document.getElementById("unidade");
    select.innerHTML = `<option value='' selected>Unidade</option>` +
        unidades.map(u => `<option value="${u.id_units}">${u.units}</option>`).join('');
}

// Load format time
function carregaHora() {
    const select = document.getElementById("hora");
    select.innerHTML = `<option value='' selected>H</option>`;
    for (let i = 0; i <= 23; i++) {
        let opt = document.createElement("option");
        opt.value = opt.text = i.toString().padStart(2, "0");
        select.appendChild(opt);
    }
}

// Load minutes in selectbox
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

// Formate date
function formatDate(isoDate) {
    let [ano, mes, dia] = isoDate.split("-");
    return `${dia}/${mes}/${ano}`;
}

//GetTime
function getDateTime() {
    const agora = new Date();

  const options = {
    timeZone: 'America/Sao_Paulo',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false 
  };

  const partes = new Intl.DateTimeFormat('pt-BR', options).formatToParts(agora);
  const mapa = Object.fromEntries(partes.map(p => [p.type, p.value]));

  return `${mapa.year}-${mapa.month}-${mapa.day} ${mapa.hour}:${mapa.minute}:${mapa.second}`;
}

// Carregar agendamentos
async function carregarAgendamentos() {
    const res = await fetch(`api/schedule.php`);
    const agendamentos = await res.json();
    let tabela = document.getElementById("tabelaAgendamentos");

    tabela.innerHTML = agendamentos.map(a => `
        <tr>
            <td>${getDayName(a.date)}</td>
            <td>${a.date}</td>
            <td>${a.time}</td>
            <td>${a.myevent}</td>
            <td>${a.tpevent}</td>
            <td>R$${a.price}</td>
            <td>${a.units}</td>
            <td>${a.vacancies}</td>
            <td><button class="btn btn-danger btn-sm" onclick="deletarAgendamento(${a.id_schedule})">Excluir</button></td>
        </tr>
    `).join('');
}

// Deletar agendamento
function deletarAgendamento(id) {
    if (confirm("Tem certeza que deseja excluir este agendamento?")) {
        fetch(`api/generic/delete.php`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ table: "schedule", id_field: "id_schedule", id_value: id })
        })
        .then(res => res.json())
        .catch(error => { alert("Erro ao tentar excluir essa informação", res.error + error)})
        carregarAgendamentos();   
    }
}

// Salvar agendamento
document.getElementById("formAgendamento").addEventListener("submit", async function(e) {
    e.preventDefault();
    const token = localStorage.getItem("token");
    const data = {
        table: "schedule",
        values: {
            date: formatDate(document.getElementById("data").value.trim()),
            time: document.getElementById("hora").value + ":" + document.getElementById("minuto").value,
            id_myevent: parseInt(document.getElementById("evento").value.trim()),
            id_tpEvent: parseInt(document.getElementById("tipo").value.trim()),
            id_units: parseInt(document.getElementById("unidade").value.trim()),
            vacancies: parseInt(document.getElementById("vagas").value.trim()),
            created_at: getDateTime()
        }
    };

    try {
        const response = await fetch(`./api/generic/insert.php`, {
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
})

    const formDelete = document.getElementById("genericFormDelete");
    const formAdd = document.getElementById("genericFormAdd");

    // Função genérica para carregar registros no <select> atual
    async function loadGenericSelect() {
      if (!currentConfig) {
        console.warn("Nenhuma configuração carregada para o modal.");
        return;
      }

      const { table, idField, selectId, fieldsToLoad } = currentConfig;
      const selectEl = document.getElementById(selectId);
      selectEl.innerHTML = "";

      try {
        const res = await fetch(`./api/generic/list.php?table=myevent`);
        const data = await res.json();

        data.forEach(row => {
          const opt = document.createElement("option");
          opt.value = row[idField];

          // Monta o texto a partir dos campos configurados
          let textParts = fieldsToLoad.map(field => {
            if (field === "price") {
              return "R$ " + parseFloat(row[field]).toFixed(2);
            }
            return row[field];
          });

          opt.textContent = textParts.join(" - ");
          selectEl.appendChild(opt);
          
        });

      } catch (err) {
        alert("Erro no carregamento das informações!");
        console.error(err);
      }
    }

    // Variável global para guardar os parâmetros atuais do modal
    let currentConfig = null

   // Função para abrir modal genérico com configuração dinâmica
  function openGenericModal(config) {
      const genericModalTitle = document.getElementById("genericModalTitle");
      const genericInput1 = document.getElementById("genericInput1");
      const genericInput2 = document.getElementById("genericInput2");


        //Aplica o titulo ao modal de acordo com a função
        genericModalTitle.textContent = config.title;
      // 🔹 Aplica a config carregada atravez do objeto

      if(config.showPrice){
        genericInput1.placeholder = config.inputPlaceholder[0];
        genericInput1.name = config.fieldsToLoad[0];
        genericInput2.placeholder = config.inputPlaceholder[1];
        genericInput2.name = config.fieldsToLoad[1];
        genericInput2.style.display = "block";
        genericInput2.setAttribute('required','')
      } else {
        genericInput1.placeholder = config.inputPlaceholder[0];
        genericInput1.name = config.fieldsToLoad[0];
        genericInput2.style.display = "none";
        genericInput2.removeAttribute('placeholder')
        genericInput2.removeAttribute('name')
        genericInput2.removeAttribute('required')
      }
      
      // 🔹 Atualiza config global
      currentConfig = {
        table: config.table || null,
        idField: config.idField || null,
        selectId: config.selectId || null,
        fieldsToLoad: config.fieldsToLoad || []
      };

      loadGenericSelect();

      const modal = new bootstrap.Modal(document.getElementById("genericModal"));
      modal.show();
    }

      

    document.getElementById("genericModal").addEventListener("hidden.bs.modal", function () {
 // Limpa os formulários
        const formAdd = document.getElementById("genericFormAdd");
        if (formAdd) formAdd.reset();

        const formDelete = document.getElementById("genericFormDelete");
        if (formDelete) formDelete.reset();

        // Esconde o genericinput2 e remove atributo requerido

             /*  
            genericInput2.name
            genericInput2.style.display = "none";
            genericInput2.removeAttribute('required') */
         

        // Limpa o select
        const selectEl = document.getElementById("genericSelect");
        if (selectEl) selectEl.innerHTML = "";
        carregarEventos()
        carregarTipos()
        carregarUnidades()
        config = null;
        // Zera a configuração
        currentConfig = null;
        
    });

// Form inserir
    document.getElementById("genericFormAdd").addEventListener("submit", async function(e) {
      e.preventDefault();
      const genericInput1 = document.getElementById("genericInput1")

      if (!currentConfig) return alert("Nenhuma configuração carregada!");

      const token = localStorage.getItem("token")
      const formAdd = this; // referência ao form
      const formData = Object.fromEntries(new FormData(formAdd))
      formData.created_at = getDateTime()

      try {
        const res = await fetch("./api/generic/insert.php", {
          method: "POST",
          headers: { 
            "Content-Type": "application/json", 
            "Authorization": `Bearer ${token}` 
          },
          body: JSON.stringify({ 
            table: currentConfig.table, 
            values: formData 
          })
        });

        const data = await res.json();

        if (data.success) {
          alert(data.message);
          
          // Atualiza a lista no select
          loadGenericSelect(
            currentConfig.table, 
            currentConfig.idField, 
            currentConfig.fieldsToLoad
          );

          // Reseta o form
          formAdd.reset();
          genericInput1.focus()

          /* Fecha o modal (opcional, se quiser fechar após inserir)
          const modalEl = document.getElementById("genericModal");
          const modal = bootstrap.Modal.getInstance(modalEl);
          modal.hide(); */

        } else {
          alert(data.error || "Erro ao inserir registro.");
        }
      } catch (err) {
        alert("Erro de comunicação com o servidor: " + err.message);
      }
      
    });

// Form deletar
    document.getElementById("genericFormDelete").addEventListener("submit", async function(e) {
      e.preventDefault();

      if (!currentConfig) return alert("Nenhuma configuração carregada!");

      if (confirm("Tem certeza que deseja excluir este registro?")) {
        const id = document.getElementById(currentConfig.selectId).value;

        try {
          const res = await fetch("./api/generic/delete.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              table: currentConfig.table,
              id_field: currentConfig.idField,
              id_value: id
            })
          });

          const data = await res.json();

          if (data.success) {
            // Atualiza o select com os dados da tabela atual
            loadGenericSelect(currentConfig.table, currentConfig.idField, currentConfig.fieldsToLoad);

            /*
            const modalEl = document.getElementById("genericModal");
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide(); */

          } else {
            alert(data.error || "Erro ao excluir o registro.");
          }
        } catch (err) {
          alert("Erro de comunicação com o servidor: " + err.message);
        }
      }
    });

    console.log(currentConfig)
    checkLogin();
 