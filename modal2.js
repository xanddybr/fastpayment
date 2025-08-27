// Modal
const modal = document.getElementById("myModal");
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");

openBtn.onclick = () => modal.style.display = "block";
closeBtn.onclick = () => modal.style.display = "none";
window.onclick = (event) => { if (event.target === modal) modal.style.display = "none"; }

// Form adicionar evento
document.getElementById("formAdd").addEventListener("submit", async function(e) {
    e.preventDefault();
    const token = localStorage.getItem("token");
    const formData = Object.fromEntries(new FormData(this));

    const res = await fetch("api/insert.php", {
        method: "POST",
        headers: { "Content-Type": "application/json", "Authorization": `Bearer ${token}` },
        body: JSON.stringify({ table: "myevent", values: formData })
    });
    const data = await res.json();
    if (data.success) { alert("Evento adicionado!"); carregarEventos(); }
    else { alert("Erro: " + data.error); }
});

// Form deletar evento
document.getElementById("formDelete").addEventListener("submit", async function(e) {
    e.preventDefault();
    const id = document.getElementById("eventList").value;

    const res = await fetch("api/generic/delete.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ table: "myevent", id_field: "id_myevent", id_value: id })
    });
    const data = await res.json();
    if (data.success) { alert("Evento deletado!"); carregarEventos(); }
    else { alert("Erro: " + data.error); }
});
