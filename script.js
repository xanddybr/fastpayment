function login() {
  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;

  fetch('api/login.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({email, senha})
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      localStorage.setItem("userName", data.nome);
      window.location.href = "dashboard.html";
    } else {
      alert("Login inválido!");
    }
  });
}
