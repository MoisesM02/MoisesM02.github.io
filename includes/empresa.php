<div class="modal fade" id="empresaEditar" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Editar imagen de empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form id="actualizarEmpresa">
        
            <div class="form-group">
                <label for="nombreEmpresa" >Nombre de la empresa<strong>*</strong></label>
                <input type="text" class="form-control" id="nombreEmpresa">
            </div>
            <div class="form-group">
                <label for="logo">Logo<strong>*</strong></label>
            </div>
            <div class="form-group">
                <input type="file"  id="logo" class="form-control">
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger register-button" data-dismiss="modal">Cerrar</button>
        <button type="button" id ="btnActualizar" class="btn btn-primary register-button">Aceptar</button>
        
      </form>
      </div>
      
    </div>
  </div>
</div>