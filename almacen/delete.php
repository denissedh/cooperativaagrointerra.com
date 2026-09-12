<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/almacen/cargar_producto.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Datos del Producto: <?php echo $nombre; ?> a ser eliminado</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">¿Estás seguro de eliminar el producto?</h3>
                        </div>
                        <div class="card-body">
                            <form action="../app/controllers/almacen/delete.php" method="post" >
                                <input type="text" name="id_producto" value="<?php echo $id_producto_get; ?>" hidden>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Código:</label>
                                                    <input type="text" class="form-control" value="<?php echo $codigo; ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Categoría:</label>
                                                    <input type="text" class="form-control" value="<?php echo $nombre_categoria; ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Nombre del Producto:</label>
                                                    <input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Usuario:</label>
                                                    <input type="text" class="form-control" value="<?php echo $email; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Beneficios:</label>
                                                    <textarea class="form-control" rows="2" disabled><?php echo $beneficios; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- === NUEVOS CAMPOS AGROINTERRA === -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Cantidad:</label>
                                                    <input type="text" class="form-control" value="<?php echo $cantidad.' '.$unidad; ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fecha de ingreso:</label>
                                                    <input type="date" class="form-control" value="<?php echo $fecha_ingreso; ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Ingredientes:</label>
                                                    <textarea class="form-control" rows="3" disabled><?php echo $ingredientes; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Propiedades:</label>
                                                    <textarea class="form-control" rows="3" disabled><?php echo $propiedades; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Imagen del producto</label>
                                            <center>
                                                <img src="<?php echo $URL . "/almacen/img_productos/" . $imagen; ?>" width="100%" alt="Imagen del producto" style="border-radius:8px;">
                                            </center>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Stock:</label>
                                            <input type="number" class="form-control" value="<?php echo $stock; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Stock mínimo:</label>
                                            <input type="number" class="form-control" value="<?php echo $stock_minimo; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Stock máximo:</label>
                                            <input type="number" class="form-control" value="<?php echo $stock_maximo; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Precio compra:</label>
                                            <input type="number" class="form-control" value="<?php echo $precio_compra; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Precio venta:</label>
                                            <input type="number" class="form-control" value="<?php echo $precio_venta; ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="index.php" class="btn btn-secondary">Volver</a>
                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Borrar producto</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include('../layout/mensajes.php'); 
include('../layout/parte2.php'); 
?>