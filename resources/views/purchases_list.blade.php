
@extends('layout')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/r-2.2.2/datatables.min.css"/>
    <link rel="stylesheet" href="../css/toastr.min.css">
@endsection

@section('body')
<!-- TABLA DE COMPRAS -->
<div id="responsive_table" class="container-fluid" style="width: calc(100% - 220px);">
    <table class="table table-hover text-center" id="purchases_table">
        <thead style="background-color: #252525; color:white; font-weight:bold;">
            <tr>
                <th style="padding-left:30px;">Fecha</th>
                <th style="padding-left:30px;">Total</th>
                <th style="padding-left:30px;">Estado</th>
                <th style="padding-left:30px;">Provincia</th>
                <th style="padding-left:30px;">Localidad</th>
                <th style="padding-left:30px;">Dirección</th>
                <td style="padding-left:15px;">Ver más</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
            <tr>
                <td class="negrita">{{$purchase->date}}</td>
                <td class="negrita">${{number_format($purchase->total, 2, ',', '.')}}</td>
                <td class="negrita">@if ($purchase->state == 'Pago pendiente')<a class="text-primary" style="font-size:16px; text-decoration: underline;" href="{{$purchase->payment_link}}">{{$purchase->state}}</a>@else{{$purchase->state}}@endif</td>
                <td class="negrita">{{$purchase->city->province->name}}</td>
                <td class="negrita">{{$purchase->city->name}}</td>
                <td class="negrita">{{$purchase->address}}</td>
                <td>
                    <button class="btn btn-primary" onclick="purchase_details('{{$purchase->id}}')" data-toggle="modal" data-target="#edit_purchase_modal">
                            <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #fbb710;">
                <th>Fecha</th>
                <th>Total ($)</th>
                <th>Estado</th>
                <th>Provincia</th>
                <th>Localidad</th>
                <th>Dirección</th>
                <td>Ver más</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@section('modal')
<!-- MODAL DETALLES DE COMPRA -->
<div class="modal fade" id="edit_purchase_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Detalles de compra</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">
                <div class="row d-flex justify-content-center">
                    <label class="text-primary negrita mb-0" style="font-size:30px;">Productos</label>
                </div>
                <div class="row d-flex justify-content-center mt-2 pr-5">
                    <table class="table table-responsive" id='products_table'>
                    </table>
                </div>
                <div id="total">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="js/toastr.min.js"></script>
<script type="text/javascript"> 
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    
    function purchase_details(id, state, purchase_lines, products){
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
          url:"/ajax/find_purchase_lines",
            success:function(r){
                if (typeof(r) == 'string'){location.reload()}else{
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
                         <td class="align-middle" style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;">
                             <h5>`,element['product'],`</h5>
                         </td>
                         <td class="align-middle" style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                             <h5>`,element['waist'],`</h5>
                         </td>
                         <td class="align-middle" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                             <h5>$`,element['subtotal']/element['qty'],`</h5>
                         </td>
                         <td class="align-middle" class="qty" style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                             <h5>`,element['qty'],`</h5>
                         </td>
                         <td class="align-middle" class="subtotal" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                             <h5>$`,element['subtotal'],`</h5>
                         </td>
                     </tr>`));
                });
                $('#total').children().remove();
                $('#total').append(`
                    <div class="row d-flex justify-content-center">
                        <label class="negrita" style="font-size:20px;">Total: $`.concat(r[0]['total'], `</label>
                    </div>`));
            }}
        });
    }
</script>

<script>
$('#deletebtn').popover({
    title: "<div class='d-flex justify-content-center'><label>Está por eliminar una compra ¿Desea continuar?</label></div>",
    content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_purchase'>Confirmar</button></div>",
    html: true,
    placement: "right"}); 
</script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/r-2.2.2/datatables.min.js"></script>

<!--DATATABLES-->
<script type="text/javascript">
$(document).ready(function () {
    $('#responsive_table').addClass('table-responsive');
    // Setup - add a text input to each footer cell
    $('#purchases_table tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" class="tcentrado negrita" style="border-radius: 10px;"/>');
    });

    var table = $('#purchases_table').DataTable({
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
            { "orderable": false, "targets": 6 },
            { "searchable": false, "targets": 6 }
          ],
        "autoWidth": false,
        "language": {
            "emptyTable": "Aún no haz efectuado ninguna compra",
            "lengthMenu": "Mostrar _MENU_ compras por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ compras",
            "infoEmpty": "No hay compras para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ compras)",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Búsqueda:",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

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

    var r = $('#purchases_table tfoot tr');
    $('#purchases_table thead').append(r);
    $('#search_0').css('text-align', 'center');

});
</script>
  
@endsection