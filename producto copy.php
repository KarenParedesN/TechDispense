<?php
require '../../config/database.php';
require '../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'TecNI.ecc-3534*');
}

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id_produ, nombre_produ, descripcion_produ, tamaño_produ, precio_produ, descuento_produ, foto_produ FROM productos WHERE stock_produ = 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

$usuarioLogueado = isset($_SESSION['usuario_id']); // true o false
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TechDispense</title>
    <link rel="stylesheet" href="../../View/css/styles.css" />
    <link rel="stylesheet" href="../../View/css/footer.css" />
    <link rel="stylesheet" href="../../View/css/precios.css" />
    <link rel="stylesheet" href="../../View/css/chatboot.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
</head>

<body>
	<header>
		<span class="logo" style="display: flex; flex-direction: column; align-items: center;">
			<img src="../../Source/img/WhatsApp_Image_2025-06-24_at_6.53.35_PM-removebg-preview (1).png"
				alt="TechDispense" style="height: 40px; border-radius: 12px;">
			<a href="">TechDispense</a>
		</span>



		<nav class="navigation-header">
			<a href="../../index.html"> Volver al Inicio</a>
			<a href="../../View/modulos/producto.html">Comprar el dispensador</a>
			<a href="../../View/modulos/funiona.html">Cómo funciona</a>

		</nav>

		<ul class="social-links">
			<li>
				<a href="../../View/modulos/inicioSesion.php">
					<i class="fa-solid fa-user"></i>
				</a>
			</li>
		
		</ul>

		<button class="btn-menu-responsive">
			<i class="fa-solid fa-bars-staggered"></i>
		</button>

		<div class="menu-mobile">
			<div class="btn-close">
				<i class="fa-solid fa-x"></i>
			</div>
			<span class="logo">
				<a href="/">TechDispense</a>
			</span>

			<nav class="navigation-mobile">
				<a href="../../index.html">Volver al Inicio</a>
				<a href="../../View/modulos/funiona.html">Cómo funciona</a>
				<a href="../../View/modulos/producto.html">Comprar el dispensador</a>
			</nav>

		</div>
	</header>
    <section class="container section-more-recipes">
        <h2>Prueba esta receta para alegrarte el día</h2>
        <p>Deléitate con creaciones sabrosas...</p>

        <?php if (!$usuarioLogueado): ?>
            <div class="alert alert-warning" role="alert">
                Para ver detalles y agregar productos al carrito, por favor <a href="../../View/modulos/inicioSesion.html">inicia sesión</a>.
            </div>
        <?php endif; ?>

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

                        <?php if ($usuarioLogueado): ?>
                            <a href="details.php?id=<?php echo $id; ?>&token=<?php echo $token; ?>" class="btn-favorite" title="Ver producto">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn-favorite" title="Debes iniciar sesión para ver detalles" disabled style="cursor:not-allowed; opacity:0.5;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        <?php endif; ?>
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
                            <?php if ($usuarioLogueado): ?>
                                <button title="Agregar al carrito">
                                    <i class="fa-solid fa-cart-shopping" style="margin-right: 0.5rem;"></i> Agregar al carrito
                                </button>
                            <?php else: ?>
                                <button title="Debes iniciar sesión para agregar productos" disabled style="cursor:not-allowed; opacity:0.5;">
                                    <i class="fa-solid fa-cart-shopping" style="margin-right: 0.5rem;"></i> Agregar al carrito
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer y scripts -->

</body>

</html>
