<style>
.menu{
  background-color: #343A40 ;
}
</style>
<script src="js/obtenerNombreEmpresa.js"></script>
<nav class="navbar menu navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="<?php echo (isset($_SESSION["url"]))? $_SESSION["url"] : 'index.php' ?>"><span id ="enterpriseName"></span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo (isset($_SESSION["url"]))? $_SESSION["url"] : 'index.php' ?>">Inicio<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Servicios
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="librodeservicios.php">Libro de servicios</a>
          <a class="dropdown-item" href="servicios.php">Administrar servicios</a>
        </div>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Productos
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="productos.php">Administrar productos</a>
          <a class="dropdown-item" href="recibosdeproductos.php">Recibir productos</a>
          <a class="dropdown-item" href="vender.php">Vender productos</a>
          <a class="dropdown-item" href="devolverventa.php">Devolver ventas</a>
          <a class="dropdown-item" href="devolvercompra.php">Devolver compras</a>
        </div>
      </li>
      <li class="nav-item">
            <a class="nav-link" href="crear.php">Administrar</a>
      </li>
      <li class="nav-item active">
            <a class="nav-link" href="cerrarsesion.php">Cerrar sesión<span class="sr-only">(current)</span></a>
        </li>
        </ul>
    </div>
    </nav>