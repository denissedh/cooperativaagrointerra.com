<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');

include ('../app/controllers/almacen/cargar_producto.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Datos del producto: <?php echo $nombre;?></h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Datos del producto</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body" style="display: block;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Código:</label>
                                                        <input type="text" class="form-control" value="<?php echo $codigo; ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Categoría:</label>
                                                        <input type="text" class="form-control" value="<?php echo $nombre_categoria; ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Nombre del producto:</label>
                                                        <input type="text" name="nombre" value="<?php echo $nombre;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Usuario</label>
                                                        <input type="text" class="form-control" value="<?php echo $email; ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="">Beneficios:</label>
                                                        <textarea name="descripcion" cols="30" rows="2" class="form-control" disabled><?php echo $beneficios;?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- === NUEVA SECCIÓN: Presentación del producto === -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Cantidad :</label>
                                                        <input type="text" class="form-control" value="<?php echo $cantidad.' '.$unidad; ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Fecha de ingreso:</label>
                                                        <input type="date" name="fecha_ingreso" value="<?php echo $fecha_ingreso;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- === NUEVA SECCIÓN: Ingredientes y Propiedades === -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Ingredientes:</label>
                                                        <textarea class="form-control" rows="3" disabled><?php echo $ingredientes; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Propiedades:</label>
                                                        <textarea class="form-control" rows="3" disabled><?php echo $propiedades; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Stock:</label>
                                                        <input type="number" name="stock" value="<?php echo $stock;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Stock mínimo:</label>
                                                        <input type="number" name="stock_minimo" value="<?php echo $stock_minimo;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Stock máximo:</label>
                                                        <input type="number" name="stock_maximo" value="<?php echo $stock_maximo;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Precio compra:</label>
                                                        <input type="number" name="precio_compra" value="<?php echo $precio_compra;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">Precio venta:</label>
                                                        <input type="number" name="precio_venta" value="<?php echo $precio_venta;?>" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Imagen del producto</label>
                                                <center>
                                                    <img src="<?php echo $URL."/almacen/img_productos/".$imagen;?>" width="100%" alt="Imagen del producto" style="border-radius:8px;">
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <a href="index.php" class="btn btn-secondary">Volver al listado</a>
                                        <a href="update.php?id=<?php echo $id_producto; ?>" class="btn btn-success">Editar producto</a>
                                    </div>
                                </div>
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