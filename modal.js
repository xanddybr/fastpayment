const formDelete = document.getElementById("genericFormDelete");
const formAdd = document.getElementById("genericFormAdd");

// Variável global para guardar os parâmetros atuais do modal
let currentConfig = null;

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
    const res = await fetch(`./api/generic/list.php?table=${table}`);
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

// Função para abrir modal genérico com configuração dinâmica
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
      loadGenericSelect();
      console.log(currentConfig)
      // Abre o modal
      const modal = new bootstrap.Modal(document.getElementById("genericModal"));
      modal.show();
    }

    document.getElementById("genericModal").addEventListener("hidden.bs.modal", function () {
        // Limpa os formulários
        const formAdd = document.getElementById("genericFormAdd");
        if (formAdd) formAdd.reset();

        const formDelete = document.getElementById("genericFormDelete");
        if (formDelete) formDelete.reset();

        // Limpa o select
        const selectEl = document.getElementById("genericSelect");
        if (selectEl) selectEl.innerHTML = "";

        // Zera a configuração
        currentConfig = null;

        // Atualiza a página pai
        location.reload(); 
    });

// Form inserir
    document.getElementById("genericFormAdd").addEventListener("submit", async function(e) {
      e.preventDefault();

      if (!currentConfig) return alert("Nenhuma configuração carregada!");

      const token = localStorage.getItem("token");
      const formAdd = this; // referência ao form
      const formData = Object.fromEntries(new FormData(formAdd));

      try {
        const res = await fetch("./api/insert.php", {
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

      