$(function(){
  let startDate;
    moment.locale('es')
    $("#FotoPreview").hide();
    function readURL(input) {
        Swal.fire({
            title:"Cargando imagen",
            text: "Por favor espere...",
            html: `<div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>`
        });
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          
          reader.onload = function(e) {
            $('#FotoPreview').attr('src', e.target.result);
          }
          $(".img").css("background-image", "none")
          reader.readAsDataURL(input.files[0]); // convert to base64 string
          $("#FotoPreview").show();
          Swal.close();
        }
      }
      $("#Foto").on("change",function() {
        readURL(this);
      });

      $('#reportrange span').daterangepicker(
        {

           singleDatePicker: true,
           showDropdowns:true,
           minDate: moment().subtract(40, 'years').startOf('year').format('DD MM YYYY'),
           maxDate: moment().subtract(18, 'years').format('DD MM YYYY'),
           
           opens: 'center',
           buttonClasses: ['btn btn-default'],
           applyClass: 'btn-small btn-primary',
           cancelClass: 'btn-small',
           dateFormat: 'DD-MMMM-YYYY:',
           
           locale: {
               applyLabel: 'Confirmar',
               fromLabel: 'Desde',
               toLabel: 'Hasta',
               customRangeLabel: 'Rango específico',
               daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
               monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
               firstDay: 1
           }
        },
        function(start, end) {
         $('#reportrange span').html(start.format('LL') );
        startDate = start;
        let now = moment()
        let edad = now.diff(start, 'years')
        $('#Edad').val(edad);  
        }
     );
     //Set the initial state of the picker label
     $('#reportrange span').html(moment().format('LL'));
 
        var cleave = new Cleave('#DUI',{
          numericOnly:true,
          blocks:[8,1],
          delimiter: '-'
        })

      $('#enviar').on('click', function(e){
        e.preventDefault();

        Swal.fire({
          title:"Crear perfil",
          text: '¿Está seguro de crear este perfil?',
          icon: 'warning',
          showConfirmButton: true,
          showDenyButton: true,
          allowEscapeKey: false,
          allowEnterKey: false,
          allowOutsideClick: false
        }).then((result) =>{
          Swal.fire({
            title: 'Por favor espere',
            html: `<div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>`
          })
          if(result.isConfirmed){
            agregarPerfil();
          }else{
            Swal.fire('Cancelado', 'Se ha cancelado la creación del perfil', 'error');
          }
        })
      })


      function agregarPerfil(){



    //Peticion ajax para subir la imagen
        let nombres = $('#Nombre').val();
        let apellidos = $('#Apellido').val();
        let dui = $('#DUI').val();
        let fechaNacimiento = startDate.format('YYYY-MM-DD');
        let direccion = $('#Direccion').val();
        let edad = $('#Edad').val();
        let imageData = $('#Foto').prop('files')[0];
        var formData = new FormData();
        formData.append('image',imageData);
        formData.append('nombres', nombres);
        formData.append('apellidos', apellidos);
        formData.append('dui', dui);
        formData.append('fechaNacimiento', fechaNacimiento);
        formData.append('direccion', direccion);
        formData.append('edad', edad);
        $.ajax({
            url         :   'Backend/agregarPerfil.php',
            dataType    :   'text',
            cache       :   false,
            contentType :   false,
            processData :   false,
            data        :   formData,
            type        :   'post',
            success     :   function(response){
            try{
              Swal.close();
              let res = JSON.parse(response);
              Swal.fire(res.msg,'', 'success');
              $('#form').trigger('reset');
            }catch(error){
              Swal.close();
              Swal.fire(response, '', 'error');
            }
          
           
            }
        })
      }

    
})