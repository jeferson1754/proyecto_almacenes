<!--ventana para Update--->
<div class="modal fade" id="ModalEdit<?php echo $mostrar2['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">
          Editar Producto
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <style>
        .div1 {
          text-align: start;
        }
      </style>


      <form method="POST" action="update_producto.php">
        <input type="hidden" name="id" value="<?php echo $mostrar2['ID']; ?>">
        <input type="hidden" name="id_almacen" value="<?php echo $id_almacen ?>">

        <div class="modal-body div1" id="cont_modal">

          <div class="form-group">
            <label for="nombre_producto" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre_producto" class="form-control" value="<?php echo $mostrar2['Nombre']; ?>" required>
          </div>
          <div class="form-group">
            <label for="marca" class="col-form-label">Marca:</label>
            <input type="text" name="marca" class="form-control" value="<?php echo $mostrar2['marca'] ?>" required>
          </div>
          <div class="form-group">
            <label for="precio" class="col-form-label">Valor:</label>
            <input type="text" name="precio" class="form-control" value="<?php echo $mostrar2['Valor'] ?>">
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