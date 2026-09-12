<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');

include('../app/controllers/ventas/listado_de_ventas.php');

?>

<!-- Contenido de la página -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1>Listado de Ventas Realizadas</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ventas registradas</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><center>Nro</center></th>
                                <th><center>Nro de venta</center></th>
                                <th><center>Productos</center></th>
                                <th><center>Cliente</center></th>
                                <th><center>Total pagado</center></th>
                                <th><center>Acciones</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($ventas_datos as $ventas_dato) {
                                $estado_venta = strtolower($ventas_dato['estado'] ?? '');
                                
                                if (!empty($estado_venta) && !in_array($estado_venta, ['completada', 'pagada', 'confirmada', 'manual'])) {
                                    continue; 
                                }

                                $id_venta = $ventas_dato['id_venta'];
                                $id_cliente = $ventas_dato['id_cliente'] ?? null;
                                $direccion_cliente = $ventas_dato['direccion_cliente'] ?? 'S/N';
                                $celular_cliente = $ventas_dato['celular_cliente'] ?? 'S/N';
                                $email_cliente = $ventas_dato['email_cliente'] ?? 'S/N';
                                
                                // Determinar nombre a mostrar si es venta manual o cliente genérico
                                $nombre_cliente_mostrar = !empty($ventas_dato['nombre_cliente']) 
                                    ? $ventas_dato['nombre_cliente'] 
                                    : 'Venta Manual / Público General';

                                $contador = $contador + 1;
                            ?>
                            <tr>
                                <td><center><?php echo $contador; ?></center></td>
                                <td><center><?php echo $ventas_dato['nro_venta']; ?></center></td>
                                <td>
                                    <center>
                                        <button type="button" class="btn btn-primary" 
                                                data-toggle="modal" data-target="#Modal_productos<?php echo $id_venta; ?>">
                                            <i class="fa fa-shopping-basket"></i> Productos
                                        </button>

                                        <!-- Modal Productos -->
                                        <div class="modal fade" id="Modal_productos<?php echo $id_venta; ?>" tabindex="-1" role="dialog" 
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #08c2ec">
                                                        <h5 class="modal-title" id="exampleModalLabel">Productos de la Venta Nro 
                                                            <?php echo $ventas_dato['nro_venta']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-sm table-hover table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="background-color: #e7e7e7;text-align: center">Nro</th>
                                                                        <th style="background-color: #e7e7e7;text-align: center">Producto</th>
                                                                        <th style="background-color: #e7e7e7;text-align: center">Beneficios</th>
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

                                                                    $nro_venta = $ventas_dato['nro_venta'];

                                                                    $sql_carrito = "SELECT carr.*, pro.nombre as nombre_producto, pro.beneficios as beneficios, 
                                                                                        pro.precio_venta as precio_venta, pro.stock as stock, pro.id_producto as id_producto 
                                                                                FROM tb_carrito AS carr 
                                                                                INNER JOIN tb_almacen AS pro ON carr.id_producto = pro.id_producto 
                                                                                WHERE carr.nro_venta = :nro_venta 
                                                                                ORDER BY carr.id_carrito ASC";

                                                                    $query_carrito = $pdo->prepare($sql_carrito);
                                                                    $query_carrito->execute([':nro_venta' => $nro_venta]);
                                                                    $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

                                                                    foreach ($carrito_datos as $carrito_dato) {
                                                                        $contador_de_carrito++;
                                                                        $cantidad_item = floatval($carrito_dato['cantidad'] ?? 0);
                                                                        $precio_item = floatval($carrito_dato['precio_venta'] ?? 0);

                                                                        $cantidad_total += $cantidad_item;
                                                                        $precio_unitario_total += $precio_item;
                                                                        $subtotal = $cantidad_item * $precio_item;
                                                                        $precio_total += $subtotal;
                                                                    ?>
                                                                    <tr>
                                                                        <td><center><?php echo $contador_de_carrito; ?></center></td>
                                                                        <td><?php echo $carrito_dato['nombre_producto']; ?></td>
                                                                        <td><?php echo $carrito_dato['beneficios']; ?></td>
                                                                        <td><center><?php echo $cantidad_item; ?></center></td>
                                                                        <td><center><?php echo $precio_item; ?></center></td>
                                                                        <td><center><?php echo $subtotal; ?></center></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                    <tr>
                                                                        <th colspan="3" style="background-color: #e7e7e7;text-align: right">Total</th>
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
                                    </center>
                                </td>
                                <td><center>
                                    <button type="button" class="btn btn-warning" 
                                            data-toggle="modal" data-target="#Modal_clientes<?php echo $id_venta; ?>">
                                        <i class="fa fa-user"></i> <?php echo $nombre_cliente_mostrar; ?>
                                    </button>

                                    <!-- Modal Cliente -->
                                    <div class="modal fade" id="Modal_clientes<?php echo $id_venta; ?>">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #b6900c;color: white">
                                                    <h4 class="modal-title">Cliente</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php
                                                $nombre_cliente_modal = $nombre_cliente_mostrar;
                                                if (!empty($id_cliente)) {
                                                    $sql_clientes = "SELECT * FROM tb_clientes WHERE id_cliente = :id_cliente";
                                                    $query_clientes = $pdo->prepare($sql_clientes);
                                                    $query_clientes->execute([':id_cliente' => $id_cliente]);
                                                    $clientes_datos = $query_clientes->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($clientes_datos as $clientes_dato){
                                                        $nombre_cliente_modal = $clientes_dato['nombre_cliente'];
                                                    }
                                                }
                                                ?>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nombre del Cliente</label>
                                                        <input type="text" value="<?php echo $nombre_cliente_modal; ?>" class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Direccion del Cliente</label>
                                                        <input type="text" value="<?php echo $direccion_cliente; ?>" class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Celular del Cliente</label>
                                                        <input type="text" value="<?php echo $celular_cliente; ?>" class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Correo del Cliente</label>
                                                        <input type="email" value="<?php echo $email_cliente; ?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </center></td>
                                <td><center><button class="btn btn-success"><strong>Bs. <?php echo number_format($ventas_dato['total_pagado'], 2, '.', ''); ?></strong></button></center></td>
                                <td>
                                    <center>
                                        <a href="show.php?id_venta=<?php echo $id_venta; ?>" class="btn btn-info"><i class="fa fa-eye"></i> Ver</a>
                                        <a href="delete.php?id_venta=<?php echo $id_venta; ?>&nro_venta=<?php echo $ventas_dato['nro_venta']; ?>" 
                                           class="btn btn-danger"><i class="fa fa-trash"></i> Borrar</a>
                                        <a href="factura.php?id_venta=<?php echo $id_venta; ?>" class="btn btn-success"><i class="fa fa-print"></i> Imprimir</a>
                                    </center>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Ventas",
                "infoEmpty": "Mostrando 0 a 0 de 0 Ventas",
                "infoFiltered": "(Filtrado de _MAX_ total Ventas)",
                "lengthMenu": "Mostrar _MENU_ Ventas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            buttons: [{
                extend: 'collection',
                text: 'Reportes',
                buttons: [
                    { extend: 'copy', text: 'Copiar' },
                    { extend: 'pdf' },
                    { extend: 'csv' },
                    { extend: 'excel' },
                    { extend: 'print', text: 'Imprimir' }
                ]
            }, {
                extend: 'colvis',
                text: 'Visor de columnas'
            }],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>