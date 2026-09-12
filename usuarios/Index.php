<?php
include('../app/config.php');
include('../layout/sesion.php');
include('../layout/parte1.php');
include('../app/controllers/usuarios/listado_de_usuarios.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Listado de usuarios</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Usuarios registrados</h3>

                            <div class="card-tools">
                                <button type="button"
                                        class="btn btn-tool"
                                        data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th><center>Nro</center></th>
                                    <th><center>Nombre</center></th>
                                    <th><center>Email</center></th>
                                    <th><center>Rol del Usuario</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                 $contador = 0;
                                  foreach ($usuarios_datos as $usuario_dato){
                                    
                                  $id_usuario = $usuario_dato['id_usuarios'];
                                  
                                ?>
                                <tr>
                                    <td><center><?php echo $contador + 1; ?></center></td>
                                    <td><?php echo $usuario_dato['nombres']; ?></td>
                                    <td><?php echo $usuario_dato['email']; ?></td>
                                    <td><center><?php echo $usuario_dato['rol']; ?></center></td>
                                    <td>
                                        <center>
                                            <div class="btn-group">
                                             <a href="show.php?id=<?php echo $id_usuario; ?>" class="btn btn-info">
                                                <i class="fa fa-eye"></i> Ver</a>
                                             <a href="update.php?id=<?php echo $id_usuario; ?>" type="button" class="btn btn-success">
                                                <i class="fa fa-pencil-alt"></i>Editar</a> 
                                             <a href="delete.php?id=<?php echo $id_usuario; ?>" type="button" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>Borrar</a
                                            </div>
                                        </center>
                                    </td>
                                </tr>
                                <?php
                                $contador++;
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th><center>Nro</center></th>
                                    <th><center>Nombre</center></th>
                                    <th><center>Email</center></th>
                                    <th><center>Rol del Usuario</center></th>
                                    <th><center>Acciones</center></th>
                                </tr>
                                </tfoot>
                            </table>
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
        $('#example1').DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Usuarios",
                "infoEmpty": "Mostrando 0 a 0 de 0 Usuarios",
                "infoFiltered": "(Filtrado de _MAX_ total Usuarios)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Usuarios",
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
            buttons: [{
    extend: 'collection',
    text: 'Reportes',
    orientation: 'landscape',
    buttons: [{
        text: 'Copiar',
        extend: 'copy',
    },{
        extend: 'pdf'
    },{
        extend: 'csv'
    },{
        extend: 'excel'
    },{
        text: 'Imprimir',
        extend: 'print'
    }
    ]
},
{
    extend: 'colvis',
    text: 'Visor de columnas',
    collectionLayout: 'fixed three-column'
}
],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>