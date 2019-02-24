
@extends('admin/layout')

@section('head')
<link rel="stylesheet" href="../css/datatable.css">
@endsection

@section('body')
<!-- TABLA DE OFERTAS -->
<div class="justify-content-center">
<div class="card col-12 px-0">
    <div class="card-body">
        <div class="row px-3">
            <div class="d-inline-flex ml-auto mb-2">
            <button type="button" class="btn btn-danger px-2" data-toggle="modal" data-target="#new_offer_modal" style="font-weight:bold;">
                <span class="fas fa-plus-square"></span> Nuevo talle
            </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover text-center" id="waists_table">
                <thead class="bg-dark" style="color:white; font-weight:bold;">
                    <tr>
                        <th style="padding-left:30px;">Id</th>
                        <th style="padding-left:30px;">Talle</th>
                        <td style="padding-left:15px;">Editar</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($offers as $offer)
                    <tr>
                        <td class="negrita">{{$offer->id}}</td>
                        <td class="negrita">{{$offer->name}}</td>
                        <td>
                            <span class="btn btn-danger" class="btn btn-warning border-radius" onclick="edit_waist('{{$offer->id}}', '{{$offer->name}}')" data-toggle="modal" data-target="#edit_waist_modal">
                                    <i class="fas fa-user-edit"></i>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger">
                        <th>Id</th>
                        <th>Talle</th>
                        <td>Editar</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVO PRODUCTO -->
<div class="modal fade" id="new_offer_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Nueva Oferta</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-white">Registrar Oferta</h2>
                </div>
                <form method="POST" action="{{route('admin.offers.create')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="normal_width">
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Nombre *">
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-4">
                            <textarea class="form-control col-12 col-lg-10 col-xl-8" name="min_quantity" rows='3' placeholder="Descripción">{{old('min_quantity')}}</textarea>
                    </div>


                    <div id="categories">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Categorías</label>
                        </div>
                        @if (!empty(old('categories.0')))
                            <?php $n = 0;
                            while (!empty(old('categories.'.$n))){ ?>
                            <div class="row d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-danger mr-3" onclick="remove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                                <div class="input-group ml-1 mr-5 normal_width">
                                    <select class="form-control" name="categories[]" value="{{old('categories.'.$n)}}">
                                        <option>Seleccionar</option>
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php $n += 1;} ?>
                        @else
                        <div class="row d-flex justify-content-center mt-2">
                            <button type="button" class="btn btn-danger mr-3" onclick="remove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="input-group ml-1 mr-5 normal_width">
                                <select class="form-control" name="categories[]">
                                    <option>Seleccionar</option>
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="append_category()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>


                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-4 col-lg-3">Porcentaje de descuento</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-4 col-lg-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="cost_price" name="discount_percentage" min="0" step="0.01" placeholder="0.00" value="{{old('discount_percentage')}}">
                        </div>
                    </div>


                    <div id="waists">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Talles - Stock *</label>
                        </div>
                        <div class="row d-flex justify-content-center mt-2 pr-5">
                            <button type="button" class="btn btn-danger mr-1 mr-lg-3" onclick="remove_waist(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="input-group ml-1 normal_width">
                                <select class="form-control" name="products[]">
                                    <option>Seleccionar</option>
                                    @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="append_waist()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>



                    <div class="row d-flex justify-content-center mt-2">
                        <label class="text-white negrita mt-1" for="visible">Visible en la tienda</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="visible" name="state" @if (!empty(old('state'))) checked="true" @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-4 negrita">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- MODAL EDITAR TALLE
<div class="modal fade" id="edit_waist_modal" tabindex="-1" role="dialog">
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
                    <h2 class="negrita nosub text-white">Editar Talle</h2>
                </div>
                <form method="POST" action="{{ action('Waist@edit_waist')}}" enctype="multipart/form-data" id="editform">
                    {{ csrf_field() }}
                    <input type="hidden" name="eid" id="eid" value="{{old('eid')}}">

                    <div class="row d-flex justify-content-center my-4">
                        <div class="normal_width">
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
</div> -->

@endsection


@section('footer')
<script type="text/javascript">
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if ($errors->get('name'))
        $('#new_waist_modal').modal('show');
    @endif

    @if ($errors->get('ename'))
        $('#edit_waist_modal').modal('show');
    @endif

    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
</script>

<script type="text/javascript">
    function edit_waist(id, name, state){
        $('#eid').val(id);
        $('#ename').val(name);
        if (state == 0){
            $('#estate').prop("checked", false);
        }else{
            $('#estate').prop("checked", true);
        };
    }
</script>

<script>
$('#deletebtn').popover({
    title: "<div class='d-flex justify-content-center'><label>Está por eliminar un talle ¿Desea continuar?</label></div>",
    content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_waist'>Confirmar</button></div>",
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
    $('#waists_table tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" class="tcentrado negrita" style="border-radius: 10px;"/>');
    });

    var table = $('#waists_table').DataTable({
        dom: '<"top"<"row col-12"<"mt-2"B><"d-inline-flex ml-auto mt-2"l>><"row col-12 mt-2"f>>rt<"bottom"<"row"<"col-8"i><"col-4"p>>>',
        buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                text: 'Columnas',
                className: 'bg-primary negrita'
            }
        ],
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 2 },
            { "searchable": false, "targets": 2 }
          ],
        "autoWidth": false,
        "language": {
            "emptyTable": "Tabla vacía",
            "lengthMenu": "Mostrar _MENU_ talles por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ talles",
            "infoEmpty": "No hay talles para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ talles)",
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

    var r = $('#waists_table tfoot tr');
    $('#waists_table thead').append(r);
    $('#search_0').css('text-align', 'center');

});
</script>

@endsection
