<?php
require '../../config/database.php';
require '../../config/config.php';

if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'TecNI.ecc-3534*');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../../View/modulos/cabecera.php';

$db = new Database();
$con = $db->conectar();

// Solo mostrar productos si hay sesión
$productos = [];
if (isset($_SESSION['usuario'])) {
    $sql = $con->prepare("SELECT id_produ, nombre_produ, descripcion_produ, tamaño_produ, precio_produ, descuento_produ, foto_produ FROM productos WHERE stock_produ = 1");
    $sql->execute();
    $productos = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TechDispense</title>
    <link rel="icon" href="/img/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../../View/css/styles.css" />
    <link rel="stylesheet" href="../../View/css/footer.css" />
    <link rel="stylesheet" href="../../View/css/precios.css" />
    <link rel="stylesheet" href="../../View/css/chatboot.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
</head>

<body>

<section class="container section-more-recipes">
    <h2>Dispensadores disponibles</h2>
    <p>Selecciona tu modelo ideal y agrégalo al carrito.</p>

    <div class="list-card-recipes">
        <?php if (!isset($_SESSION['usuario'])): ?>
<div class="alert alert-danger text-center shadow rounded-3 py-3 px-4 mt-4" style="width: 100vw; position: fixed; left: 0; top: 0; z-index: 1050;">
  <strong>Atención:</strong> Debes 
  <a href="../../View/modulos/inicioSesion.php" class="text-decoration-underline fw-bold text-dark">
    iniciar sesión
  </a> 
  para ver los productos.
</div>

<br><br><br><br><br><br><br><br><br>

        <?php else: ?>
            <?php foreach ($productos as $row):
                $id = $row['id_produ'];
                $nombre = $row['nombre_produ'];
                $imagen = !empty($row['foto_produ']) ? '../../Source/img/' . $row['foto_produ'] : '../../Source/img/sinimagen.jpg';
                $precio = $row['precio_produ'];
                $descuento = (float)$row['descuento_produ'];
                $precioConDescuento = $precio - ($precio * $descuento / 100);
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
                                <p class="price-descuento">
                                    $MXN <?php echo number_format($precioConDescuento, 2); ?>
                                    <span class="original-price">$<?php echo number_format($precio, 2); ?></span>
                                </p>
                            <?php else: ?>
                                <p class="price-normal" style="font-weight: bold; color: black;">$MXN <?php echo number_format($precio, 2); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <button class="btn btn-add-to-cart mb-3"
                                    onclick="addProducto(<?php echo $id; ?>, '<?php echo $token; ?>')">
                                <i class="fa-solid fa-cart-plus me-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<script>
function addProducto(id, token) {
    let url = '../../models/carrito.php';
    let formData = new FormData();
    formData.append('id', id);
    formData.append('token', token);

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            alert('Producto agregado al carrito');
            document.getElementById("num_cart").innerText = data.numero;
        } else {
            alert('No se pudo agregar el producto al carrito');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al agregar el producto al carrito');
    });
}
</script>


<footer class="footer-container">
		<div class="footer-top">
		</div>

		<div class="footer-bottom">
			<div></div>
			<p class="footer-copyright">
				&copy;2024 <span style="color: rgb(255, 174, 143);">TechDispense</span>. Diseñado con amor para marcas
				que quieren crecer.
			</p>
			<ul class="footer-social-links">
				<li>
					<a
						href="https://www.facebook.com/people/TechDispense-Dispensadores/pfbid02d6hs26zpvTh9NDthMHxT6PuSg2rc7343TxhEnTPivGwn7GfXcmUurYjGoNBhq5yul/?rdid=LSBmSX0IG0erdEmb&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F19MEckERRv%2F">
						<i class="fa-brands fa-facebook"></i>
					</a>
				</li>
				<li>
					<a href="https://www.instagram.com/techdispensedispensadores/#">
						<i class="fa-brands fa-instagram"></i>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="fa-brands fa-whatsapp"></i>
					</a>
				</li>
			</ul>
		</div>
	</footer>


	<script src="../../View/js/chatboot.js"></script>
	<script src="../../View/js/menu.js"></script>
</body>

</html>