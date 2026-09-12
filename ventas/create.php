<?php

include('../app/config.php');

include('../layout/sesion.php');

include('../layout/parte1.php');

include('../app/controllers/ventas/listado_de_ventas.php');

include('../app/controllers/almacen/listado_de_productos.php');

include('../app/controllers/clientes/listado_de_clientes.php');

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-12">

                    <h1 class="m-0">Ventas</h1>

                </div>

            </div>

        </div>

    </div>

    <!-- /.content-header -->

    <!-- Main content -->

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">

                    <div class="card card-outline card-primary">

                        <div class="card-header">

                            <?php

                            $contador_de_ventas = 0;

                            foreach ($ventas_datos as $ventas_dato) {

                                $contador_de_ventas = $contador_de_ventas + 1;

                            }

                            ?>

                            <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Venta Nro

                                <input type="text" style="text-align:center" value="<?php echo $contador_de_ventas + 1; ?>" disabled>

                            </h3>

                            <div class="card-tools">

                                <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-minus"></i>

                                </button>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">

                                    <b>Venta</b>

                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-buscar_producto">

                                        <i class="fas fa-search"></i> Buscar Producto

                                    </button>

                                </div>

                            </div>

                            <!-- Modal para ver datos de los productos -->

                            <div class="modal fade" id="modal-buscar_producto">

                                <div class="modal-dialog modal-xl">

                                    <div class="modal-content">

                                        <div class="modal-header" style="background-color: #07b0d6;color: white">

                                            <h4 class="modal-title">Búsqueda del Producto</h4>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                                <span aria-hidden="true">&times;</span>

                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="table-responsive">

                                                <table id="example1" class="table table-bordered table-striped table-sm w-100">

                                                    <thead>

                                                        <tr>

                                                            <th><center>Nro</center></th>

                                                            <th><center>Seleccionar</center></th>

                                                            <th><center>Código</center></th>

                                                            <th><center>Nombre</center></th>

                                                            <th><center>Presentación</center></th>

                                                            <th><center>Propiedades / Beneficios</center></th>

                                                            <th><center>Stock</center></th>

                                                            <th><center>Precio Venta</center></th>

                                                            <th><center>Fecha Ingreso</center></th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $contador = 0;

                                                        foreach ($productos_datos as $productos_dato) {

                                                            $id_producto = $productos_dato['id_producto']; 

                                                            $detalle_propiedades = !empty($productos_dato['propiedades']) ? $productos_dato['propiedades'] : (isset($productos_dato['Beneficios']) ? $productos_dato['Beneficios'] : '');

                                                            $presentacion = (isset($productos_dato['cantidad']) ? $productos_dato['cantidad'] : '') . ' ' . (isset($productos_dato['unidad']) ? $productos_dato['unidad'] : '');

                                                        ?>

                                                        <tr>

                                                            <td><center><?php echo ++$contador; ?></center></td>

                                                            <td>

                                                                <center>

                                                                    <button type="button" class="btn btn-info btn-sm btn-seleccionar-producto"

                                                                        data-id="<?php echo $id_producto; ?>"

                                                                        data-nombre="<?php echo htmlspecialchars($productos_dato['nombre'], ENT_QUOTES, 'UTF-8'); ?>"

                                                                        data-propiedades="<?php echo htmlspecialchars($detalle_propiedades, ENT_QUOTES, 'UTF-8'); ?>"

                                                                        data-unidad="<?php echo htmlspecialchars(trim($presentacion), ENT_QUOTES, 'UTF-8'); ?>"

                                                                        data-precio="<?php echo $productos_dato['precio_venta']; ?>"

                                                                        data-stock="<?php echo $productos_dato['stock']; ?>">

                                                                        Seleccionar

                                                                    </button>

                                                                </center>

                                                            </td>

                                                            <td><center><?php echo $productos_dato['codigo']; ?></center></td>

                                                            <td><?php echo $productos_dato['nombre']; ?></td>

                                                            <td><center><?php echo trim($presentacion); ?></center></td>

                                                            <td><?php echo $detalle_propiedades; ?></td>

                                                            <td><center><?php echo (int)$productos_dato['stock']; ?></center></td>

                                                            <td><center><?php echo number_format($productos_dato['precio_venta'], 2); ?></center></td>

                                                            <td><center><?php echo $productos_dato['fecha_ingreso']; ?></center></td>

                                                        </tr>

                                                        <?php } ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                            <div class="row mt-3">

                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <input type="hidden" id="id_producto">

                                                        <input type="hidden" id="stock_maximo">

                                                        <label for="producto">Producto</label>

                                                        <input type="text" id="producto" class="form-control" disabled>

                                                    </div>

                                                </div>

                                                <div class="col-md-3">

                                                    <div class="form-group">

                                                        <label for="propiedades_prod">Propiedades / Beneficios</label>

                                                        <input type="text" id="propiedades_prod" class="form-control" disabled>

                                                    </div>

                                                </div>

                                                <div class="col-md-2">

                                                    <div class="form-group">

                                                        <label for="unidad_prod">Presentación / Unidad</label>

                                                        <input type="text" id="unidad_prod" class="form-control" disabled>

                                                    </div>

                                                </div>

                                                <div class="col-md-2">

                                                    <div class="form-group">

                                                        <label for="cantidad_venta">Cantidad</label>

                                                        <input type="number" min="1" id="cantidad_venta" class="form-control">

                                                    </div>

                                                </div>

                                                <div class="col-md-2">

                                                    <div class="form-group">

                                                        <label for="precio_venta">Precio Unitario</label>

                                                        <input type="text" id="precio_venta" class="form-control" disabled>

                                                    </div>

                                                </div>

                                            </div>

                                            <button style="float: right" id="btn_guardar_venta" class="btn btn-success">

                                                <i class="fas fa-cart-plus"></i> Agregar a la Venta

                                            </button>

                                            <div id="respuesta_carrito"></div>

                                            <br><br>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <br><br>

                            <!-- TABLA DEL CARRITO -->

                            <div class="table-responsive">

                                <table class="table table-bordered table-sm table-hover table-striped">

                                    <thead>

                                        <tr>

                                            <th style="background-color: #e7e7e7; text-align: center">Nro</th>

                                            <th style="background-color: #e7e7e7; text-align: center">Producto</th>

                                            <th style="background-color: #e7e7e7; text-align: center">Detalles / Unidad</th>

                                            <th style="background-color: #e7e7e7; text-align: center">Cantidad</th>

                                            <th style="background-color: #e7e7e7; text-align: center">Precio Unitario</th>

                                            <th style="background-color: #e7e7e7; text-align: center">Precio Subtotal</th>

                                            <th style="background-color: #e7e7e7; text-align: center">Acción</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $nro_venta = $contador_de_ventas + 1;

                                        $contador_de_carrito = 0;

                                        $total = 0;

                                        $sql_carrito = "SELECT carr.*, pro.nombre as nombre_prod, pro.precio_venta as precio, pro.stock as stock, pro.unidad as unidad, pro.cantidad as cant_prod, pro.propiedades as propiedades, pro.id_producto as id_producto_almacen 

                                            FROM tb_carrito as carr 

                                            INNER JOIN tb_almacen as pro on carr.id_producto = pro.id_producto 

                                            WHERE carr.nro_venta = '$nro_venta' 

                                            ORDER BY carr.id_carrito ASC";

                                        $query_carrito = $pdo->prepare($sql_carrito);

                                        $query_carrito->execute();

                                        $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($carrito_datos as $carrito_dato) {

                                            $contador_de_carrito = $contador_de_carrito + 1;

                                            $id_carrito = $carrito_dato['id_carrito'];

                                            $cantidad = (int)$carrito_dato['cantidad'];

                                            $precio = $carrito_dato['precio'];

                                            $subtotal = $cantidad * $precio;

                                            $total = $total + $subtotal;

                                        ?>

                                        <tr>

                                            <td>

                                                <center><?php echo $contador_de_carrito; ?></center>

                                                <input type="hidden" value="<?php echo $carrito_dato['id_producto_almacen']; ?>" id="id_producto<?php echo $contador_de_carrito; ?>">

                                            </td>

                                            <td><center><?php echo $carrito_dato['nombre_prod']; ?></center></td>

                                            <td><center><?php echo $carrito_dato['cant_prod'] . ' ' . $carrito_dato['unidad']; ?></center></td>

                                            <td>

                                                <center><span id="cantidad_carrito<?php echo $contador_de_carrito; ?>">

                                                    <?php echo $cantidad; ?>

                                                </span></center>

                                                <input type="hidden" value="<?php echo (int)$carrito_dato['stock']; ?>" id="stock_de_inventario<?php echo $contador_de_carrito; ?>">

                                            </td>

                                            <td><center><?php echo number_format($precio, 2); ?></center></td>

                                            <td style="text-align: right">

                                                <?php echo number_format($subtotal, 2); ?>

                                            </td>

                                            <td>

                                                <center>

                                                    <form action="../app/controllers/ventas/borrar_carrito.php" method="post">

                                                        <input type="hidden" name="id_carrito" value="<?php echo $id_carrito; ?>">

                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Borrar</button>

                                                    </form>

                                                </center>

                                            </td>

                                        </tr>

                                        <?php } ?>

                                        <tr>

                                            <th colspan="4"></th>

                                            <th style="background-color:#98FB98; text-align: right">TOTAL</th>

                                            <th style="background-color:#98FB98; text-align: right"><?php echo number_format($total, 2); ?></th>

                                            <th></th>

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

                    <div class="card card-outline card-primary">

                        <div class="card-header">

                            <h3 class="card-title"><i class="fas fa-user-check"></i> Datos del Cliente</h3>

                            <div class="card-tools">

                                <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-minus"></i>

                                </button>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="col-md-6 mb-3">

                                <b>Cliente</b>

                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-buscar_cliente">

                                    <i class="fas fa-search"></i> Buscar Cliente

                                </button>

                            </div>

                            <!-- Modal para ver datos de los clientes -->

                            <div class="modal fade" id="modal-buscar_cliente">

                                <div class="modal-dialog modal-xl">

                                    <div class="modal-content">

                                        <div class="modal-header" style="background-color: #07b0d6;color: white">

                                            <h4 class="modal-title">Búsqueda del Cliente </h4>

                                            <div style="width: 12px"></div>

                                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#modal-agregar_cliente">

                                                <i class="fas fa-plus"></i> Agregar Cliente

                                            </button>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                                <span aria-hidden="true">&times;</span>

                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="table-responsive">

                                                <table id="example2" class="table table-bordered table-striped table-sm w-100">

                                                    <thead>

                                                        <tr>

                                                            <th><center>Nro</center></th>

                                                            <th><center>Seleccionar</center></th>

                                                            <th><center>Nombre</center></th>

                                                            <th><center>Dirección</center></th>

                                                            <th><center>Celular</center></th>

                                                            <th><center>Email</center></th>

                                                        </tr>

                                                    </thead>

                                                    <tbody>

                                                        <?php

                                                        $contador_clientes = 0;

                                                        foreach ($clientes_datos as $clientes_dato) {

                                                            $id_cliente = $clientes_dato['id_cliente']; 

                                                            $contador_clientes = $contador_clientes + 1; 

                                                        ?>

                                                        <tr>

                                                            <td><center><?php echo $contador_clientes; ?></center></td>

                                                            <td>

                                                                <center>

                                                                    <button type="button" class="btn btn-info btn-sm btn-seleccionar-cliente"

                                                                        data-id="<?php echo $id_cliente; ?>"

                                                                        data-nombre="<?php echo htmlspecialchars($clientes_dato['nombre_cliente'], ENT_QUOTES, 'UTF-8'); ?>"

                                                                        data-direccion="<?php echo htmlspecialchars($clientes_dato['direccion_cliente'], ENT_QUOTES, 'UTF-8'); ?>"

                                                                        data-celular="<?php echo htmlspecialchars($clientes_dato['celular_cliente'], ENT_QUOTES, 'UTF-8'); ?>"

                                                                        data-email="<?php echo htmlspecialchars($clientes_dato['email_cliente'], ENT_QUOTES, 'UTF-8'); ?>">

                                                                        Seleccionar

                                                                    </button>

                                                                </center>

                                                            </td>

                                                            <td><center><?php echo $clientes_dato['nombre_cliente']; ?></center></td>

                                                            <td><center><?php echo $clientes_dato['direccion_cliente']; ?></center></td>

                                                            <td><center><?php echo $clientes_dato['celular_cliente']; ?></center></td>

                                                            <td><center><?php echo $clientes_dato['email_cliente']; ?></center></td>

                                                        </tr>

                                                        <?php } ?>

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <input type="hidden" id="id_cliente">

                                        <label for="nombre_cliente">Nombre Cliente</label>

                                        <input type="text" class="form-control" id="nombre_cliente" disabled>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="direccion_cliente">Dirección</label>

                                        <input type="text" class="form-control" id="direccion_cliente" disabled>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="celular_cliente">Celular</label>

                                        <input type="text" class="form-control" id="celular_cliente" disabled>

                                    </div>

                                </div>

                                <div class="col-md-3">

                                    <div class="form-group">

                                        <label for="email_cliente">Email</label>

                                        <input type="text" class="form-control" id="email_cliente" disabled>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card card-outline card-primary">

                        <div class="card-header">

                            <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Registrar Venta</h3>

                            <div class="card-tools">

                                <button type="button" class="btn btn-tool" data-card-widget="collapse">

                                    <i class="fas fa-minus"></i>

                                </button>

                            </div>

                        </div>

                        <div class="card-body">

                            <div class="form-group">

                                <label for="total_a_pagar">Total a Pagar</label>

                                <input type="text" class="form-control" id="total_a_pagar" style="text-align: center; font-weight:bold; background-color:#98FB98"

                                    value="<?php echo number_format($total, 2); ?>" disabled>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label for="paga_con">Paga Con</label>

                                        <input type="number" step="any" class="form-control" id="paga_con" style="text-align: center; font-weight:bold">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label for="vuelto">Vuelto</label>

                                        <input type="text" class="form-control" id="vuelto" style="text-align: center; font-weight:bold" disabled>

                                    </div>

                                </div>

                            </div>

                            <hr>

                            <div class="form-group">

                                <button id="btn_registrar" class="btn btn-success btn-block">Guardar Venta</button>

                                <div id="respuesta_registro_venta"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div><!-- /.container-fluid -->

    </div>

    <!-- /.content -->

</div>

<!-- /.content-wrapper -->

<!-- Modal para agregar cliente -->

<div class="modal fade" id="modal-agregar_cliente">

    <div class="modal-dialog modal-md">

        <div class="modal-content">

            <div class="modal-header" style="background-color:#9370DB; color: white">

                <h4 class="modal-title">Agregar Cliente </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="../app/controllers/clientes/guardar_cliente.php" method="post">

                    <div class="form-group">

                        <label for="modal_nombre_cliente">Nombre del Cliente</label>

                        <input type="text" id="modal_nombre_cliente" name="nombre_cliente" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label for="modal_direccion_cliente">Dirección</label>

                        <input type="text" id="modal_direccion_cliente" name="direccion_cliente" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label for="modal_celular_cliente">Celular</label>

                        <input type="text" id="modal_celular_cliente" name="celular_cliente" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label for="modal_email_cliente">Email</label>

                        <input type="email" id="modal_email_cliente" name="email_cliente" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label for="modal_password_cliente">Contraseña</label>

                        <input type="password" id="modal_password_cliente" name="password_cliente" class="form-control" required>

                    </div>

                    <hr>

                    <div class="form-group">

                        <center>

                            <button type="submit" class="btn btn-primary">Agregar</button>

                        </center>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include('../layout/mensajes.php'); ?>

<?php include('../layout/parte2.php'); ?>

<script>

    $(document).ready(function() {

        // Inicializar DataTables

        $("#example1").DataTable({

            "pageLength": 5,

            "language": {

                "emptyTable": "No hay información",

                "info": "Mostrando _START_ a _END_ de _TOTAL_ Productos",

                "infoEmpty": "Mostrando 0 a 0 de 0 Productos",

                "infoFiltered": "(Filtrado de _MAX_ total Productos)",

                "lengthMenu": "Mostrar _MENU_ Productos",

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

            "autoWidth": false

        });

        $("#example2").DataTable({

            "pageLength": 5,

            "language": {

                "emptyTable": "No hay información",

                "info": "Mostrando _START_ a _END_ de _TOTAL_ Clientes",

                "infoEmpty": "Mostrando 0 a 0 de 0 Clientes",

                "infoFiltered": "(Filtrado de _MAX_ total Clientes)",

                "lengthMenu": "Mostrar _MENU_ Clientes",

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

            "autoWidth": false

        });

        // Seleccionar producto

        $(document).on('click', '.btn-seleccionar-producto', function() {

            var id = $(this).data('id');

            var nombre = $(this).data('nombre');

            var propiedades = $(this).data('propiedades');

            var unidad = $(this).data('unidad');

            var precio = $(this).data('precio');

            var stock = $(this).data('stock');

            $('#id_producto').val(id);

            $('#producto').val(nombre);

            $('#propiedades_prod').val(propiedades);

            $('#unidad_prod').val(unidad);

            $('#precio_venta').val(precio);

            $('#stock_maximo').val(stock);

            $('#cantidad_venta').val('').focus();

        });

        // Bloquear decimales en cantidad

        $('#cantidad_venta').on('input', function() {

            this.value = this.value.replace(/[^0-9]/g, '');

        });

        // Agregar al carrito

        $('#btn_guardar_venta').click(function() {

            var nro_venta = '<?php echo $contador_de_ventas + 1; ?>';

            var id_producto = $('#id_producto').val();

            var cantidad = parseInt($('#cantidad_venta').val());

            var stock = parseInt($('#stock_maximo').val());

            if (id_producto === "") {

                alert("Debe seleccionar un PRODUCTO primero.");

                return;

            }

            if (isNaN(cantidad) || cantidad <= 0) {

                alert("Escribe una CANTIDAD válida mayor a 0.");

                return;

            }

            if (cantidad > stock) {

                alert("No hay suficiente stock. Stock disponible: " + stock);

                return;

            }

            var url = "../app/controllers/ventas/guardar_venta.php";

            $.get(url, {

                nro_venta: nro_venta,

                id_producto: id_producto,

                cantidad: cantidad

            }, function(datos) {

                location.reload();

            });

        });

        // Seleccionar cliente actualizado

        $(document).on('click', '.btn-seleccionar-cliente', function() {

            $('#id_cliente').val($(this).data('id'));

            $('#nombre_cliente').val($(this).data('nombre'));

            $('#direccion_cliente').val($(this).data('direccion'));

            $('#celular_cliente').val($(this).data('celular'));

            $('#email_cliente').val($(this).data('email'));

            $('#modal-buscar_cliente').modal('hide');

        });

        // Cálculo de Vuelto

        $('#paga_con').on('keyup change', function() {

            var total_a_pagar = parseFloat($('#total_a_pagar').val()) || 0;

            var paga_con = parseFloat($(this).val()) || 0;

            var vuelto = paga_con - total_a_pagar;

            $('#vuelto').val(vuelto >= 0 ? vuelto.toFixed(2) : '0.00');

        });

        // Guardar venta con redirección automática

        $('#btn_registrar').click(function() {

            var nro_venta = '<?php echo $contador_de_ventas + 1; ?>';

            var id_cliente = $('#id_cliente').val();

            // 1. Limpiar comas del total para enviarlo en formato numérico válido (ej. 1250.00)

            var total_raw = $('#total_a_pagar').val().replace(/,/g, '');

            var total_a_pagar = parseFloat(total_raw) || 0;

            var n_items = parseInt('<?php echo $contador_de_carrito; ?>') || 0;

            if (id_cliente === "") {

                alert("Debe seleccionar un CLIENTE antes de continuar.");

                return;

            }

            if (n_items === 0 || total_a_pagar <= 0) {

                alert("El carrito no puede estar vacío.");

                return;

            }

            // 2. Actualizar stock de cada producto

            for (var i = 1; i <= n_items; i++) {

                var stock_de_inventario = parseInt($('#stock_de_inventario' + i).val());

                var cantidad_carrito = parseInt($('#cantidad_carrito' + i).text().trim());

                var id_producto = $('#id_producto' + i).val();

                var stock_calculado = stock_de_inventario - cantidad_carrito;

                var url2 = "../app/controllers/ventas/actualizar_stock.php";

                $.get(url2, {id_producto: id_producto, stock_calculado: stock_calculado});

            }

            // 3. Registrar la venta y redirigir automáticamente a index.php

            var url = "../app/controllers/ventas/registro_de_ventas.php";

            $.get(url, {

                nro_venta: nro_venta, 

                id_cliente: id_cliente, 

                total_a_pagar: total_a_pagar

            }, function(datos) { 

                $('#respuesta_registro_venta').html(datos);

                // Redirigir a index.php tras registrar la venta

                setTimeout(function(){

                    window.location.href = 'index.php';

                }, 800);

            });

        });

    });

</script>