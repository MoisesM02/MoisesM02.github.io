$(document).ready(function(){
cargarUsuarios();
function cargarUsuarios(){
    $.get('Backend/administrarUsuarios.php', function(response){
        console.log(response);
        try{
            let empleados = JSON.parse(response);
            let template = `<center><table id='tablaEmpleados' style='cursor:pointer' class ='table table-striped table-bordered'>
            <thead class='thead-dark'>
            <tr>
            <th>Editar</th>
            <th>Id</th>
            <th>Nombre de usuario</th>
            <th>Tipo de usuario</th>
            <th>Creado en</th>
            <th>Borrar</th>
            </tr>
            </thead>
            <tbody>`;
            empleados.forEach(empleado => {
                template+=`
                    <tr>
                    <td><a class='editar' id='${empleado.id}'> Editar </a></td>
                    <td>${empleado.id}</td>
                    <td><span id="empleado${empleado.id}">${empleado.username}</span></td>
                    <td>${empleado.tipo}</td>
                    <td>${empleado.creadoEn}</td>
                    <td><a class='borrar' data-id='${empleado.id}'> Borrar </a></td>
                    </tr>
                `;
            });
            template += `</tbody></table>`;
            $('#tableContainer').html(template);
            $('#tablaEmpleados').DataTable({
                scrollY: 400,
                language: {
                    search: "Buscar:",
                    paginate:{
                        first: "Primera",
                        last: "Última",
                        previous: "Anterior",
                        next: "Siguiente"
                    },
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    lengthMenu: "Mostrar _MENU_ entradas"
                }
            });
        }catch(error){
            Swal.fire(response, "", "error");
        }

    });
}

//Abrir modal para editar
$(document).on("click", ".editar", function(e){
    e.preventDefault();
    let id = $(this).attr('id');
    $('#userID').val(id);
    let username = $(`#empleado${id}`).text()
    $('#Username').val(username);

    $('#editarUsuarios').modal('show');

});
$('#enviarDatos').on("click", function(e){
    e.preventDefault();
    let username = $('#Username').val();
    let pwd1 = $('#Password').val();
    let pwd2 = $('#Password2').val();
    let id = $('#userID').val();
    let tipo = $("#UserType").val();
    const data = {
        username,
        pwd1,
        pwd2,
        id,
        tipo
    };
    Swal.fire({
        title: "Confirmar",
        text: "¿Está seguro que desea editarlo?",
        icon: "warning",
        showConfirmButton: true,
        showDenyButton: true,
        allowEscapeKey: false,
        allowEnterKey: false,
        allowOutsideClick: false
    }).then((result) =>{
        if(result.isConfirmed){
            $('#editarUsuario').trigger('reset');
            $.post("Backend/editarUsuario.php", data, function(response){
                try {
                    let res = JSON.parse(response);
                    Swal.fire(res.msg, '', "success");
                    
                } catch (error) {
                    Swal.fire(response, "", "error")
                }
            });
        }else{
            Swal.fire("Cancelado", "", "error");
        }
       
    })
    
})

})