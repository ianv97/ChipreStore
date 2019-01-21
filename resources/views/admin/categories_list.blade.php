
@extends('admin/layout')

@section('head')
<link rel="stylesheet" href="../css/datatable.css">
@endsection

@section('body')
<!-- TABLA DE CATEGORÍAS -->
<div class="justify-content-center">
<div class="card col-12 px-0">
    <div class="card-body">
        <div class="row px-3">
            <div class="d-inline-flex ml-auto mb-2">
            <button type="button" class="btn btn-danger px-2" data-toggle="modal" data-target="#new_category_modal" style="font-weight:bold;">
                <span class="fas fa-plus-square"></span> Nueva categoría
            </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover text-center" id="categories_table">
                <thead class="bg-dark" style="color:white; font-weight:bold;">
                    <tr>
                        <th style="padding-left:30px;">Id</th>
                        <th style="padding-left:30px;">Categoría</th>
                        <th style="padding-left:30px;">Estado</th>
                        <td style="padding-left:15px;">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $categories = \App\Category::all() ?>
                    @foreach ($categories as $category)
                    <tr>
                        <td class="negrita">{{$category->id}}</td>
                        <td class="negrita">{{$category->name}}</td>
                        <td class="negrita">{{$category->state}}</td>
                        <td>
                            <span class="btn btn-danger" class="btn btn-warning border-radius" onclick="editcategory('{{$category->id}}', '{{$category->name}}')" data-toggle="modal" data-target="#edit_category_modal">
                                    <i class="fas fa-user-edit"></i>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger">
                        <th>Id</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <td>Editar</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<!-- MODAL NUEVA CATEGORÍA -->
<div class="modal fade" id="new_category_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Nueva categoría</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-white">Registrar Categoría</h2>
                </div>
                <form method="POST" action="{{ action('Category@new_category')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="input-category largewidth">
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Nombre *">
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


<!-- MODAL EDITAR CATEGORÍA -->
<div class="modal fade" id="edit_category_modal" tabindex="-1" role="dialog">
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
                    <h2 class="negrita nosub text-white">Editar Datos</h2>
                </div>
                <form method="POST" action="{{ action('Category@edit_category')}}" enctype="multipart/form-data" id="editform">
                    {{ csrf_field() }}
                    <input type="hidden" name="eid" id="eid" value="{{old('eid')}}">
                    <div class="row d-flex justify-content-center my-4">
                        <div class="input-category largewidth">
                            <input type="text" class="form-control" name="ename" id="ename" value="{{old('ename')}}" placeholder="Nombre *">
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
<script type="text/javascript">
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if ($errors->get('name'))
        $('#new_category_modal').modal('show');
    @endif
    
    @if ($errors->get('ename'))
        $('#edit_category_modal').modal('show');
    @endif
    
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
</script>

<script type="text/javascript">    
    function edit_category(id, name){
        $('#eid').val(id);
        $('#ename').val(name);
    }
</script>

<script>
$('#deletebtn').popover({
    title: "<div class='d-flex justify-content-center'><label>Está por eliminar una categoría ¿Desea continuar?</label></div>",
    content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_category'>Confirmar</button></div>",
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
    $('#categories_table tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" class="tcentrado negrita" style="border-radius: 10px;"/>');
    });

    var table = $('#categories_table').DataTable({
        dom: '<"top"<"row col-12"<"mt-2"B><"d-inline-flex ml-auto mt-2"l>><"row col-12 mt-2"f>>rt<"bottom"<"row"<"col-8"i><"col-4"p>>>',
        buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                text: 'Columnas',
                className: 'bg-primary negrita'
            }
        ],
        "order": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 2 },
            { "searchable": false, "targets": 2 }
          ],
        "autoWidth": false,
        "language": {
            "emptyTable": "Tabla vacía",
            "lengthMenu": "Mostrar _MENU_ categorías por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ categorías",
            "infoEmpty": "No hay categorías para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ categorías)",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Búsqueda:",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
    table.columns( [0] ).visible( false );

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

    var r = $('#categories_table tfoot tr');
    $('#categories_table thead').append(r);
    $('#search_0').css('text-align', 'center');

});
</script>
@endsection