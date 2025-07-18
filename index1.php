<!DOCTYPE html>
<html>
<head>
  <title>Users Table</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
  <h1>User List</h1>
  <table border="1" id="usersTable" style="width: 1000px;">
    <thead>
      <tr><th>ID</th><th>Name</th><th>Email</th></tr>
    </thead>
    <tbody></tbody>
  </table>
  <br>
  <input type="button" value="Carregar dados" onClick="loadData()">
  <script>
    function loadData(){
      axios.get('get-users.php')
      .then(response => {
        const users = response.data;
        const tbody = document.querySelector('#usersTable tbody');
        users.forEach(user => {
          const row = `<tr><td>${user.id_person}</td><td>${user.full_name}</td><td>${user.password}</td></tr>`;
          tbody.innerHTML += row;
        });
      })
      .catch(error => {
        console.error('Error fetching users:', error);
      });
    } 
  </script>
</body>
</html>
