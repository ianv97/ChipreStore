
@extends('admin/layout')

@section('head')
<!--<link rel="stylesheet" href="../css/datatable.css">-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">
@endsection

@section('body')
<!-- TABLA DE OFERTAS -->
<div class="justify-content-center">
<div class="card col-12 px-0">
    <div class="card-body">
        <div class="row px-3">
            <div class="d-inline-flex ml-auto mb-2">
            <button type="button" class="btn btn-danger px-2" data-toggle="modal" data-target="#new_offer_modal" style="font-weight:bold;">
                <span class="fas fa-plus-square"></span> Nueva oferta
            </button>
            </div>
        </div>
        <div id="responsive_table" class="container-fluid">
            <table class="table table-hover text-center" id="waists_table">
                <thead class="bg-dark" style="color:white; font-weight:bold;">
                    <tr>
                        <th style="padding-left:30px;">Id</th>
                        <th style="padding-left:30px;">Oferta</th>
                        <th style="padding-left:30px;">% Descuento</th>
                        <th style="padding-left:30px;">Estado</th>
                        <td style="padding-left:15px;">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offers as $offer)
                    <tr>
                        <td class="negrita">{{$offer->id}}</td>
                        <td class="negrita">{{$offer->name}}</td>
                        <td class="negrita">{{$offer->discount_percentage}}</td>
                        <td class="negrita">@if ($offer->state == 1) Habilitada @else Deshabilitada @endif</td>
                        <td>
                            <span class="btn btn-danger" class="btn btn-warning border-radius" onclick="edit_offer('{{$offer->id}}',
                                  '{{$offer->name}}', '{{$offer->category}}',{{json_encode($offer->products_offers)}},
                                  '{{$offer->min_quantity}}', '{{$offer->discount_percentage}}', '{{$offer->start_date}}',
                                  '{{$offer->end_date}}')" data-toggle="modal" data-target="#edit_offer_modal">
                                    <i class="fas fa-user-edit"></i>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger">
                        <th>Id</th>
                        <th>Oferta</th>
                        <th>% Descuento</th>
                        <th>Estado</th>
                        <td>Editar</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- MODAL NUEVA OFERTA -->
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
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Nombre *" required>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-5">
                        <div class="col-10 col-sm-8 col-md-7 col-lg-5 col-xl-4">
                            <select class="categories" name="category" value="{{old('category')}}">
                                <option></option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="products">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Productos</label>
                        </div>
                        <div class="row d-flex justify-content-center mt-2 pr-5">
                            <button type="button" class="btn btn-danger mr-1 mr-lg-3" onclick="remove_product(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="ml-1 col-10 col-sm-8 col-md-7 col-lg-5 col-xl-4">
                                <select class="products" name="products[]">
                                    <option></option>
                                    @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="append_product()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>

                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-6 col-sm-5 col-lg-3">Cantidad mínima</label>
                        <label class="text-white negrita col-5 col-sm-4 col-md-3 col-lg-2 mr-5">Descuento *</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-6 col-sm-5 col-lg-3">
                            <input type="number" class="form-control" id="min_quantity" name="min_quantity" min="1" step="1" placeholder="1" value="{{old('min_quantity')}}">
                            <span class="input-group-text">Productos</span>
                        </div>
                        <div class="input-group col-5 col-sm-4 col-md-3 col-lg-2 mr-5">
                            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" min="1" max="99" step="1" placeholder="0" value="{{old('discount_percentage')}}" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-6 col-sm-5 col-lg-3">Fecha de inicio</label>
                        <label class="text-white negrita col-6 col-sm-5 col-lg-3">Fecha de fin</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-6 col-sm-5 col-lg-3">
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{old('start_date')}}">
                        </div>
                        <div class="input-group col-6 col-sm-5 col-lg-3">
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{old('end_date')}}">
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-4">
                        <label class="text-white negrita mt-1" id="state_label" for="state">@if (!empty(old('estate'))) Habilitada @else Deshabilitada @endif</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="state" name="state" onchange="toggle_state()" @if (!empty(old('state'))) checked="true" @endif>
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



<!-- MODAL EDITAR OFERTA -->
<div class="modal fade" id="edit_offer_modal" tabindex="-1" role="dialog">
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
                    <h2 class="negrita nosub text-white">Editar Oferta</h2>
                </div>
                <form method="POST" action="{{action('OfferController@edit_offer')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="text"  id="eid" name="eid" hidden>
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="normal_width">
                            <input type="text" class="form-control" id="ename" name="ename" value="{{old('ename')}}" placeholder="Nombre *" required>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-5">
                        <div class="col-10 col-sm-8 col-md-7 col-lg-5 col-xl-4">
                            <select class="ecategories" id="ecategory" name="ecategory" value="{{old('ecategory')}}">
                                <option></option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="eproducts">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Productos</label>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="eappend_product()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>

                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-6 col-sm-5 col-lg-3">Cantidad mínima</label>
                        <label class="text-white negrita col-5 col-sm-4 col-md-3 col-lg-2 mr-5">Descuento *</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-6 col-sm-5 col-lg-3">
                            <input type="number" class="form-control" id="emin_quantity" name="emin_quantity" min="1" step="1" placeholder="1" value="{{old('emin_quantity')}}">
                            <span class="input-group-text">Productos</span>
                        </div>
                        <div class="input-group col-5 col-sm-4 col-md-3 col-lg-2 mr-5">
                            <input type="number" class="form-control" id="ediscount_percentage" name="ediscount_percentage" min="1" max="99" step="1" placeholder="0" value="{{old('ediscount_percentage')}}" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-6 col-sm-5 col-lg-3">Fecha de inicio</label>
                        <label class="text-white negrita col-6 col-sm-5 col-lg-3">Fecha de fin</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-6 col-sm-5 col-lg-3">
                            <input type="date" class="form-control" id="estart_date" name="estart_date" value="{{old('estart_date')}}">
                        </div>
                        <div class="input-group col-6 col-sm-5 col-lg-3">
                            <input type="date" class="form-control" id="eend_date" name="eend_date" value="{{old('eend_date')}}">
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center mt-4">
                        <label class="text-white negrita mt-1" id="estate_label" for="estate">@if (!empty(old('estate'))) Habilitada @else Deshabilitada @endif</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="estate" name="estate" onchange="toggle_estate()" @if (!empty(old('estate'))) checked="true" @endif>
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
</div>
@endsection


@section('footer')
<script type="text/javascript">
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if ($errors->get('name') or $errors->get('category') or $errors->get('products') or $errors->get('min_quantity') or $errors->get('discount_percentage') or $errors->get('start_date') or $errors->get('end_date'))
        $('#new_offer_modal').modal('show');
    @endif

    @if ($errors->get('ename') or $errors->get('ecategory') or $errors->get('eproducts') or $errors->get('emin_quantity') or $errors->get('ediscount_percentage') or $errors->get('estart_date') or $errors->get('eend_date'))
        $('#edit_offer_modal').modal('show');
    @endif

    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
    
    $(document).ready(function() {
        $('.categories').select2({
            placeholder: "Categoría",
            allowClear: true,
            dropdownParent: $("#new_offer_modal"),
            width: '100%'
        });
        $('.products').select2({
            placeholder: "Producto",
            allowClear: true,
            dropdownParent: $("#new_offer_modal"),
            width: '100%'
        });
        $('.ecategories').select2({
            placeholder: "Categoría",
            allowClear: true,
            dropdownParent: $("#edit_offer_modal"),
            width: '100%'
        });
    });
</script>

<script type="text/javascript">
    function edit_offer(id, name, category, products_offers, min_quantity, discount_percentage, start_date, end_date, state){
        $('#eproducts .erow').each(function(id, element){
            $(element).remove();
        });
        $('#eid').val(id);
        $('#ename').val(name);
        $('#ecategory').val(category);
        $('#emin_quantity').val(min_quantity);
        $('#ediscount_percentage').val(discount_percentage);
        $('#estart_date').val(start_date);
        $('#eend_date').val(end_date);
        var i;
        for (i = 0; i < products_offers.length; ++i) {
            eappend_product();
            $('#eproducts .eproducts').last().val(products_offers[i]['product_id'])
        }
        if (state == 0){
            $('#estate').prop("checked", false);
        }else{
            $('#estate').prop("checked", true);
        };
        toggle_estate();
    }
    
    function toggle_state(){
        if ($('#state').prop('checked')){
            $('#state_label').text('Habilitada');
        }else{
            $('#state_label').text('Deshabilitada');
        }
    }
    
    function toggle_estate(){
        if ($('#estate').prop('checked')){
            $('#estate_label').text('Habilitada');
        }else{
            $('#estate_label').text('Deshabilitada');
        }
    }
    
    function append_product(){
        $('#products').append(
            `<div class="row d-flex justify-content-center mt-2 pr-5">
                <button type="button" class="btn btn-danger mr-1 mr-lg-3" onclick="remove_product(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <div class="ml-1 col-10 col-sm-8 col-md-7 col-lg-5 col-xl-4">
                    <select class="products" name="products[]">
                        <option></option>
                        @foreach ($products as $product)
                        <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>`);
        $('.products').select2({
            placeholder: "Producto",
            allowClear: true,
            dropdownParent: $("#new_offer_modal"),
            width: '100%'
        });
    }
    
    function remove_product(product){
        $(product).parent().remove();
    }
    
    function eappend_product(){
        $('#eproducts').append(
            `<div class="row d-flex justify-content-center mt-2 pr-5 erow">
                <button type="button" class="btn btn-danger mr-1 mr-lg-3" onclick="remove_product(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <div class="ml-1 col-10 col-sm-8 col-md-7 col-lg-5 col-xl-4">
                    <select class="eproducts" name="eproducts[]">
                        <option></option>
                        @foreach ($products as $product)
                        <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>`);
        $('.eproducts').select2({
            placeholder: "Producto",
            allowClear: true,
            dropdownParent: $("#edit_offer_modal"),
            width: '100%'
        });
    }
    
    $('#deletebtn').popover({
        title: "<div class='d-flex justify-content-center'><label>Está por eliminar una oferta ¿Desea continuar?</label></div>",
        content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_offer'>Confirmar</button></div>",
        html: true,
        placement: "right"});
</script>


<!--DATATABLES-->
<script type="text/javascript">
$(document).ready(function () {
    $('#responsive_table').addClass('table-responsive');

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
            "lengthMenu": "Mostrar _MENU_ ofertas por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ ofertas",
            "infoEmpty": "No hay ofertas para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ ofertas)",
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
