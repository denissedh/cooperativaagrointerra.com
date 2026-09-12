<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/compras/listado_de_compras.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Listado de compras actualizado</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Compras registradas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th><center>Nro</center></th>
                                            <th><center>Producto</center></th>
                                            <th><center>Fecha de compra</center></th>
                                            <th><center>Proveedor</center></th>
                                            <th><center>Comprobante</center></th>
                                            <th><center>Usuario</center></th>
                                            <th><center>Precio compra</center></th>
                                            <th><center>Cantidad</center></th>
                                            <th><center>Acciones</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($compras_datos as $compras_dato){
                                            $id_compra = $compras_dato['id_compra'];
                                            $contador++;
                                        ?>
                                        <tr>
                                            <td><?php echo $contador; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#modal-producto<?php echo $id_compra;?>">
                                                    <?php echo $compras_dato['nombre_producto'];?>
                                                </button>

                                                <!-- Modal: Datos completos del producto -->
                                                <div class="modal fade" id="modal-producto<?php echo $id_compra;?>">
                                                    <div class="modal-dialog modal-xl"> <!-- Modal más ancho -->
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color: #07b0d6;color: white">
                                                                <h4 class="modal-title">Datos del producto</h4>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-9">
                                                                        <!-- Fila 1: Código, Nombre -->
                                                                        <div class="row">
                                                                            <div class="col-md-2">
                                                                                <div class="form-group">
                                                                                    <label>Código</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['codigo'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-10">
                                                                                <div class="form-group">
                                                                                    <label>Nombre del producto</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['nombre'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 2: BENEFICIOS MÁS GRANDE -->
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Beneficios del producto</label>
                                                                                    <textarea class="form-control" rows="4" disabled><?php echo $compras_dato['beneficios'];?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 3: Cantidad, Unidad -->
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Cantidad</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['cantidad'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-9">
                                                                                <div class="form-group">
                                                                                    <label>Unidad</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['unidad'];?>" class="form-control" disabled placeholder="Ej: 1 Litro, 500 ml">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 4: INGREDIENTES MÁS GRANDE -->
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Ingredientes</label>
                                                                                    <textarea class="form-control" rows="4" disabled><?php echo $compras_dato['ingredientes'];?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 5: PROPIEDADES MÁS GRANDE -->
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label>Propiedades</label>
                                                                                    <textarea class="form-control" rows="4" disabled><?php echo $compras_dato['propiedades'];?></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 6: Stock, Fechas -->
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Stock</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['stock'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Stock mínimo</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['stock_minimo'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Stock máximo</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['stock_maximo'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Fecha de Ingreso</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['fecha_ingreso'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Fila 7: Precios, Categoría, Usuario -->
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Precio Compra</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['precio_compra_producto'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Precio Venta</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['precio_venta_producto'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Categoría</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['nombre_categoria'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label>Usuario</label>
                                                                                    <input type="text" value="<?php echo $compras_dato['nombres'];?>" class="form-control" disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Columna Imagen -->
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label>Imagen</label>
                                                                            <img src="<?php echo $URL."/almacen/img_productos/".$compras_dato['imagen'];?>" class="img-thumbnail" style="width:100%;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /Modal producto -->
                                            </td>

                                            <td><?php echo $compras_dato['fecha_compra'];?></td>

                                            <td>
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#modal-proveedor<?php echo $id_compra;?>">
                                                    <?php echo $compras_dato['nombre_proveedor'];?>
                                                </button>

                                                <!-- Modal: Datos del proveedor -->
                                                <div class="modal fade" id="modal-proveedor<?php echo $id_compra;?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color: #07b0d6;color: white">
                                                                <h4 class="modal-title">Datos del proveedor</h4>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Nombre del proveedor</label>
                                                                            <input type="text" value="<?php echo $compras_dato['nombre_proveedor'];?>" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Celular</label>
                                                                            <a href="https://wa.me/591<?php echo $compras_dato['celular_proveedor'];?>" target="_blank" class="btn btn-success btn-block">
                                                                                <i class="fab fa-whatsapp"></i> <?php echo $compras_dato['celular_proveedor'];?>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Teléfono</label>
                                                                            <input type="text" value="<?php echo $compras_dato['telefono_proveedor'];?>" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Empresa</label>
                                                                            <input type="text" value="<?php echo $compras_dato['empresa'];?>" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Email</label>
                                                                            <input type="text" value="<?php echo $compras_dato['email_proveedor'];?>" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Dirección</label>
                                                                            <input type="text" value="<?php echo $compras_dato['direccion_proveedor'];?>" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><?php echo $compras_dato['comprobante'];?></td>
                                            <td><?php echo $compras_dato['nombres_usuario'];?></td>
                                            <td><?php echo $compras_dato['precio_compra'];?></td>
                                            <td><?php echo $compras_dato['cantidad'];?></td>
                                            <td>
                                                <center>
                                                    <div class="btn-group">
                                                        <a href="show.php?id=<?php echo $id_compra; ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Ver</a>
                                                        <a href="update.php?id=<?php echo $id_compra; ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil-alt"></i> Editar</a>
                                                        <a href="delete.php?id=<?php echo $id_compra; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Borrar</a>
                                                    </div>
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
        </div>
    </div>
</div>

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Compras",
                "infoEmpty": "Mostrando 0 a 0 de 0 Compras",
                "infoFiltered": "(Filtrado de _MAX_ total Compras)",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Compras",
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
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            buttons: [
                { extend: 'collection', text: 'Reportes', orientation: 'landscape',
                    buttons: [
                        { extend: 'copy', text: 'Copiar' },
                        { extend: 'pdf' }, { extend: 'csv' }, { extend: 'excel' }, { extend: 'print', text: 'Imprimir' }
                    ]
                },
                { extend: 'colvis', text: 'Visor de columnas', collectionLayout: 'fixed three-column' }
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>