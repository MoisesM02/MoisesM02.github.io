<div class="modal fade" id="formularioObservaciones" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Añadir Observación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form id="crearForm">
            <label for="observacion"><strong>Observación</strong></label>    
            <textarea id="observacion" row="50" class="form-control"></textarea>
        </form>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger register-button" data-dismiss="modal">Cerrar</button>
        <button type="button" id ="crearObservacion" class="btn btn-primary register-button">Aceptar</button>
      </div>
      
    </div>
  </div>
</div>
