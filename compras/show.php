<?php
include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');

include ('../app/controllers/almacen/listado_de_productos.php');
include ('../app/controllers/proveedores/listado_de_proveedores.php');
include ('../app/controllers/compras/cargar_compra.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Actualización de la compra</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Llene los datos con cuidado</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body" style="display: block;">
                                    <div style="display: flex">
                                        <h5>Datos del producto </h5>
                                        <div class="modal fade" id="modal-buscar_producto">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="table table-responsive">
                                                            <table id="example1" class="table table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th><center>Nro</center></th>
                                                                    <th><center>Seleccionar</center></th>
                                                                    <th><center>Código</center></th>
                                                                    <th><center>Categoría</center></th>
                                                                    <th><center>Imagen</center></th>
                                                                    <th><center>Nombre</center></th>
                                                                    <th><center>Presentación</center></th>
                                                                    <th><center>Unidad</center></th>
                                                                    <th><center>Stock</center></th>
                                                                    <th><center>Precio compra</center></th>
                                                                    <th><center>Precio venta</center></th>
                                                                    <th><center>Fecha ingreso</center></th>
                                                                    <th><center>Usuario</center></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $contador = 0;
                                                                foreach ($productos_datos as $productos_dato){
                                                                    $contador++;
                                                                    $id_producto_tabla = $productos_dato['id_producto']; ?>
                                                                    <tr>
                                                                        <td><?php echo $contador; ?></td>
                                                                        <td>
                                                                            <button class="btn btn-info btn-seleccionar-producto"
                                                                                    data-id="<?php echo $id_producto_tabla;?>"
                                                                                    data-codigo="<?php echo $productos_dato['codigo'];?>"
                                                                                    data-categoria="<?php echo $productos_dato['categoria'];?>"
                                                                                    data-nombre="<?php echo $productos_dato['nombre'];?>"
                                                                                    data-email="<?php echo $productos_dato['email'];?>"
                                                                                    data-beneficios="<?php echo isset($productos_dato['Beneficios']) ? $productos_dato['Beneficios'] : (isset($productos_dato['beneficios']) ? $productos_dato['beneficios'] : '');?>"
                                                                                    data-propiedades="<?php echo isset($productos_dato['propiedades']) ? $productos_dato['propiedades'] : '';?>"
                                                                                    data-ingredientes="<?php echo isset($productos_dato['ingredientes']) ? $productos_dato['ingredientes'] : '';?>"
                                                                                    data-cantidad-prod="<?php echo isset($productos_dato['cantidad']) ? $productos_dato['cantidad'] : '';?>"
                                                                                    data-unidad="<?php echo isset($productos_dato['unidad']) ? $productos_dato['unidad'] : '';?>"
                                                                                    data-stock="<?php echo $productos_dato['stock'];?>"
                                                                                    data-stock_min="<?php echo $productos_dato['stock_minimo'];?>"
                                                                                    data-stock_max="<?php echo $productos_dato['stock_maximo'];?>"
                                                                                    data-precio_compra="<?php echo $productos_dato['precio_compra'];?>"
                                                                                    data-precio_venta="<?php echo $productos_dato['precio_venta'];?>"
                                                                                    data-fecha="<?php echo $productos_dato['fecha_ingreso'];?>"
                                                                                    data-imagen="<?php echo $URL.'/almacen/img_productos/'.$productos_dato['imagen'];?>">
                                                                                Seleccionar
                                                                            </button>
                                                                        </td>
                                                                        <td><?php echo $productos_dato['codigo'];?></td>
                                                                        <td><?php echo $productos_dato['categoria'];?></td>
                                                                        <td>
                                                                            <img src="<?php echo $URL."/almacen/img_productos/".$productos_dato['imagen'];?>" width="50px" alt="">
                                                                        </td>
                                                                        <td><?php echo $productos_dato['nombre'];?></td>
                                                                        <td><?php echo isset($productos_dato['cantidad']) ? $productos_dato['cantidad'] : '';?></td>
                                                                        <td><?php echo isset($productos_dato['unidad']) ? $productos_dato['unidad'] : '';?></td>
                                                                        <td><?php echo $productos_dato['stock'];?></td>
                                                                        <td><?php echo $productos_dato['precio_compra'];?></td>
                                                                        <td><?php echo $productos_dato['precio_venta'];?></td>
                                                                        <td><?php echo $productos_dato['fecha_ingreso'];?></td>
                                                                        <td><?php echo $productos_dato['email'];?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="font-size: 12px">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" value="<?= $id_producto; ?>" id="id_producto" hidden>
                                                        <label for="">Código:</label>
                                                        <input type="text" value="<?= $codigo; ?>" class="form-control" id="codigo" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Categoría:</label>
                                                        <input type="text" value="<?= $nombre_categoria; ?>" class="form-control" id="categoria" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Nombre del producto:</label>
                                                        <input type="text" value="<?= $nombre_producto; ?>" name="nombre" id="nombre_producto" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Usuario:</label>
                                                        <input type="text" value="<?= $nombre_usuarios_producto; ?>" class="form-control" id="usuario_producto" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Presentación (Cantidad):</label>
                                                        <input type="text" value="<?= isset($cantidad_producto) ? $cantidad_producto : ''; ?>" class="form-control" id="cantidad_producto_vista" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Unidad:</label>
                                                        <input type="text" value="<?= isset($unidad) ? $unidad : ''; ?>" class="form-control" id="unidad_producto" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Ingredientes:</label>
                                                        <textarea id="ingredientes_producto" rows="2" class="form-control" disabled><?= isset($ingredientes) ? $ingredientes : ''; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Beneficios:</label>
                                                        <textarea name="Beneficios" id="beneficios_producto" rows="2" class="form-control" disabled><?= isset($beneficios) ? $beneficios : ''; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Propiedades:</label>
                                                        <textarea name="propiedades" id="propiedades_producto" rows="2" class="form-control" disabled><?= isset($propiedades) ? $propiedades : ''; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Stock:</label>
                                                        <input type="number" value="<?= $stock; ?>" name="stock" id="stock" class="form-control" style="background-color: #fff819" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Stock mínimo:</label>
                                                        <input type="number" value="<?= $stock_minimo; ?>" name="stock_minimo" id="stock_minimo" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Stock máximo:</label>
                                                        <input type="number" value="<?= $stock_maximo; ?>" name="stock_maximo" id="stock_maximo" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Precio compra:</label>
                                                        <input type="number" value="<?= $precio_compra_producto; ?>" name="precio_compra" id="precio_compra" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Precio venta:</label>
                                                        <input type="number" value="<?= $precio_venta_producto; ?>" name="precio_venta" id="precio_venta" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Fecha ingreso:</label>
                                                        <input type="date" style="font-size: 12px" value="<?= $fecha_ingreso; ?>" name="fecha_ingreso" id="fecha_ingreso" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Imagen del producto</label>
                                                <center>
                                                    <img src="<?php echo $URL."/almacen/img_productos/".$imagen;?>" id="img_producto" width="65%" alt="">
                                                </center>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div style="display: flex">
                                        <h5>Datos del proveedor </h5>
                                        <div style="width: 20px"></div>
                                        <div class="modal fade" id="modal-buscar_proveedor">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="table table-responsive">
                                                            <table id="example2" class="table table-bordered table-striped table-sm">
                                                                <thead>
                                                                <tr>
                                                                    <th><center>Nro</center></th>
                                                                    <th><center>Seleccionar</center></th>
                                                                    <th><center>Nombre del proveedor</center></th>
                                                                    <th><center>Celular</center></th>
                                                                    <th><center>Teléfono</center></th>
                                                                    <th><center>Empresa</center></th>
                                                                    <th><center>Email</center></th>
                                                                    <th><center>Dirección</center></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $contador = 0;
                                                                foreach ($proveedores_datos as $proveedores_dato){
                                                                    $contador++;
                                                                    $id_proveedor = $proveedores_dato['id_proveedor'];
                                                                    $nombre_proveedor = $proveedores_dato['nombre_proveedor']; ?>
                                                                    <tr>
                                                                        <td><center><?php echo $contador;?></center></td>
                                                                        <td>
                                                                            <button class="btn btn-info btn-seleccionar-proveedor"
                                                                                    data-id="<?php echo $id_proveedor; ?>"
                                                                                    data-nombre="<?php echo $nombre_proveedor; ?>"
                                                                                    data-celular="<?php echo $proveedores_dato['celular']; ?>"
                                                                                    data-telefono="<?php echo $proveedores_dato['telefono']; ?>"
                                                                                    data-empresa="<?php echo $proveedores_dato['empresa']; ?>"
                                                                                    data-email="<?php echo $proveedores_dato['email']; ?>"
                                                                                    data-direccion="<?php echo $proveedores_dato['direccion']; ?>">
                                                                                Seleccionar
                                                                            </button>
                                                                        </td>
                                                                        <td><?php echo $nombre_proveedor;?></td>
                                                                        <td>
                                                                            <a href="https://wa.me/591<?php echo $proveedores_dato['celular'];?>" target="_blank" class="btn btn-success">
                                                                                <i class="fa fa-whatsapp"></i>
                                                                                <?php echo $proveedores_dato['celular'];?>
                                                                            </a>
                                                                        </td>
                                                                        <td><?php echo $proveedores_dato['telefono'];?></td>
                                                                        <td><?php echo $proveedores_dato['empresa'];?></td>
                                                                        <td><?php echo $proveedores_dato['email'];?></td>
                                                                        <td><?php echo $proveedores_dato['direccion'];?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </div>

                                    <hr>

                                    <div class="container-fluid" style="font-size: 12px">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" value="<?= $id_proveedor_tabla; ?>" id="id_proveedor" hidden>
                                                    <label for="">Nombre del proveedor </label>
                                                    <input type="text" value="<?= $nombre_proveedor_tabla; ?>" id="nombre_proveedor" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Celular</label>
                                                    <input type="number" value="<?= $celular_proveedor; ?>" id="celular" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Teléfono</label>
                                                    <input type="number" value="<?= $telefono_proveedor; ?>" id="telefono" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Empresa </label>
                                                    <input type="text" value="<?= $empresa; ?>" id="empresa" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="email" value="<?= $email_proveedor; ?>" id="email" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Dirección</label>
                                                    <textarea name="" id="direccion" cols="30" rows="3" class="form-control" disabled><?= $direccion_proveedor; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Detalle de la compra</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Número de la compra</label>
                                                <input type="text" value="<?php echo $nro_compra; ?>" style="text-align: center" class="form-control" disabled>
                                                <input type="text" value="<?php echo $nro_compra; ?>" id="nro_compra" hidden>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Fecha de la compra</label>
                                                <input type="date" value="<?= $fecha_compra; ?>" class="form-control" id="fecha_compra" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Comprobante de la compra</label>
                                                <input type="text" value="<?= $comprobante; ?>" class="form-control" id="comprobante" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Precio de la compra</label>
                                                <input type="number" value="<?= $precio_compra; ?>" class="form-control" style="text-align: center" id="precio_compra_controlador" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Stock actual</label>
                                                <input type="text" value="<?= $stock - $cantidad; ?>" style="background-color: #fff819;text-align: center" id="stock_actual" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Stock Total</label>
                                                <input type="text" style="text-align: center" id="stock_total" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Cantidad de la compra</label>
                                                <input type="number" value="<?= $cantidad; ?>" id="cantidad_compra" style="text-align: center" class="form-control" disabled>
                                            </div>
                                            <script>
                                                $('#cantidad_compra').keyup(function () {
                                                    sumacantidades();
                                                });
                                                sumacantidades();
                                                function sumacantidades (){
                                                    var stock_actual = parseInt($('#stock_actual').val()) || 0;
                                                    var stock_compra = parseInt($('#cantidad_compra').val()) || 0;
                                                    var total = stock_actual + stock_compra;
                                                    $('#stock_total').val(total);
                                                }
                                            </script>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Usuario</label>
                                                <input type="text" class="form-control" value="<?php echo $nombres_usuarios; ?>" disabled>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <hr>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>
