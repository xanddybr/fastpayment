fetch('api/login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: "arthur2025@gmail.com",
        password: "senha123"
    })
})
.then(res => res.json())
.then(data => {
    if (data.token) {
        localStorage.setItem('token', data.token);
    console.log("Logon efetuado com sucesso!")
    const token = localStorage.getItem('token');
    console.log(token)
       //  window.location.href = 'dashboard.html'; // redireciona para área logada
    } else {
       console.log("Este usuário não existe...");
    }
});

