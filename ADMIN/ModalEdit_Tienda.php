<!--ventana para Update--->
<div class="modal fade" id="ModalEdit_Tienda<?php echo $id_almacen; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Editar Almacen
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="actualizar_tienda.php">
        <input type="hidden" name="id" value="<?php echo $id_almacen; ?>">

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="nombre" class="col-form-label">Nombre Almacen:</label>
            <input type="text" name="nombre_almacen" class="form-control" value="<?php echo $row['Nombre_Almacen'] ?>" required>
          </div>
          <div class="form-group">
            <label for="direccion" class="col-form-label">Direccion:</label>
            <input type="text" name="direccion" class="form-control" value="<?php echo $row['Direccion'] ?>" required>
          </div>
          <div class="form-group">
            <label for="link_imagen" class="col-form-label">Link Imagen:</label>
            <input type="text" name="link_imagen" class="form-control" value="<?php echo $row['Link_Imagen'] ?>">
          </div>
          <div class="form-group">
            <label for="detalles" class="col-form-label">Detalles:</label>
            <input type="text" name="detalles" class="form-control" value="<?php echo $row['Detalles'] ?>" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>

    </div>
  </div>
</div>