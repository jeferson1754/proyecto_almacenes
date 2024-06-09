<div class="modal fade" id="ModalEditarProducto<?php echo $mostrar2['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="actualizar_producto.php">
        <input type="hidden" name="id" value="<?php echo $mostrar2['ID']; ?>">

        <div id="contenedor" class="modal-body">
          <div class="form-group" style="margin-top:-15px">
            <label for="nombre_producto" class="col-form-label">Nombre:</label>
            <input type="text" name="nombre_producto" id="producto" list="productos" class="form-control" value="<?php echo $mostrar2['Nombre'] . " - " . $mostrar2['Marca']; ?>" required>
            <datalist id="productos">
              <?php
              $productos = $conexion->query("SELECT CONCAT(productos.Nombre, ' - ', marca.Nombre) AS Nombre FROM productos INNER JOIN marca ON marca.ID = productos.ID_Marca;");

              foreach ($productos as $producto) {
                echo "<option value='" . $producto['Nombre'] . "'></option>";
              }

              ?>
            </datalist>
          </div>

          <div class="form-group" style="margin-top:10px">
            <label for="precio" class="form-label">Valor:</label>
            <div style=" display: flex; align-items: center;">
              <input type="number" class="form-control" min="0" name="precio" value="<?php echo $mostrar2['Valor']; ?>">
              <button type="button" onclick="agregarFormulario('<?php echo $mostrar2['ID']; ?>', this)" class="btn btn-dark" style="margin-left:10px;">
                <img style="width: 40px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFMklEQVR4nO2Za4gWVRjHxzaz1MzUrTY3LLtIGQUFkkLah6g+qGvYahmBBWUuhSVdPnQzstIso5u2EV0ozRaprEiie1hpQQkRJtlaWVRmV/NWtr942P/U2dk5M2fed1YX8Q/Dy7zz3M71+Z/nRNFe7KEAJgFrgW+AeUBdikwdcBfwPbAemBx1JwCjgZ10xDZgAXCUngX6D0fWfkdH3QFALfCtArsaOAZ4BNih//7WY9iub0dLFunW7u5G9ACWKaCX7d35digwC/gd+BO4D6hP6D4v3VeAfXZV0COAy5xnJvCYAvkaGODRG2SP59sA6Roeld2LgEbgLGBo2Y2Yjx82bUZVYXsU8JfHdhtwY1mNsJ3mH+A34B5gjh6bNtcDDSX4aABukN37gWagBfhDHZU6okWdjFXvtFRtrLjv5+T7nDKMWa8bbiklumK+Z8v3zDKMPSlj55USXTHfF8QbQRnGPpKxE0qJrpjvk+X7g2qMDHPmqO0qPUuNMiyGXk5CtY2mfxHlw4CFTjb+CWjK0ZkCfAZsBVYD5wbkJOuctQHxzHKojcUyHdg3T8mS0GYpbQHuAA7K0Zmo/d6FbddjM3TelVxjXkMM4mpLHfufAEMiH4BWCdoCH+wVTF9DczUdH9L7So/8BH1/z6U0gb7GAB9L/xmfUI2G24jdfgWMx8N+iMOxDL+kyPYEPtcIjizSiBi26cj+qsgH4C0JjfMKddaJKXkfvffR+7YU2aZqkyvtHM+wMEvISJthcQHD8fqocUbW0JaQ6wf8qBGvmAzSPiUNZ2YJHaxzhC30voGG43PHAXrvrfedCbnb9f/dVTRisDpuU8jO9ZIcnh9o3Mgk8eFIBy3DVkemXp3zs3VWFQ25MjjTAy9IeE6g8VWSvxk4ELhW7x86Mo/rv6sqbYQBWCQ786IsAOMlaHN5YKZwR3ZsecNFW5wURTPs+7oiu2EagOGayvYM9wlZb25QIFOiArCgldFt+nzqTkvg1TJJJ3Cbk4c6H491oDEsL8OhwxQMK+LkV5TOJAHsr7KTYXry40AlQysSHBmVAOstBWrT7LRK6UwagDNkZ2OH6WrBy+h6XxGhKICpsvlsBp15MIvO+AAcoepMW6d4gTdkdHW1dSblkg3J5OfQmdo8OpNDIFuTneQK9LcDjATWAIdHFcIqH2nbpLO79dZ7Xx+dCWjE8jgJpwn208JEC+q/gloojDxq2Dclk18onUkDcDzwneStKNgrT8F66c1MqpytH9P4GSnfbKrl0pk0AG9LdmnwadXygJSagxT+1xum3e+LtOTn0JlBPjoTQJ3GeIVSlK6Q0uxgpXa9uJY70fN9ZR6d8cEpz4YnVuBW3/TI0DldOisqoDMTAuzPlfzlRRpidxmGCwvovKOCxYhK6UwWgGsU002Zggmlp6U0PuomAKalbel5SlZQRtuo7RKXZlYtugjAsVqvL2oEDVOLErOnUq7T1pRW5s+/xmhN+N6hy6KaSgxaThmnMv9XzuI8rovvI2N8Kd+NhaqMAU7ulYP5pRnt7GOJfFwXdaGTIZpuv8bln5Lt1ymhbimLhWc5s4VnmJayrkbqzOGtIFr1Q9cFpyapBu113sKMoiIAZ8vZOhWUH1YZ070HbEkbMV1+vubIbVe2fwC4RLUCw0m7oiE9lPyS2Kz/f0grNKtwYI2PD3DvO+cTF0u6vBFOUMZaZwB3AhcDJzrUvN45CW7UsbRBF5uG1+P5r1rwKUp2tuU27Y57GC+0Xp5I6e3mbhVowcpgq55g3rYX0R6AfwHl17UdvzLIFwAAAABJRU5ErkJggg==">
              </button>
            </div>
          </div>

          <div id="Agregar_Promocion_<?php echo $mostrar2['ID']; ?>" style="display: none;">
            <div class="form-group">
              <label for="detalle_promocion" class="col-form-label">Detalle Promocion:</label>
              <input type="text" name="detalle_promocion" class="form-control" value="">
            </div>


            <div class="form-group" style="margin-top:10px">
              <label for="fecha_termino_<?php echo $mostrar2['ID']; ?>" class="form-label">Fecha Termino:</label>
              <div style="display: flex; align-items: center;">
                <input type="date" class="form-control" name="fecha_termino" value="<?php echo $fecha_hora_actual; ?>" id="fecha_termino_<?php echo $mostrar2['ID']; ?>">

                <div class="todo">
                  <label class="container" style="width:80px">
                    <span class="text">Indefinido</span>
                    <input type="checkbox" name="Indefinido" value="SI" unchecked id="indefinido_checkbox_<?php echo $mostrar2['ID']; ?>">
                    <span class="checkmark"></span>
                  </label>
                </div>

              </div>
            </div>



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
      <script>
        function agregarFormulario(id) {
          // Concatenar el ID dinámico para obtener el ID completo del div
          var divID = 'Agregar_Promocion_' + id;

          // Obtener el div por su ID
          var divPromocion = document.getElementById(divID);

          // Verificar el estado actual del div
          if (divPromocion.style.display === 'none' || divPromocion.style.display === '') {
            // Si está oculto, mostrar el div
            divPromocion.style.display = 'block';
          } else {
            // Si está visible, ocultar el div
            divPromocion.style.display = 'none';
          }
        }

        // Función para manejar el cambio de estado del checkbox
        function toggleInput(id) {
          // Obtener referencias a los elementos del DOM específicos para el ID dado
          var checkbox = document.getElementById('indefinido_checkbox_' + id);
          var inputFecha = document.getElementById('fecha_termino_' + id);

          // Si el checkbox está marcado, deshabilitar el input de fecha; de lo contrario, habilitarlo
          inputFecha.disabled = checkbox.checked;
        }

        // Función para agregar event listener a los checkboxes
        function agregarListeners() {
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 'extracion_id.php', true);
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
              if (xhr.status === 200) {
                var tusDatos = JSON.parse(xhr.responseText);
                tusDatos.forEach(function(id) {
                  var checkbox = document.getElementById('indefinido_checkbox_' + id);
                  checkbox.addEventListener('change', function() {
                    toggleInput(id);
                  });
                  toggleInput(id); // Asegurar que el estado inicial coincida con el checkbox
                });
              } else {
                console.error('Error al obtener los IDs de los registros');
              }
            }
          };
          xhr.send();
        }

        // Llamar a la función para agregar event listeners cuando el documento esté listo
        document.addEventListener('DOMContentLoaded', agregarListeners);
      </script>
    </div>
  </div>
</div>

<!---fin ventana Update --->