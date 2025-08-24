const modal = document.getElementById("myModal");
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");
const formAdd = document.getElementById("formAdd");
const formDelete = document.getElementById("formDelete");
const eventList = document.getElementById("eventList");

// Abrir modal (desabilita scroll da página)
openBtn.onclick = () => {
  modal.style.display = "block";
  document.body.style.overflow = "hidden";
  loadEvents();
};

// Fechar modal (reativa scroll)
closeBtn.onclick = () => {
  modal.style.display = "none";
  document.body.style.overflow = "auto";
};

window.onclick = e => {
  if (e.target === modal) {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
  }
};

// Buscar lista
async function loadEvents() {
  eventList.innerHTML = "";
  try {
    const res = await fetch("/api/generic/list.php?table=myevent");
    const data = await res.json();
    data.forEach(ev => {
      const opt = document.createElement("option");
      opt.value = ev.id_myevent;
      opt.textContent = `${ev.myevent} - R$ ${parseFloat(ev.price).toFixed(2)}`;
      eventList.appendChild(opt);
    });
  } catch (err) {
    console.error("Erro ao carregar eventos", err);
  }
}

// Adicionar evento
formAdd.addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(formAdd);
  const res = await fetch("api/generic/insert.php", { method: "POST", body: formData });
  if (res.ok) {
    formAdd.reset();
    loadEvents();
  } else {
    alert("Erro ao adicionar!");
  }
});

// Deletar evento
formDelete.addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(formDelete);
  const res = await fetch("api/generic/delete.php", { method: "POST", body: formData });
  if (res.ok) {
    loadEvents();
  } else {
    alert("Erro ao deletar!");
  }
});

loadEvents();
