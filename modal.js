const modal = document.getElementById("myModal");
const openBtn = document.getElementById("openModalEvent");
const closeBtn = document.getElementById("closeModal");
const formDelete = document.getElementById("formDelete");
const eventList = document.getElementById("eventList");

// Abrir modal (desabilita scroll da página)
meuModal.addEventListener('shown.bs.modal', function () {
    loadEvents(); // sua função que carrega os eventos
});

// Fechar modal (reativa scroll)
closeBtn.onclick = () => {
  modal.style.display = "none";
  location.reload()
};



// Buscar lista
async function loadEvents() {
  eventList.innerHTML = "";
  try {
    const res = await fetch("./api/generic/list.php?table=myevent");
    const data = await res.json();
    data.forEach(ev => {
      const opt = document.createElement("option");
      opt.value = ev.id_myevent;
      opt.textContent = `${ev.myevent} - R$ ${parseFloat(ev.price).toFixed(2)}`;
      eventList.appendChild(opt);
    });
  } catch (err) {
    alert("Erro no carregamento das informações!")
  }
}

document.getElementById("formAdd").addEventListener("submit", async function(e) {
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


// Form deletar evento
document.getElementById("formDelete").addEventListener("submit", async function(e) {
    e.preventDefault();
    const id = document.getElementById("eventList").value;
    const res = await fetch("./api/generic/delete.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ table: "myevent", id_field: "id_myevent", id_value: id })
    });
    const data = await res.json();
    if (data.success) { alert(data.message); loadEvents(); }
    else { alert("Erro: " + data.error); }
});

loadEvents();