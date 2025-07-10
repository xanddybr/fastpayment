
<!DOCTYPE html>
<html>
<head>
    <title>Fetch WordPress Users</title>
</head>
<body>
<form id="myForm">
    <h1>Implementatio of Mercado Pago for Mistura de luz - BETA 2 Teste</h1>
    <button type="submit">Load Users</button>
    <pre id="output"></pre>
</form>

    <script>
        document.getElementById('myForm').addEventListener('submit', function(e) {
          e.preventDefault(); // Prevent default form submission

          const formData = new FormData(this);

          fetch('get_users.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.text())
          .then(data => {
            console.log('Server response:', data);
            document.getElementById('output').textContent = JSON.stringify(data, null, 2);
          })
          .catch(error => {
            console.error('Error:', error);
          });
        });

    </script>

</body>

</html>
