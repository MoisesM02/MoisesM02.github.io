<div class="modal fade" id="formulario" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Añadir pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form id="addToRegisterBook">
        <input type="hidden" id="idServicio" value="">
            
            <div class="form-group">
                <label for="montoPagar"><strong> Empleada</strong></label>
               <select id="selectEmpleadas" class="Empleadas form-control"></select>
            </div>
            <div class="form-group">
                <label for="montoPagar"><strong> Monto a pagar</strong></label>
                <input type="text" placeholder="Monto a pagar" value="0" class="form-control porcentaje" id="montoPagar" >
            </div>
            
            <div class="form-group">
                <label for="descuentos"><strong> Descuentos</strong></label>
                <input type="text" class="form-control" id="descuentos" value="0" placeholder="Descuentos">
            </div>
            <div class="form-group">
                <label for="total"><strong> Total</strong></label>
                <input type="text" class="form-control" id="total"  readonly>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="cerrarModal" class="btn btn-danger register-button" data-dismiss="modal">Cerrar</button>
        <button type="button" id ="addPayment" class="btn btn-primary register-button">Añadir pago</button>
      </form>
      </div>
      
    </div>
  </div>
</div>
