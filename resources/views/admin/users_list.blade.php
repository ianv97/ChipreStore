
@extends('admin/layout')

@section('head')
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/datatable.css">
@endsection

@section('body')
<!-- TABLA DE USUARIOS -->
<div class="justify-content-center">
<div class="card col-12 px-0">
    <div class="card-body">
        <div class="row px-3">
            <div class="d-inline-flex ml-auto mb-2">
            <button type="button" class="btn btn-danger px-2" data-toggle="modal" data-target="#newusermodal" style="font-weight:bold;">
                <span class="fas fa-plus-square"></span> Nuevo usuario
            </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover text-center" id="userstable">
                <thead class="bg-dark" style="color:white; font-weight:bold;">
                    <tr>
                        <th style="padding-left:30px;">Id</th>
                        <th style="padding-left:30px;">Apellido y Nombre</th>
                        <th style="padding-left:30px;">Email</th>
                        <th style="padding-left:30px;">Rol</th>
                        <th style="padding-left:30px;">DNI</th>
                        <th style="padding-left:30px;">Teléfono</th>
                        <th style="padding-left:30px;">Dirección</th>
                        <th style="padding-left:30px;">Salario</th>
                        <th style="padding-left:30px;">Comisión</th>
                        <td style="padding-left:15px;">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $users = \App\User::all() ?>
                    @foreach ($users as $user)
                    <tr>
                        <td class="negrita">{{ $user->id }}</td>
                        <td class="negrita">{{ $user->name }}</td>
                        <td class="negrita">{{ $user->email }}</td>
                        <td class="negrita">{{ $user->role }}</td>
                        <td class="negrita">{{ $user->dni }}</td>
                        <td class="negrita">{{ $user->phone }}</td>
                        <td class="negrita">{{ $user->address }}</td>
                        <td class="negrita">{{ $user->salary }}</td>
                        <td class="negrita">{{ $user->comission }}</td>
                        <td>
                            <span class="btn btn-danger" onclick="edituser('{{$user->id}}', '{{$user->access}}', '{{$user->email}}', '{{$user->name}}', '{{$user->lastname}}')" data-toggle="modal" data-target="#editusermodal">
                                    <i class="fas fa-user-edit"></i>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger">
                        <th>Id</th>
                        <th>Apellido y Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Salario</th>
                        <th>Comisión</th>
                        <td>Editar</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</div>


<!-- MODAL NUEVO USUARIO -->
<div class="modal fade" id="newusermodal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Nuevo usuario</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-white">Registrar Usuario</h2>
                </div>
                <form method="POST" action="{{ action('User@new_user')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <div class="row d-flex justify-content-center mt-3">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-danger negrita">
                                <input type="radio" name="role" value="Administrador"> Administrador
                            </label>
                            <label class="btn btn-danger negrita">
                                <input type="radio" name="role" value="Stock"> Stock
                            </label>
                            <label class="btn btn-danger negrita">
                                <input type="radio" name="role" value="Consultas"> Consultas
                            </label>
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="input-group largewidth">
                            <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="E-mail *" aria-label="E-mail">
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="input-group normalwidth">
                            <input type="password" class="form-control" name="password1" placeholder="Contraseña *" aria-label="Contraseña">
                        </div>
                        <div class="input-group ml-5 normalwidth">
                            <input type="password" class="form-control" name="password2" placeholder="Repetir Contraseña *" aria-label="Repetir Contraseña">
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="input-group normalwidth">
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Nombre *" aria-label="Nombre">
                        </div>
                        <div class="input-group ml-5 normalwidth">
                            <input type="text" class="form-control" name="lastname" value="{{old('lastname')}}" placeholder="Apellido *" aria-label="Apellido">
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-4 negrita">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODAL EDITAR USUARIO -->
<div class="modal fade" id="editusermodal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Edición de datos</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-white">Editar Usuario</h2>
                </div>
                <form method="POST" action="{{ action('User@edit_user')}}" enctype="multipart/form-data" id="editform">
                    {{ csrf_field() }}
                    <input type="hidden" name="eid" id="eid" value="{{old('eid')}}">
                    <div class="row d-flex justify-content-center mt-3">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-danger negrita" id="eaccess1btn">
                                <input type="radio" name="erole" id="eaccess1" value="Administrador"> Administrador
                            </label>
                            <label class="btn btn-danger negrita" id="eaccess2btn">
                                <input type="radio" name="erole" id="eaccess2" value="Stock"> Stock
                            </label>
                            <label class="btn btn-danger negrita" id="eaccess3btn">
                                <input type="radio" name="erole" id="eaccess3" value="Consultas"> Consultas
                            </label>
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="input-group largewidth">
                            <input type="email" class="form-control" name="eemail" id="eemail" value="{{old('eemail')}}" placeholder="E-mail" aria-label="E-mail">
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center my-4">
                        <div class="input-group normalwidth">
                            <input type="text" class="form-control" name="ename" id="ename" value="{{old('ename')}}" placeholder="Nombre" aria-label="Nombre">
                        </div>
                        <div class="input-group ml-5 normalwidth">
                            <input type="text" class="form-control" name="elastname" id="elastname" value="{{old('elastname')}}" placeholder="Apellido" aria-label="Apellido">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <div style="margin-right:10vw;">
                    <button type="button" class="btn btn-danger negrita" id="deletebtn"><i class="fa fa-times"></i> Eliminar</button>
                </div>
                <button type="submit" form="editform" class="btn btn-success negrita"><i class="fa fa-check"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('footer')
<script src="../js/bootstrap.min.js"></script>
<script src="../js/popper.min.js"></script>
<script type="text/javascript">
    @if ($errors->get('access') or $errors->get('email') or $errors->get('password1') or $errors->get('password2') or $errors->get('name') or $errors->get('lastname'))
        $('#newusermodal').modal('show');
    @endif
    
    @if ($errors->get('eaccess') or $errors->get('eemail') or $errors->get('ename') or $errors->get('elastname'))
        switch("{{old('eaccess')}}"){
            case 'Administrador': $('#eaccess1').prop('checked', true); $('#eaccess1btn').click(); break;
            case 'Stock': $('#eaccess2').prop('checked', true); $('#eaccess2btn').click(); break;
            case 'Consultas': $('#eaccess3').prop('checked', true); $('#eaccess3btn').click(); break;
        }
        $('#editusermodal').modal('show');
    @endif
    
    @if (session('success'))
        toastr.success("{{ session('success') }}")
    @endif
    
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
</script>

<script type="text/javascript">    
    function edituser(id, access, email, name, lastname){
        $('#eid').val(id);
        switch(access){
            case 'Administrador': $('#eaccess1').prop('checked', true); $('#eaccess1btn').click(); break;
            case 'Stock': $('#eaccess2').prop('checked', true); $('#eaccess2btn').click(); break;
            case 'Consultas': $('#eaccess3').prop('checked', true); $('#eaccess3btn').click(); break;
        }
        $('#eemail').val(email);
        $('#ename').val(name);
        $('#elastname').val(lastname);
    }
</script>

<script>
$('#deletebtn').popover({
    title: "<div class='d-flex justify-content-center'><label>Está por eliminar un usuario ¿Desea continuar?</label></div>",
    content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_user'>Confirmar</button></div>",
    html: true,
    placement: "right"}); 
</script>


<script src="../js/datatable.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>

<!--DATATABLES-->
<script type="text/javascript">
$(document).ready(function () {

    // Setup - add a text input to each footer cell
    $('#userstable tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" class="tcentrado negrita" style="border-radius: 10px;"/>');
    });

    var table = $('#userstable').DataTable({
        dom: '<"top"<"row col-12"<"mt-2"B><"d-inline-flex ml-auto mt-2"l>><"row col-12 mt-2"f>>rt<"bottom"<"row"<"col-8"i><"col-4"p>>>',
        buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                text: 'Columnas',
                className: 'bg-primary negrita'
            }
        ],
        responsive: true,
        "order": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 9 },
            { "searchable": false, "targets": 9 }
          ],
        "autoWidth": false,
        "language": {
            "emptyTable": "Tabla vacía",
            "lengthMenu": "Mostrar _MENU_ usuarios por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
            "infoEmpty": "No hay usuarios para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ usuarios)",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Búsqueda:",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
    table.columns( [0, 4, 5, 6, 7, 8] ).visible( false );

    // Apply the search
    table.columns().every(function () {
        var that = this;

        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that
                        .search(this.value)
                        .draw();
            }
        });
    });

    var r = $('#userstable tfoot tr');
    $('#userstable thead').append(r);
    $('#search_0').css('text-align', 'center');

});
</script>
@endsection