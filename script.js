 let id = 2

            
                fetch('http://localhost:8000/api/generic/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ table: "schedule", id_field: "id_schedule", id_value: id })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        console.log("arquivo deletado")
                    } else {
                        alert("Erro ao excluir: " + result.error);
                    }
                });
            
      
        