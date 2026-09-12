<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/almacen/listado_de_productos.php');
include ('../app/controllers/proveedores/listado_de_proveedores.php');
include ('../app/controllers/compras/listado_de_compras.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de una nueva compra</h1>
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
                                       <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                           <i class="fas fa-minus"></i>
                                       </button>
                                   </div>
                               </div>
                               <div class="card-body" style="display: block;">
                                   <div style="display: flex">
                                       <h5>Datos del producto </h5>
                                       <div style="width: 20px"></div>
                                       <button type="button" class="btn btn-primary" data-toggle="modal"
                                               data-target="#modal-buscar_producto">
                                           <i class="fa fa-search"></i> Buscar producto
                                       </button>
                                       <!-- Modal Buscar Producto -->
                                       <div class="modal fade" id="modal-buscar_producto">
                                           <div class="modal-dialog modal-lg">
                                               <div class="modal-content">
                                                   <div class="modal-header" style="background-color: #1d36b6;color: white">
                                                       <h4 class="modal-title">Búsqueda del producto</h4>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                           <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="table table-responsive">
                                                           <table id="example1" class="table table-bordered table-striped table-sm">
                                                               <thead>
                                                                   <tr>
                                                                       <th><center>Nro</center></th>
                                                                       <th><center>Seleccionar</center></th>
                                                                       <th><center>Código</center></th>
                                                                       <th><center>Nombre</center></th>
                                                                       <th><center>Categoría</center></th>
                                                                       <th><center>Imagen</center></th>
                                                                       <th><center>Stock</center></th>
                                                                       <th><center>Precio compra</center></th>
                                                                       <th><center>Precio venta</center></th>
                                                                       <th><center>Fecha ingreso</center></th>
                                                                   </tr>
                                                               </thead>
                                                               <tbody>
                                                                   <?php
                                                                   $contador = 0;
                                                                   foreach ($productos_datos as $productos_dato):
                                                                       $contador++;
                                                                       $id_producto = $productos_dato['id_producto']; ?>
                                                                       <tr>
                                                                           <td><?php echo $contador; ?></td>
                                                                           <td>
                                                                               <button type="button" class="btn btn-info btn-seleccionar"
                                                                                   data-id="<?php echo $id_producto; ?>"
                                                                                   data-codigo="<?php echo $productos_dato['codigo']; ?>"
                                                                                   data-nombre="<?php echo $productos_dato['nombre']; ?>"
                                                                                   data-nombre-categoria="<?php echo $productos_dato['nombre_categoria']; ?>"
                                                                                   data-stock="<?php echo $productos_dato['stock']; ?>"
                                                                                   data-stock-minimo="<?php echo $productos_dato['stock_minimo']; ?>"
                                                                                   data-stock-maximo="<?php echo $productos_dato['stock_maximo']; ?>"
                                                                                   data-precio-compra="<?php echo $productos_dato['precio_compra']; ?>"
                                                                                   data-precio-venta="<?php echo $productos_dato['precio_venta']; ?>"
                                                                                   data-fecha-ingreso="<?php echo $productos_dato['fecha_ingreso']; ?>"
                                                                                   data-imagen="<?php echo $URL.'/almacen/img_productos/'.$productos_dato['imagen']; ?>"
                                                                                   data-cantidad="<?php echo $productos_dato['cantidad']; ?>"
                                                                                   data-unidad="<?php echo $productos_dato['unidad']; ?>"
                                                                                   data-ingredientes="<?php echo $productos_dato['ingredientes']; ?>"
                                                                                   data-propiedades="<?php echo $productos_dato['propiedades']; ?>"
                                                                                   data-id-usuario="<?php echo $productos_dato['id_usuarios']; ?>"
                                                                                   data-nombre-usuario="<?php echo $productos_dato['nombres']; ?>">
                                                                                   Seleccionar
                                                                               </button>
                                                                           </td>
                                                                           <td><?php echo $productos_dato['codigo']; ?></td>
                                                                           <td><?php echo $productos_dato['nombre']; ?></td>
                                                                           <td><?php echo $productos_dato['nombre_categoria']; ?></td>
                                                                           <td><img src="<?php echo $URL."/almacen/img_productos/".$productos_dato['imagen']; ?>" width="50" alt=""></td>
                                                                           <td><?php echo $productos_dato['stock']; ?></td>
                                                                           <td><?php echo $productos_dato['precio_compra']; ?></td>
                                                                           <td><?php echo $productos_dato['precio_venta']; ?></td>
                                                                           <td><?php echo $productos_dato['fecha_ingreso']; ?></td>
                                                                       </tr>
                                                                   <?php endforeach; ?>
                                                               </tbody>
                                                           </table>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                       <!-- /.modal -->
                                   </div>
                                   <hr>
                                   <div class="container-fluid" style="font-size: 12px">
                                       <div class="row">
                                           <input type="hidden" id="id_producto">
                                           <div class="col-md-3">
                                               <div class="form-group">
                                                   <label>Código:</label>
                                                   <input type="text" class="form-control" id="codigo" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-3">
                                               <div class="form-group">
                                                   <label>Categoría:</label>
                                                   <input type="text" class="form-control" id="nombre_categoria" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-3">
                                               <div class="form-group">
                                                   <label>Nombre del producto:</label>
                                                   <input type="text" class="form-control" id="nombre_producto" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-3">
                                               <div class="form-group">
                                                   <label>Usuario:</label>
                                                   <input type="text" class="form-control" id="nombres" disabled>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="row">
                                           <div class="col-md-2">
                                               <div class="form-group">
                                                   <label>Stock:</label>
                                                   <input type="number" class="form-control" id="stock" style="background-color: #fff819" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-2">
                                               <div class="form-group">
                                                   <label>Stock mínimo:</label>
                                                   <input type="number" class="form-control" id="stock_minimo" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-2">
                                               <div class="form-group">
                                                   <label>Stock máximo:</label>
                                                   <input type="number" class="form-control" id="stock_maximo" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-3">
                                               <div class="form-group">
                                                   <label>Cantidad / Unidad:</label>
                                                   <input type="text" class="form-control" id="cantidad_unidad" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-3">
                                               <div class="form-group">
                                                   <label>Fecha ingreso:</label>
                                                   <input type="date" class="form-control" id="fecha_ingreso" disabled>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="row">
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Precio compra:</label>
                                                   <input type="text" class="form-control" id="precio_compra" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Precio venta:</label>
                                                   <input type="text" class="form-control" id="precio_venta" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Imagen:</label>
                                                   <center><img id="img_producto" width="40%" alt="Sin imagen"></center>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="row">
                                           <div class="col-md-6">
                                               <div class="form-group">
                                                   <label>Ingredientes:</label>
                                                   <textarea class="form-control" id="ingredientes" rows="2" disabled></textarea>
                                               </div>
                                           </div>
                                           <div class="col-md-6">
                                               <div class="form-group">
                                                   <label>Propiedades:</label>
                                                   <textarea class="form-control" id="propiedades" rows="2" disabled></textarea>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <hr>
                                   <!-- SECCIÓN PROVEEDOR -->
                                   <div style="display: flex">
                                       <h5>Datos del proveedor </h5>
                                       <div style="width: 20px"></div>
                                       <button type="button" class="btn btn-primary" data-toggle="modal"
                                               data-target="#modal-buscar_proveedor">
                                           <i class="fa fa-search"></i> Buscar proveedor
                                       </button>
                                       <!-- Modal Buscar Proveedor -->
                                       <div class="modal fade" id="modal-buscar_proveedor">
                                           <div class="modal-dialog modal-lg">
                                               <div class="modal-content">
                                                   <div class="modal-header" style="background-color: #1d36b6;color: white">
                                                       <h4 class="modal-title">Búsqueda de proveedor</h4>
                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                           <span aria-hidden="true">&times;</span>
                                                       </button>
                                                   </div>
                                                   <div class="modal-body">
                                                       <div class="table table-responsive">
                                                           <table id="example2" class="table table-bordered table-striped table-sm">
                                                               <thead>
                                                                   <tr>
                                                                       <th><center>Nro</center></th>
                                                                       <th><center>Seleccionar</center></th>
                                                                       <th><center>Nombre proveedor</center></th>
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
                                                                   foreach ($proveedores_datos as $proveedores_dato):
                                                                       $contador++; ?>
                                                                       <tr>
                                                                           <td><center><?php echo $contador; ?></center></td>
                                                                           <td>
                                                                               <button type="button" class="btn btn-info btn-seleccionar-proveedor"
                                                                                   data-id="<?php echo $proveedores_dato['id_proveedor']; ?>"
                                                                                   data-nombre="<?php echo $proveedores_dato['nombre_proveedor']; ?>"
                                                                                   data-celular="<?php echo $proveedores_dato['celular']; ?>"
                                                                                   data-telefono="<?php echo $proveedores_dato['telefono']; ?>"
                                                                                   data-empresa="<?php echo $proveedores_dato['empresa']; ?>"
                                                                                   data-email="<?php echo $proveedores_dato['email']; ?>"
                                                                                   data-direccion="<?php echo $proveedores_dato['direccion']; ?>">
                                                                                   Seleccionar
                                                                               </button>
                                                                           </td>
                                                                           <td><?php echo $proveedores_dato['nombre_proveedor']; ?></td>
                                                                           <td>
                                                                               <a href="https://wa.me/52<?php echo $proveedores_dato['celular']; ?>" target="_blank" class="btn btn-success btn-sm">
                                                                                   <i class="fa fa-whatsapp"></i> <?php echo $proveedores_dato['celular']; ?>
                                                                               </a>
                                                                           </td>
                                                                           <td><?php echo $proveedores_dato['telefono']; ?></td>
                                                                           <td><?php echo $proveedores_dato['empresa']; ?></td>
                                                                           <td><?php echo $proveedores_dato['email']; ?></td>
                                                                           <td><?php echo $proveedores_dato['direccion']; ?></td>
                                                                       </tr>
                                                                   <?php endforeach; ?>
                                                               </tbody>
                                                           </table>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <hr>
                                   <div class="container-fluid" style="font-size: 12px">
                                       <div class="row">
                                           <input type="hidden" id="id_proveedor">
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Nombre del proveedor:</label>
                                                   <input type="text" id="nombre_proveedor" class="form-control" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Celular:</label>
                                                   <input type="text" id="celular" class="form-control" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Teléfono:</label>
                                                   <input type="text" id="telefono" class="form-control" disabled>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="row">
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Empresa:</label>
                                                   <input type="text" id="empresa" class="form-control" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Email:</label>
                                                   <input type="email" id="email" class="form-control" disabled>
                                               </div>
                                           </div>
                                           <div class="col-md-4">
                                               <div class="form-group">
                                                   <label>Dirección:</label>
                                                   <textarea id="direccion" class="form-control" rows="2" disabled></textarea>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <!-- COLUMNA DETALLE COMPRA -->
               <div class="col-md-3">
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
                           <?php
                           $contador_de_compras = 1;
                           foreach ($compras_datos as $compras_dato) {
                               $contador_de_compras++;
                           }
                           $fecha_hoy = date("Y-m-d");
                           ?>
                           <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label>Número de compra</label>
                                       <input type="text" value="<?php echo $contador_de_compras; ?>" class="form-control" style="text-align:center" disabled>
                                       <input type="hidden" id="nro_compra" value="<?php echo $contador_de_compras; ?>">
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label>Fecha de compra</label>
                                       <input type="date" class="form-control" id="fecha_compra" value="<?php echo $fecha_hoy; ?>">
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label>Comprobante</label>
                                       <input type="text" class="form-control" id="comprobante" placeholder="Factura / Boleta">
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label>Precio de compra unitario</label>
                                       <input type="text" class="form-control" id="precio_compra_controlador">
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="form-group">
                                       <label>Stock actual</label>
                                       <input type="number" id="stock_actual" class="form-control" style="background-color: #fff819; text-align:center" disabled>
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="form-group">
                                       <label>Stock total</label>
                                       <input type="number" id="stock_total" class="form-control" style="text-align:center" disabled>
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label>Cantidad comprada</label>
                                       <input type="number" id="cantidad" class="form-control" style="text-align:center" min="1" value="1">
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label>Usuario registra</label>
                                       <input type="text" class="form-control" value="<?php echo $email_sesion; ?>" disabled>
                                   </div>
                               </div>
                           </div>
                           <hr>
                           <div class="form-group">
                               <button type="button" class="btn btn-primary btn-block" id="btn_guardar_compra">
                                   <i class="fa fa-save"></i> Guardar compra
                               </button>
                           </div>
                           <div id="respuesta_create"></div>
                       </div>
                   </div>
               </div>
           </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>
<script>
// 🔘 BOTÓN SELECCIONAR PRODUCTO
$(document).on('click', '.btn-seleccionar', function(){
    const btn = $(this);
    $('#id_producto').val(btn.data('id'));
    $('#codigo').val(btn.data('codigo'));
    $('#nombre_producto').val(btn.data('nombre'));
    $('#nombre_categoria').val(btn.data('nombre-categoria'));
    $('#stock').val(btn.data('stock'));
    $('#stock_actual').val(btn.data('stock'));
    $('#stock_minimo').val(btn.data('stock-minimo'));
    $('#stock_maximo').val(btn.data('stock-maximo'));
    $('#precio_compra').val(btn.data('precio-compra'));
    $('#precio_compra_controlador').val(btn.data('precio-compra'));
    $('#precio_venta').val(btn.data('precio-venta'));
    $('#fecha_ingreso').val(btn.data('fecha-ingreso'));
    $('#img_producto').attr('src', btn.data('imagen'));
    $('#cantidad_unidad').val(btn.data('cantidad') + ' ' + btn.data('unidad'));
    $('#ingredientes').val(btn.data('ingredientes'));
    $('#propiedades').val(btn.data('propiedades'));
    $('#nombres').val(btn.data('nombre-usuario')); // ✅ NOMBRE DE USUARIO CORREGIDO
    // Cerrar modal
    $('#modal-buscar_producto').modal('hide');
});
// 🔘 BOTÓN SELECCIONAR PROVEEDOR
$(document).on('click', '.btn-seleccionar-proveedor', function(){
    const btn = $(this);
    $('#id_proveedor').val(btn.data('id'));
    $('#nombre_proveedor').val(btn.data('nombre'));
    $('#celular').val(btn.data('celular'));
    $('#telefono').val(btn.data('telefono'));
    $('#empresa').val(btn.data('empresa'));
    $('#email').val(btn.data('email'));
    $('#direccion').val(btn.data('direccion'));
    $('#modal-buscar_proveedor').modal('hide');
});
$('#cantidad').on('keyup change', function(){
    const actual = parseInt($('#stock_actual').val()) || 0;
    const compra = parseInt($(this).val()) || 0;
    $('#stock_total').val(actual + compra);
});
$('#btn_guardar_compra').click(function(){
    const campos = [
        {sel: '#id_producto', msj: 'Debe seleccionar un producto'},
        {sel: '#fecha_compra', msj: 'Ingrese la fecha de compra'},
        {sel: '#comprobante', msj: 'Ingrese el comprobante'},
        {sel: '#precio_compra_controlador', msj: 'Ingrese el precio de compra'},
        {sel: '#cantidad', msj: 'Ingrese la cantidad'}
    ];
    let valido = true;
    for(const c of campos){
        if(!$(c.sel).val()){
            alert(c.msj);
            $(c.sel).focus();
            valido = false;
            break;
        }
    }
    if(!valido) return;
    const datos = {
        id_producto: $('#id_producto').val(),
        nro_compra: $('#nro_compra').val(),
        fecha_compra: $('#fecha_compra').val(),
        id_proveedor: $('#id_proveedor').val(),
        comprobante: $('#comprobante').val(),
        id_usuarios: '<?php echo $id_usuario_sesion; ?>',
        precio_compra: $('#precio_compra_controlador').val(),
        cantidad: $('#cantidad').val(),
        stock_total: $('#stock_total').val()
    };
    $.get('../app/controllers/compras/create.php', datos, function(resp){
        $('#respuesta_create').html(resp);
    });
});
// 📚 DATATABLES
$(function(){
    $("#example1").DataTable({
        pageLength: 5,
        language: {
            emptyTable: "No hay información",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Productos",
            infoEmpty: "Mostrando 0 a 0 de 0 Productos",
            infoFiltered: "(Filtrado de _MAX_ total Productos)",
            lengthMenu: "Mostrar _MENU_ Productos",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscador:",
            zeroRecords: "Sin resultados encontrados",
            paginate: {first:"Primero", last:"Último", next:"Siguiente", previous:"Anterior"}
        },
        responsive: true, lengthChange: true, autoWidth: false
    });
    $("#example2").DataTable({
        pageLength: 5,
        language: {
            emptyTable: "No hay información",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Proveedores",
            infoEmpty: "Mostrando 0 a 0 de 0 Proveedores",
            infoFiltered: "(Filtrado de _MAX_ total Proveedores)",
            lengthMenu: "Mostrar _MENU_ Proveedores",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscador:",
            zeroRecords: "Sin resultados encontrados",
            paginate: {first:"Primero", last:"Último", next:"Siguiente", previous:"Anterior"}
        },
        responsive: true, lengthChange: true, autoWidth: false
    });
});
</script>
<?php include ('../layout/parte2.php'); ?>