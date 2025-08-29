

 fetch("http://localhost:8000/api/generic/list.php?table=schedule", 
    { method: 'GET'})
    
 .then(response => response.json())
 .then(data => console.log(console.log(data)))
 .catch(err => console.error(err, "Erro ao na tentativa de deleção"))