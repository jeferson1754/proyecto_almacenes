<div class="modal fade" id="ModalEditarProducto<?php echo $mostrar2['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="actualizar_producto.php">
        <input type="hidden" name="id" value="<?php echo $mostrar2['ID']; ?>">

        <div class="modal-body">
          <div class="form-group" style="margin-top:-15px">
            <label for="nombre_producto" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre_producto" id="producto" list="productos" class="form-control" value="<?php echo $mostrar2['Nombre']; ?>" required>
            <datalist id="productos">
              <?php
              $productos = $conexion->query("SELECT DISTINCT Nombre FROM `productos`;");

              foreach ($productos as $producto) {
                echo "<option value='" . $producto['Nombre'] . "'></option>";
              }

              ?>
            </datalist>
          </div>

          <div class="form-group" style="margin-top:10px">
            <label for="ubi" class="form-label">Valor:</label>
            <input type="number" class="form-control" min="0" name="precio" value="<?php echo $mostrar2['Valor']; ?>">
          </div>

          <div class="form-group" style="margin-top:10px">
            <label for="nombre_almacen" class="form-label">Tienda:</label>
            <input type="text" name="nombre_almacen" id="nombre" value="<?php echo $mostrar2['Nombre_Almacen']; ?>" list="nombres" class="form-control" required>
            <datalist id="nombres">
              <?php
              $nombres = $conexion->query("SELECT DISTINCT Nombre_Almacen FROM `tiendas`;");

              foreach ($nombres as $nombre) {
                echo "<option value='" . $nombre['Nombre_Almacen'] . "'></option>";
              }

              ?>
            </datalist>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!---fin ventana Update --->