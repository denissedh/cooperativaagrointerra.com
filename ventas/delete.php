<?php
$id_venta_get = $_GET['id_venta'];
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

include('../app/controllers/ventas/cargar_venta.php');
include('../app/controllers/clientes/cargar_clientes.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Detalle de la Venta nro <?= $nro_venta; ?> ¿Está seguro de eliminar esta venta?</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fa fa-shopping-bag"></i> Venta Nro: 
                                <input type="text" style="text-align: center" value="<?php echo $nro_venta; ?>" disabled>
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #e7e7e7;text-align: center">Nro</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Código</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Producto</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Presentación</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Beneficios</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Propiedades</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Ingredientes</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Cantidad</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Precio Unitario</th>
                                            <th style="background-color: #e7e7e7;text-align: center">Precio SubTotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $contador_de_carrito = 0;
                                    $cantidad_total = 0;
                                    $precio_unitario_total = 0;
                                    $precio_total = 0;

                                    $sql_carrito = "SELECT carr.*, 
                                                           pro.id_producto AS id_producto,
                                                           pro.codigo AS codigo,
                                                           pro.nombre AS nombre_producto, 
                                                           pro.cantidad AS presentacion_cantidad,
                                                           pro.unidad AS unidad,
                                                           pro.Beneficios AS beneficios, 
                                                           pro.propiedades AS propiedades,
                                                           pro.ingredientes AS ingredientes,
                                                           pro.precio_venta AS precio_venta, 
                                                           pro.stock AS stock 
                                                    FROM tb_carrito AS carr 
                                                    INNER JOIN tb_almacen AS pro ON carr.id_producto = pro.id_producto 
                                                    WHERE carr.nro_venta = '$nro_venta' 
                                                    ORDER BY carr.id_carrito ASC";
                                    $query_carrito = $pdo->prepare($sql_carrito);
                                    $query_carrito->execute();
                                    $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($carrito_datos as $carrito_dato) {
                                        $id_carrito = $carrito_dato['id_carrito'];
                                        $contador_de_carrito++;

                                        $cant_item = isset($carrito_dato['cantidad']) && is_numeric($carrito_dato['cantidad']) ? floatval($carrito_dato['cantidad']) : 0;
                                        $precio_item = isset($carrito_dato['precio_venta']) && is_numeric($carrito_dato['precio_venta']) ? floatval($carrito_dato['precio_venta']) : 0;

                                        $cantidad_total += $cant_item;
                                        $precio_unitario_total += $precio_item;
                                        
                                        $subtotal = $cant_item * $precio_item;
                                        $precio_total += $subtotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <center><?php echo $contador_de_carrito; ?></center>
                                            <input type="text" value="<?php echo $carrito_dato['id_producto']; ?>" id="id_producto<?php echo $contador_de_carrito; ?>" hidden>
                                        </td>
                                        <td><center><?php echo $carrito_dato['codigo']; ?></center></td>
                                        <td><?php echo $carrito_dato['nombre_producto']; ?></td>
                                        <td><?php echo $carrito_dato['presentacion_cantidad'] . ' ' . $carrito_dato['unidad']; ?></td>
                                        <td><?php echo $carrito_dato['beneficios']; ?></td>
                                        <td><?php echo $carrito_dato['propiedades']; ?></td>
                                        <td><?php echo $carrito_dato['ingredientes']; ?></td>
                                        <td>
                                            <center><span id="cantidad_carrito<?php echo $contador_de_carrito; ?>"><?php echo $cant_item; ?></span></center>
                                            <input type="text" value="<?php echo $carrito_dato['stock']; ?>" id="stock_de_inventario<?php echo $contador_de_carrito; ?>" hidden>
                                        </td>
                                        <td><center><?php echo $precio_item; ?></center></td>
                                        <td>
                                            <center>
                                                <?php echo $subtotal; ?>
                                            </center>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <th colspan="7" style="background-color: #e7e7e7;text-align: right">Total</th>
                                        <th><center><?php echo $cantidad_total; ?></center></th>
                                        <th><center><?php echo $precio_unitario_total; ?></center></th>
                                        <th style="background-color: #fff819"><center><?php echo $precio_total; ?></center></th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-user-check"></i> Datos del Cliente</h3>
                            
                            <?php
                            $nombre_cliente = '';
                            $direccion_cliente = '';
                            $celular_cliente = '';
                            $email_cliente = '';
                            foreach ($clientes_datos as $clientes_dato)
                            {
                                $nombre_cliente = $clientes_dato['nombre_cliente'] ?? '';
                                $direccion_cliente = $clientes_dato['direccion_cliente'] ?? $clientes_dato['Direccion_cliente'] ?? '';
                                $celular_cliente = $clientes_dato['celular_cliente'] ?? '';
                                $email_cliente = $clientes_dato['email_cliente'] ?? '';
                            }
                            ?>
                            <br>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="id_cliente" hidden>
                                        <label for="">Nombre del Cliente</label>
                                        <input type="text" value="<?php echo $nombre_cliente; ?>" class="form-control" id="nombre_cliente" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Dirección del Cliente</label>
                                        <input type="text" value="<?php echo $direccion_cliente; ?>" class="form-control" id="direccion_cliente" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Celular del Cliente</label>
                                        <input type="text" value="<?php echo $celular_cliente; ?>" class="form-control" id="celular_cliente" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Correo del Cliente</label>
                                        <input type="text" value="<?php echo $email_cliente; ?>" class="form-control" id="email_cliente" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-outline card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-shopping-basket"></i> Eliminar Venta</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Monto Total de la Venta</label>
                                <input type="text" class="form-control" id="total_a_cancelar" 
                                style="text-align: center; background-color: #fff815;" value="<?php echo $precio_total; ?>" disabled>
                            </div>
                            <hr>
                            <div class="form-group">
                                <button id="btn_borrar_venta" class="btn btn-danger btn-block">Eliminar Venta</button>
                            </div>
                            <script>
                                $('#btn_borrar_venta').click(function () {
                                    var id_venta = '<?php echo $id_venta_get; ?>';
                                    var total_productos = '<?php echo $contador_de_carrito; ?>';
                                    let actualizados = 0;

                                    function actualizar_stock() {
                                        for (let i = 1; i <= total_productos; i++){
                                            let stock_actual = parseInt($(`#stock_de_inventario${i}`).val());
                                            let cantidad_vendida = parseInt($(`#cantidad_carrito${i}`).text());
                                            let id_producto = $(`#id_producto${i}`).val();
                                            let stock_nuevo = stock_actual + cantidad_vendida;

                                            $.get("../app/controllers/ventas/actualizar_stock.php", {
                                                id_producto: id_producto,
                                                stock_calculado: stock_nuevo
                                            }, function () {
                                                actualizados++;
                                                if(actualizados == total_productos){
                                                    borrar_venta();
                                                }
                                            });
                                        }
                                    }

                                    function borrar_venta() {
                                        $.get("../app/controllers/ventas/borrar_venta.php", {id_venta: id_venta}, function (respuesta) {
                                            $('#btn_borrar_venta').html(respuesta);
                                            setTimeout(() => {
                                                window.location.href = 'index.php';
                                            }, 1500);
                                        });
                                    }

                                    if(confirm("¿Seguro que desea eliminar esta venta? Se devolverán los productos al inventario.")){
                                        actualizar_stock();
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 Productos",
                "infoFiltered": "(Filtrado de _MAX_ total Productos)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Productos",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $(function () {
        $("#example2").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Clientes",
                "infoEmpty": "Mostrando 0 a 0 de 0 Clientes",
                "infoFiltered": "(Filtrado de _MAX_ total Clientes)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Clientes",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
        }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>