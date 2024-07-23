<!--ventana para Update--->
<div class="modal fade" id="ModalEdit_Prom<?php echo $mostrar2['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
      <style>
        .div1 {
          text-align: start;
        }
      </style>


      <form method="POST" action="update_promo.php">
        <input type="hidden" name="id" value="<?php echo $mostrar2['ID']; ?>">
        <input type="hidden" name="id_registro" value="<?php echo $mostrar2['ID_Registro']; ?>">


        <div class="modal-body div1" id="cont_modal">

          <div class="form-group">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $mostrar2['Nombre']; ?>" required>
          </div>
          <div class="form-group">
            <label for="marca" class="col-form-label">Marca:</label>
            <input type="text" name="marca" class="form-control" value="<?php echo $mostrar2['marca'] ?>">
          </div>
          <div class="form-group">
            <label for="valor" class="col-form-label">Valor:</label>
            <input type="number" name="valor" class="form-control" value="<?php echo $mostrar2['Valor'] ?>" required>
          </div>
          <div class="form-group">
            <label for="promo" class="col-form-label">Detalle:</label>
            <input type="text" name="promo" class="form-control" value="<?php echo $mostrar2['Promocion']; ?>" required>
          </div>
          <div class="form-group">
            <label for="indef" class="col-form-label">Fecha de Termino:</label>
            <?php
            if ($mostrar2['Indefinido'] != "SI") {
              echo "<input type='date' name='indef' class='form-control' value=" . $mostrar2['Fecha_Termino'] . " required>";
            } else {
              echo "<input type='text' name='indef' class='form-control' value='Indefinido' disabled>";
            } ?>
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