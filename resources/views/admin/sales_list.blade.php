
@extends('admin/layout')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
<!-- TABLA DE VENTAS -->
<div class="justify-content-center">
<div class="card col-12 px-0">
    <div class="card-body">
        <div id="responsive_table" class="container-fluid">
            <table class="table table-hover text-center" id="sales_table">
                <thead class="bg-dark" style="color:white; font-weight:bold;">
                    <tr>
                        <th style="padding-left:30px;">Id</th>
                        <th style="padding-left:30px;">Fecha</th>
                        <th style="padding-left:30px;">Total</th>
                        <th style="padding-left:30px;">Estado</th>
                        <th style="padding-left:30px;">Cliente</th>
                        <th style="padding-left:30px;">Provincia</th>
                        <th style="padding-left:30px;">Localidad</th>
                        <th style="padding-left:30px;">Dirección</th>
                        <td style="padding-left:15px;">Ver más</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                    <tr>
                        <td class="negrita">{{$sale->id}}</td>
                        <td class="negrita">{{$sale->date}}</td>
                        <td class="negrita">${{number_format($sale->total, 2, ',', '.')}}</td>
                        <td class="negrita">{{$sale->state}}</td>
                        <td class="negrita">{{$sale->customer->name}}</td>
                        <td class="negrita">{{$sale->city->province->name}}</td>
                        <td class="negrita">{{$sale->city->name}}</td>
                        <td class="negrita">{{$sale->address}}</td>
                        <td>
                            <span class="btn btn-danger" class="btn btn-warning border-radius" onclick="sale_details('{{$sale->id}}', '{{$sale->state}}')" data-toggle="modal" data-target="#edit_sale_modal">
                                    <i class="fas fa-info"></i>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger">
                        <th>Id</th>
                        <th>Fecha</th>
                        <th>Total ($)</th>
                        <th>Estado</th>
                        <th>Cliente</th>
                        <th>Provincia</th>
                        <th>Localidad</th>
                        <th>Dirección</th>
                        <td>Ver más</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<!-- MODAL DETALLES DE VENTA -->
<div class="modal fade" id="edit_sale_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Detalles de venta</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-primary">Estado</h2>
                </div>
                <form method="POST" action="{{ action('SaleController@edit_sale')}}" enctype="multipart/form-data" id="editform">
                    {{ csrf_field() }}
                    <input type="hidden" name="eid" id="eid" value="{{old('eid')}}">
                    
                    <div class="row d-flex justify-content-center mt-3">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-danger negrita">
                                <input type="radio" id="estate1" name="state" value="Envío pendiente"> Envío pendiente
                            </label>
                            <label class="btn btn-danger negrita">
                                <input type="radio" id="estate2" name="state" value="Despachado"> Despachado
                            </label>
                            <label class="btn btn-danger negrita">
                                <input type="radio" id="estate3" name="state" value="Entregado"> Entregado
                            </label>
                        </div>
                    </div>
                </form>
                    <div class="row d-flex justify-content-center mt-4">
                        <label class="text-primary negrita mb-0" style="font-size:30px;">Productos</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 pr-5">
                        <table class="table table-responsive" id='products_table'>
                        </table>
                    </div>
                    <div id="total">
                    </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger negrita" id="deletebtn" style="display:none;"><i class="fa fa-times"></i> Eliminar</button>
                <button type="submit" form="editform" class="btn btn-success negrita" id="savebtn" style="display:none;"><i class="fa fa-check"></i> Guardar</button>
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
    
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
</script>

<script type="text/javascript">    
    function sale_details(id, state, sale_lines, products){
        $('#eid').val(id);
        switch (state) {
            case 'Envío pendiente': $('#estate1').prop("checked", true); $('#estate1').click(); break;
            case 'Despachado': $('#estate2').prop("checked", true); $('#estate2').click(); break;
            case 'Entregado': $('#estate3').prop("checked", true); $('#estate3').click(); break;
        }
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
          type:"POST",
          data:"sale_id=" + id,
          url:"/ajax/find_sale_lines",
            success:function(r){
                $('#products_table').children().remove();
                $('#products_table').append(
                `<thead>
                    <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Talle</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>`)
                r.forEach(function(element) {
                    $('#products_table').append(
                    `<tr>
                         <td style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;">
                             <img src="../img/product-img/`.concat(element['photo'],`" style="max-height: 200px;">
                         </td>
                         <td style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;">
                             <h5>`,element['product'],`</h5>
                         </td>
                         <td style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                             <h5>`,element['waist'],`</h5>
                         </td>
                         <td style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                             <h5>$`,element['subtotal']/element['qty'],`</h5>
                         </td>
                         <td class="qty" style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                             <h5>`,element['qty'],`</h5>
                         </td>
                         <td class="subtotal" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                             <h5>$`,element['subtotal'],`</h5>
                         </td>
                     </tr>`));
                });
                $('#total').children().remove();
                $('#total').append(`
                    <div class="row d-flex justify-content-center">
                        <label class="negrita" style="font-size:20px;">Total: $`.concat(r[0]['total'], `</label>
                    </div>`));
            }
        });
        if (state == 'Pago pendiente'){
            $('#deletebtn').show();
            $('#savebtn').hide();
        }else{
            $('#deletebtn').hide();
            $('#savebtn').show();
        }
    }
</script>

<script>
$('#deletebtn').popover({
    title: "<div class='d-flex justify-content-center'><label>Está por eliminar una venta ¿Desea continuar?</label></div>",
    content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_sale'>Confirmar</button></div>",
    html: true,
    placement: "right"}); 
</script>

<!--DATATABLES-->
<script type="text/javascript">
$(document).ready(function () {
    $('#responsive_table').addClass('table-responsive');

    // Setup - add a text input to each footer cell
    $('#sales_table tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" class="tcentrado negrita" style="border-radius: 10px;"/>');
    });

    var table = $('#sales_table').DataTable({
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
            { "orderable": false, "targets": 8 },
            { "searchable": false, "targets": 8 }
          ],
        "autoWidth": false,
        "language": {
            "emptyTable": "Tabla vacía",
            "lengthMenu": "Mostrar _MENU_ ventas por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ ventas",
            "infoEmpty": "No hay ventas para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ ventas)",
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

    var r = $('#sales_table tfoot tr');
    $('#sales_table thead').append(r);
    $('#search_0').css('text-align', 'center');

});
</script>
  
@endsection