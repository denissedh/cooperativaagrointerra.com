<?php
// Include the main TCPDF library
require_once('../app/TCPDF-main/tcpdf.php');
include('../app/config.php');
include('../app/controllers/ventas/literal.php');

session_start();

$nombres_sesion = "Usuario"; 

if (isset($_SESSION['sesion_email'])) {

    $email_sesion = $_SESSION['sesion_email'];

    $sql = "SELECT us.id_usuarios as id_usuarios, us.nombres as nombres, us.email as email, rol.rol as rol
            FROM tb_usuarios as us INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol WHERE email='$email_sesion'";

    $query = $pdo->prepare($sql);
    $query->execute();

    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $usuario) {
        $id_usuario_sesion = $usuario['id_usuarios'];
        $nombres_sesion = $usuario['nombres'];
        $rol_sesion = $usuario['rol'];
    }

} else {
    echo "no existe sesion";
    header("Location: ".$URL."/login");
    exit();
}

$id_venta_get = isset($_GET['id_venta']) ? $_GET['id_venta'] : '';

$sql_ventas = "SELECT 
    ve.id_venta as id_venta,
    ve.nro_venta as nro_venta,
    ve.id_cliente as id_cliente,
    ve.total_pagado as total_pagado,
    ve.fyh_creacion as fyh_creacion,
    ve.fyh_actualizacon as fyh_actualizacon,
    ve.estado as estado,
    cli.nombre_cliente as nombre_cliente, 
    cli.direccion_cliente as direccion_cliente,
    cli.celular_cliente as celular_cliente,
    cli.email_cliente as email_cliente,
    fac.nro_factura as nro_factura
FROM tb_ventas as ve 
INNER JOIN tb_clientes as cli ON cli.id_cliente = ve.id_cliente 
LEFT JOIN tb_facturas as fac ON fac.id_venta = ve.id_venta
WHERE ve.id_venta = :id_venta";

$query_ventas = $pdo->prepare($sql_ventas);
$query_ventas->execute([':id_venta' => $id_venta_get]);
$ventas_datos = $query_ventas->fetchAll(PDO::FETCH_ASSOC);

$nro_venta_get = '';
$nro_factura_mostrar = '';
$fyh_creacion = date('Y-m-d H:i:s');
$nombre_cliente = '';
$direccion_cliente = '';
$celular_cliente = '';
$email_cliente = '';
$total_pagado = 0;
$id_cliente = '';
$fyh_actualizacon = '';
$estado = '';

foreach ($ventas_datos as $ventas_dato) {
    $id_venta_get       = $ventas_dato['id_venta'];
    $nro_venta_get      = $ventas_dato['nro_venta'];
    $id_cliente         = $ventas_dato['id_cliente'];
    $fyh_creacion       = $ventas_dato['fyh_creacion'];
    $fyh_actualizacon   = $ventas_dato['fyh_actualizacon'];
    $estado             = $ventas_dato['estado'];
    $nombre_cliente     = $ventas_dato['nombre_cliente'];
    $direccion_cliente  = $ventas_dato['direccion_cliente'];
    $celular_cliente    = $ventas_dato['celular_cliente'];
    $email_cliente      = $ventas_dato['email_cliente'];
    $total_pagado       = $ventas_dato['total_pagado'];
    // Asignar el número de factura registrado o formatear idéntico al módulo
    $nro_factura_mostrar = !empty($ventas_dato['nro_factura']) ? $ventas_dato['nro_factura'] : 'FAC-' . str_pad($id_venta_get, 6, '0', STR_PAD_LEFT);
}

$monto_literal = numtoletras($total_pagado);

$fecha = date("d/m/Y", strtotime($fyh_creacion));

// Crear nuevo documento PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215,279), true, 'UTF-8', false);


$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Sistema de Ventas Agrointerra');
$pdf->setTitle('Factura de Venta');
$pdf->setSubject('Factura');
$pdf->setKeywords('TCPDF, PDF, factura, ventas');

// Quitar encabezado y pie por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Fuente y márgenes
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setMargins(15, 15, 15);
$pdf->setAutoPageBreak(true, 5);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Idioma
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
    require_once(dirname(__FILE__).'/lang/spa.php');
    $pdf->setLanguageArray($l);
}

// Fuente principal
$pdf->setFont('Helvetica', '', 12);

// Agregar página
$pdf->AddPage();


$html = '
<table border="0" style="font-size: 10px">
<tr>
    <td style="text-align: center;width: 230px">
        <img src="../public/images/Agrointerra.png.jpeg" width="80px" alt=""><br><br>
        <b>SISTEMA DE VENTAS AGROINTERRA</b> <br>
        Calle Hortalizas #3935, Colonia San Juan <br>
        C.P. 31820 Ascensión, Chihuahua<br>
        636 101 7629
    </td>
    <td style="width: 150px"></td>
    <td style="font-size: 16px;width: 290px"><br><br><br>
        <b>NIT: </b>10001099920 <br>
        <b>Nro factura:</b> '.$nro_factura_mostrar.' <br>
        <b>Nro de autorización: </b>100020029930
        <p style="text-align: center"><B>ORIGINAL</B></p>
    </td>
</tr>
</table>

<p style="text-align: center;font-size: 25px"><b>FACTURA</b></p>

<div style="border: 1px solid #000000">
<table border="0" cellpadding="6px">
<tr>
    <td><b>Fecha:</b> '.$fecha.'</td>
    <td colspan="2"></td>
</tr>
<tr>
    <td colspan="3"><b>Señor(es): </b>'.$nombre_cliente.' </td>
</tr>
<tr>
    <td colspan="3"><b>Dirección: </b>'.$direccion_cliente.' </td>
</tr>
<tr>
    <td><b>Celular: </b>'.$celular_cliente.' </td>
    <td colspan="2"><b>Correo: </b>'.$email_cliente.' </td>
</tr>
</table>
</div>
<br>';

$contador_de_carrito = 0;
$cantidad_total = 0;
$precio_unitario_total = 0;
$precio_total = 0;

$sql_carrito = "SELECT 
    carr.*,
    pro.nombre as nombre_producto,
    pro.codigo,
    pro.beneficios,
    pro.stock,
    pro.stock_minimo,
    pro.stock_maximo,
    pro.precio_compra,
    pro.precio_venta,
    pro.fecha_ingreso,
    pro.id_producto
FROM tb_carrito AS carr 
INNER JOIN tb_almacen as pro ON carr.id_producto = pro.id_producto 
WHERE nro_venta = :nro_venta 
ORDER BY id_carrito ASC";

$query_carrito = $pdo->prepare($sql_carrito);
$query_carrito->execute([':nro_venta' => $nro_venta_get]);
$carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

$html .= '
<table border="1" cellpadding="5" cellspacing="0" style="font-size: 11px; width:100%; margin-top:8px;">
<tr style="text-align: center;background-color: #f0f0f0;font-weight:bold;">
    <th width="6%">Nro</th>
    <th width="12%">Código</th>
    <th width="22%">Producto</th>
    <th width="30%">Beneficios</th>
    <th width="8%">Cant.</th>
    <th width="12%">Precio Unit.</th>
    <th width="12%">Subtotal</th>
</tr>
';

foreach ($carrito_datos as $carrito_dato) {
    $contador_de_carrito++;
    $cantidad_total += $carrito_dato['cantidad'];
    $precio_unitario_total += floatval($carrito_dato['precio_venta']);
    $subtotal = $carrito_dato['cantidad'] * $carrito_dato['precio_venta'];
    $precio_total += $subtotal;

    $codigo_producto     = $carrito_dato['codigo'];
    $beneficios_producto = !empty($carrito_dato['beneficios']) ? $carrito_dato['beneficios'] : 'Sin beneficios registrados';

    $html .= '
    <tr>
        <td style="text-align: center">'.$contador_de_carrito.'</td>
        <td style="text-align: center">'.$codigo_producto.'</td>
        <td>'.$carrito_dato['nombre_producto'].'</td>
        <td>'.$beneficios_producto.'</td>
        <td style="text-align: center">'.$carrito_dato['cantidad'].'</td>
        <td style="text-align: center">$ '.number_format($carrito_dato['precio_venta'], 2, '.', ',').'</td>
        <td style="text-align: center">$ '.number_format($subtotal, 2, '.', ',').'</td>
    </tr>
    ';
}

$html .= '
<tr>
    <td colspan="4" style="text-align: right;background-color: #d6d6d6"><b>Totales:</b></td>
    <td style="text-align: center;background-color: #d6d6d6"><b>'.$cantidad_total.'</b></td>
    <td style="text-align: center;background-color: #d6d6d6"><b>$ '.number_format($precio_unitario_total, 2, '.', ',').'</b></td>
    <td style="text-align: center;background-color: #d6d6d6"><b>$ '.number_format($precio_total, 2, '.', ',').'</b></td>
</tr>
</table>

<p style="text-align: right; font-size: 13px; margin-top:10px;">
    <b>Monto Total: </b> <span style="font-size:15px;">$ '.number_format($precio_total, 2, '.', ',').'</span>
</p>
<p>
<b>Son: </b>'.$monto_literal.'
</p>
<br>--------------------------------------------------------------------------------<br>
<b>USUARIO:</b> '.$nombres_sesion.' <br>

<p style="text-align: left">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS,</p>
<p style="text-align: left">EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LA LEY"</p>
<p style="text-align: center"><b>GRACIAS POR SU PREFERENCIA</b></p>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Código QR
$style = array(
    'border' => 0,
    'vpadding' => '3',
    'hpadding' => '3',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false,
    'module_width' => 1,
    'module_height' => 1
);

$QR = 'Factura Nro: '.$nro_factura_mostrar.
       ' | Realizada por AGROINTERRA al cliente '.$nombre_cliente.
       ' Dirección: '.$direccion_cliente.
       ' Celular: '.$celular_cliente.
       ' Correo: '.$email_cliente.
       ' Generada el: '.$fecha.
       ' Monto total: $'.number_format($precio_total, 2, '.', ',');

$pdf->write2DBarcode($QR, 'QRCODE,L', 165, 230, 45, 45, $style);

// Salida del PDF
$pdf->Output('Factura_'.$nro_factura_mostrar.'.pdf', 'I');
?>