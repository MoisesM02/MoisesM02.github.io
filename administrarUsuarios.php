<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link rel="stylesheet" href="DataTables-1.10.22/css/dataTables.bootstrap4.min.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert2.min.js"></script>
    <script src="js/administrarUsuarios.js"></script>
    <script src="DataTables-1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="DataTables-1.10.22/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<?php include("includes/menu.php")?>
    <div class="container mt-4">
        <div id="tableContainer">
        
        </div>
    
    </div>
    <input type="hidden" id="userID">
<?php include("includes/changePassword.php") ?>
</body>
</html>