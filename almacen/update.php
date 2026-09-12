<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/categorias/listado_de_categorias.php'); 
include('../app/controllers/almacen/cargar_producto.php'); 
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Actualizar producto</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                             <h3 class="card-title">Llene con cuidado los datos</h3>
                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="../app/controllers/almacen/update.php" method="post" enctype="multipart/form-data">
                                <input type="text" value="<?php echo $id_producto_get; ?>" name="id_producto" hidden>
                                
                                <!-- FILA PRINCIPAL: FORMULARIO (IZQUIERDA) + IMAGEN (DERECHA) -->
                                <div class="row">
                                    <!-- COLUMNA IZQUIERDA: TODOS LOS CAMPOS -->
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Código:</label>
                                                    <input type="text"   class="form-control" 
                                                           value="<?php echo $codigo; ?>" disabled>
                                                    <input type="text" name="codigo" value="<?php echo $codigo; ?>" hidden>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Categoría:</label>
                                                    <div style="display: flex">
                                                        <select name="id_categorias" id="" class="form-control" required>
                                                        <?php
                                                        foreach ($categorias_datos as $categorias_dato){ 
                                                            $nombre_categora_tabla = $categorias_dato['nombre_categoria']; 
                                                            $id_categoria = $categorias_dato['id_categoria']?>
                                                            <option value="<?php echo $id_categoria; ?>" 
                                                                <?php if($nombre_categora_tabla == $nombre_categoria) { ?> selected="selected" <?php } ?>>
                                                                <?php echo $nombre_categora_tabla;?>
                                                            </option>
                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nombre del Producto:</label>
                                                    <input type="text" name="nombre" value="<?php echo $nombre;?>" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Usuario:</label>
                                                    <input type="text" class="form-control" value="<?php echo $email;?>" disabled>
                                                    <input type="text" name="id_usuarios" value="<?php echo $id_usuarios;?>" hidden>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Beneficios:</label>
                                                    <textarea name="beneficios" class="form-control" rows="3" placeholder="Describa los beneficios"><?php echo $beneficios ?? ''; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Cantidad:</label>
                                                    <input type="text" name="cantidad" class="form-control" value="<?php echo $cantidad ?? ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Unidad de medida:</label>
                                                    <select name="unidad" class="form-control">
                                                        <option value="">Seleccione</option>
                                                        <option value="Litros" <?php if(($unidad ?? '')=='Litros') echo 'selected'; ?>>Litros</option>
                                                        <option value="ml" <?php if(($unidad ?? '')=='ml') echo 'selected'; ?>>Mililitros</option>
                                                        <option value="kg" <?php if(($unidad ?? '')=='kg') echo 'selected'; ?>>Kilogramos</option>
                                                        <option value="g" <?php if(($unidad ?? '')=='g') echo 'selected'; ?>>Gramos</option>
                                                        <option value="Piezas" <?php if(($unidad ?? '')=='Piezas') echo 'selected'; ?>>Piezas</option>
                                                        <option value="Paquete" <?php if(($unidad ?? '')=='Paquete') echo 'selected'; ?>>Paquete</option>
                                                        <option value="Caja" <?php if(($unidad ?? '')=='Caja') echo 'selected'; ?>>Caja</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Fecha de ingreso:</label>
                                                    <input type="date" name="fecha_ingreso" class="form-control" value="<?php echo $fecha_ingreso;?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Ingredientes:</label>
                                                    <textarea name="ingredientes" class="form-control" rows="3" placeholder="Liste los ingredientes del producto"><?php echo $ingredientes ?? ''; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Propiedades:</label>
                                                    <textarea name="propiedades" class="form-control" rows="3" placeholder="Describa las propiedades y beneficios"><?php echo $propiedades ?? ''; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIN COLUMNA IZQUIERDA -->

                                    <!-- COLUMNA DERECHA: IMAGEN DEL PRODUCTO -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Imagen del producto</label>
                                            <input type="file" name="image" class="form-control" id="file">
                                            <input type="text" name="image_text" value="<?php echo $imagen; ?>" hidden>
                                            <br>
                                            <!-- CONTENEDOR DE IMAGEN ESTILIZADO -->
                                            <output id="list" style="display: flex; justify-content: center; align-items: center; padding: 10px; background: #f8f9fa; border-radius: 8px; min-height: 300px;">
                                                <?php if (!empty($imagen)): ?>
                                                    <img src="<?php echo $URL . '/almacen/img_productos/' . $imagen; ?>" 
                                                         style="max-width: 100%; max-height: 350px; object-fit: contain; border-radius: 4px;">
                                                <?php endif; ?>
                                            </output>

                                            <script>
                                                function archivo(evt) {
                                                    var files = evt.target.files; 
                                                    for (var i = 0, f; f = files[i]; i++) {
                                                        if (!f.type.match('image.*')) {
                                                            continue;
                                                        }
                                                        var reader = new FileReader();
                                                        reader.onload = (function (theFile) {
                                                            return function (e) {
                                                                document.getElementById("list").innerHTML = 
                                                                    ['<img src="', e.target.result, 
                                                                     '" style="max-width:100%; max-height:350px; object-fit:contain; border-radius:4px;">'].join('');
                                                            };
                                                        })(f);
                                                        reader.readAsDataURL(f);
                                                    }
                                                }
                                                document.getElementById('file').addEventListener('change', archivo, false);
                                            </script>
                                        </div>
                                    </div>
                                    <!-- FIN COLUMNA DERECHA -->
                                </div>
                                <!-- FIN FILA PRINCIPAL -->

                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Stock:</label>
                                            <input type="number" name="stock" class="form-control" value="<?php echo $stock;?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Stock mínimo:</label>
                                            <input type="number" name="stock_minimo" class="form-control" value="<?php echo $stock_minimo;?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Stock máximo:</label>
                                            <input type="number" name="stock_maximo" class="form-control" value="<?php echo $stock_maximo;?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Precio compra:</label>
                                            <input type="number" step="0.01" name="precio_compra" class="form-control" value="<?php echo $precio_compra;?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Precio venta:</label>
                                            <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?php echo $precio_venta;?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                        <button type="submit" class="btn btn-success">Actualizar producto</button>
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
<?php include('../layout/mensajes.php'); ?>
<?php include('../layout/parte2.php'); ?>