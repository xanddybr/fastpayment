const formDelete = document.getElementById("genericFormDelete");
const formAdd = document.getElementById("genericFormAdd");
const genericSelect = document.getElementById("genericSelect");
const genericModal = document.getElementById("genericModal");

function openGenericModal(config) {
  const modalTitle = document.getElementById("genericModalTitle");
  const input = document.getElementById("genericInput");
  const priceField = document.getElementById("genericPrice");

  // Atualiza os elementos do modal
  modalTitle.textContent = config.title;
  input.placeholder = config.inputPlaceholder;
  priceField.style.display = config.showPrice ? "block" : "none";

  // Salva a configuração global para uso em insert/delete/load
  currentConfig = {
    table: config.table,
    idField: config.idField,
    selectId: config.selectId,
    fieldsToLoad: config.fieldsToLoad
  };

  // Carrega os dados no select automaticamente
  //loadGenericSelect();

  // Abre o modal
  const modal = new bootstrap.Modal(document.getElementById("genericModal"));
  modal.show();
}

/*
genericModal.addEventListener('hidden.bs.modal', function () {
    formAdd.reset()
    location.reload(); 
});

function openGenericModal(type) {
  const modalTitle = document.getElementById("genericModalTitle");
  const input = document.getElementById("genericInput");
  const priceField = document.getElementById("genericPrice");

  switch (type) {
    case "Eventos":
      modalTitle.textContent = "Eventos";
      input.placeholder = "Nome do Evento";
      priceField.style.display = "block";
      loadGenericSelect("myevent", "id_myevent", ["myevent","price"]);
      break;

    case "tipoEvento":
      modalTitle.textContent = "Tipo de Evento";
      input.placeholder = "Tipo do Evento";
      priceField.style.display = "none";
      loadGenericSelect("typeevent", "id_tpevent", ["tpevent"]);
      break;

    case "unidade":
      modalTitle.textContent = "Unidades";
      input.placeholder = "Nome da Unidade";
      priceField.style.display = "none";
      loadGenericSelect("units", "id_units", ["units"]);
      break;

    default:
      console.warn("Tipo de modal não reconhecido:", type);
      modalTitle.textContent = "Cadastro";
      input.placeholder = "Digite aqui";
      priceField.style.display = "none";
  }

    const modal = new bootstrap.Modal(document.getElementById("genericModal"));
    modal.show();
}
*/

// Carrega eventos
async function loadGenericSelect(table, idField, textFields = []) {
  genericSelect.innerHTML = "";
  try {
    const res = await fetch(`./api/generic/list.php?table=${table}`);
    const data = await res.json();

    data.forEach(row => {
      const opt = document.createElement("option");
      opt.value = row[idField];

      let textParts = textFields.map(field => {
        if (field === "price") {
          return "R$ " + parseFloat(row[field]).toFixed(2);
        }
        return row[field];
      });

      opt.textContent = textParts.join(" - ");
      genericSelect.appendChild(opt);
    });

  } catch (err) {
    alert("Erro no carregamento das informações!");
    console.error(err);
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
    if (data.success) { alert(data.message); loadGenericSelect("myevent", "id_myevent", ["myevent","price"]); formAdd.reset() }
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
          loadGenericSelect("myevent", "id_myevent", ["myevent","price"]);
          const data = await res.json();
          if (!data.success) { alert(data.error); }}
});

