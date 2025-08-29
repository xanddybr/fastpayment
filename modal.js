const eventModal = document.getElementById("eventModal");
const tpeventModal = document.getElementById("tpeventModal");
const unidadeModal = document.getElementById("unidadeModal");

const closeBtn = document.getElementById("closeModal");
const formDelete = document.getElementById("formDelete");
const genericSelect = document.getElementById("genericSelect");



function openGenericModal(type) {
    const modalTitle = document.getElementById("genericModalTitle");
    const input = document.getElementById("genericInput");
    const priceField = document.getElementById("genericPrice");
    
    // Configuração dinâmica
    switch (type) {
    case "event":
        modalTitle.textContent = "Eventos";
        input.placeholder = "Nome do Evento";
        priceField.style.display = "block";
        loadEvents();
        break;

    case "tpevent":
        modalTitle.textContent = "Tipo de Evento";
        input.placeholder = "Tipo do Evento";
        priceField.style.display = "none";
        loadTpEvents()
        break;

    case "unidade":
        modalTitle.textContent = "Unidades";
        input.placeholder = "Nome da Unidade";
        priceField.style.display = "none";
        loadUnits();
        break;

    default:
        console.warn("Tipo de modal não reconhecido:", type);
        modalTitle.textContent = "Cadastro";
        input.placeholder = "Digite aqui";
        priceField.style.display = "none";
}

    // Aqui você também pode mudar o endpoint de carregamento/deleção
    // Exemplo:
    // loadOptions(type); // popula o select de acordo com type

    const modal = new bootstrap.Modal(document.getElementById("genericModal"));
    modal.show();
   
}



/*
eventModal.addEventListener('shown.bs.modal', function () {
    loadEvents(); // sua função que carrega os eventos
});

eventModal.addEventListener('hidden.bs.modal', function () {
    location.reload() // sua função que carrega os eventos
}); */




// Carrega eventos
async function loadEvents() {
  genericSelect.innerHTML = "";
  try {
    const res = await fetch("./api/generic/list.php?table=myevent");
    const data = await res.json();
    data.forEach(ev => {
      const opt = document.createElement("option");
      opt.value = ev.id_myevent;
      opt.textContent = `${ev.myevent} - R$ ${parseFloat(ev.price).toFixed(2)}`;
      genericSelect.appendChild(opt);
    });
  } catch (err) {
    alert("Erro no carregamento das informações!")
  }
}

// Carrega tipo de eventos
async function loadTpEvents() {
  genericSelect.innerHTML = "";
  try {
    const res = await fetch("./api/generic/list.php?table=typeevent");
    const data = await res.json();
    data.forEach(ev => {
      const opt = document.createElement("option");
      opt.value = ev.id_tpevent;
      opt.textContent = `${ev.tpevent}`;
      genericSelect.appendChild(opt);
    });
  } catch (err) {
    alert("Erro no carregamento das informações!")
  }
}

// Carrega unidades
async function loadUnits() {
  genericSelect.innerHTML = "";
  try {
    const res = await fetch("./api/generic/list.php?table=units");
    const data = await res.json();
    data.forEach(ev => {
      const opt = document.createElement("option");
      opt.value = ev.id_units;
      opt.textContent = `${ev.units}`;
      genericSelect.appendChild(opt);
    });
  } catch (err) {
    alert("Erro no carregamento das informações!")
  }
}



// Form inserir
document.getElementById("genericFormAdd").addEventListener("submit", async function(e) {
    e.preventDefault();
    const token = localStorage.getItem("token");
    const formData = Object.fromEntries(new FormData(this));
    const res = await fetch("./api/insert.php", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
        body: JSON.stringify({ table: "myevent", values: formData })
    });
    const data = await res.json();
    if (data.success) { alert(data.message); loadEvents(); }
    else { alert("Erro: " + data.error); }
});


// Form deletar
document.getElementById("genericFormDelete").addEventListener("submit", async function(e) {
  e.preventDefault();
  if (confirm("Tem certeza que deseja excluir este evento?")) {
      const id = document.getElementById("genericSelect").value;
          const res = await fetch("./api/generic/delete.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ table: "myevent", id_field: "id_myevent", id_value: id })
          });
          const data = await res.json();
          if (data.success) { alert(data.message); loadEvents(); }
          else { alert("Erro: " + data.error); }
  }         
});

loadTpEvents();