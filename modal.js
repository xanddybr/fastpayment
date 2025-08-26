const modal = document.getElementById("myModal");
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");

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
/*
{
    "table":"myevent",
    "values":{
        "id_myevent": null,
        "myevent":"Reiki 2",
        "price": 300.00
}*/

formAdd.addEventListener("submit", async e => {
  e.preventDefault();

  const formAdd = document.getElementById("formAdd");
  let formData = new FormData(formAdd);

  // converte para objeto
  let obj = {};
  formData.forEach((value, key) => obj[key] = value);

  // monta o payload esperado
  let payload = {
    table: "myevent", // <-- troque pelo nome da sua tabela
    values: obj
  };

  const res = await fetch("api/generic/insert.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });

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

const formData = new FormData(formAdd);

  console.log(formData.entries)
  console.log(eventList.value)

 try {
    const res = await fetch("http://localhost:8000/api/generic/delete.php", 
    { method: 'DELETE', 
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ table: "myevent", id_field: "id_myevent", id_value: null })
    })
          if (res.ok) {
            alert("Deletado com sucess!")
            loadEvents();
          } else {
            alert("erro de deleção!")
          }
    
  } catch (error) {
      alert(error + " Erro ao tentar deletar o registro")
  } 

});

loadEvents();
