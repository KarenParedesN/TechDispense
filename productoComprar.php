<?php
require '../../config/database.php';
require '../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {

    header("Location: ../../View/modulos/inicioSesion.html");
    exit;
}

if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'TecNI.ecc-3534*');
}

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id_produ, nombre_produ, descripcion_produ, tamaño_produ, precio_produ, descuento_produ, foto_produ FROM productos WHERE stock_produ = 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TechDispense</title>

</head>
<body>


    <section class="container section-more-recipes">
        <h2>Prueba esta receta para alegrarte el día</h2>
        <p>Deléitate con creaciones sabrosas...</p>

        <div class="list-card-recipes">
            <?php foreach ($resultado as $row):
                $id = $row['id_produ'];
                $nombre = $row['nombre_produ'];
                $imagen = !empty($row['foto_produ']) ? '../../Source/img/' . $row['foto_produ'] : '../../Source/img/sinimagen.jpg';
                $precio = $row['precio_produ'];
                $descuento = (float) $row['descuento_produ'];
                $precioConDescuento = $precio;
                if ($descuento > 0) {
                    $precioConDescuento = $precio - ($precio * $descuento / 100);
                }
                $token = hash_hmac('sha1', $id, KEY_TOKEN);
            ?>
                <div class="card-recipe">
                    <div class="container-img">
                        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Producto <?php echo htmlspecialchars($nombre); ?>" />
                        <?php if ($descuento > 0): ?>
                            <span class="badge-descuento-img">-<?php echo $descuento; ?>%</span>
                        <?php endif; ?>
                        <a href="details.php?id=<?php echo $id; ?>&token=<?php echo $token; ?>" class="btn-favorite" title="Ver producto">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>
                    <div class="content">
                        <h3><?php echo htmlspecialchars($nombre); ?></h3>
                        <div class="price-info">
                            <?php if ($descuento > 0): ?>
                                <p class="price-descuento" style="margin-left: 30px;">
                                    $MXN <?php echo number_format($precioConDescuento, 2, '.', ','); ?>
                                    <span class="original-price">$MXN <?php echo number_format($precio, 2, '.', ','); ?></span>
                                </p>
                            <?php else: ?>
                                <p class="price-normal" style="font-weight: bold; color: black;">$MXN <?php echo number_format($precio, 2, '.', ','); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <button title="Agregar al carrito" disabled style="cursor:not-allowed; opacity:0.6;">
                                <i class="fa-solid fa-cart-shopping" style="margin-right: 0.5rem;"></i> Debes iniciar sesión
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>



</body>
</html>


</html>
