const res = await fetch('http://localhost:8000/api/myevent.php');
            const eventos = await res.json();
            let select = document.getElementById("evento");
            select.innerHTML = "";
            eventos.forEach(e => {
              select.innerHTML += `<option value="${e.id_myevent}">${e.myevent}</option>`;
            console.log(e);
});
