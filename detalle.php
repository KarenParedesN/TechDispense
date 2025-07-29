<?php
require '../../config/database.php';
require '../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'] ?? '';
$token = $_GET['token'] ?? '';

if ($id === '' || $token === '') {
    echo 'Error: ID o Token no válidos';
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
if ($token !== $token_tmp) {
    echo 'Token inválido';
    exit;
}

$sql = $con->prepare("SELECT dis_id, dis_nombre, dis_stock, dis_precio, dis_descripcion, dis_descuento, dis_tamaño, dis_imagen FROM dispensadores WHERE dis_id = ? LIMIT 1");
$sql->execute([$id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo 'Dispensador no encontrado';
    exit;
}

$dis_id = $row['dis_id'];
$nombre = $row['dis_nombre'];
$precio = $row['dis_precio'];
$descuento = $row['dis_descuento'];
$descripcion = $row['dis_descripcion'];
$tamano = $row['dis_tamaño'];
$imagen = !empty($row['dis_imagen']) ? '../../Source/img/' . $row['dis_imagen'] : '../../Source/img/sinimagen.jpg';

$sql = $con->prepare("SELECT usuario, comentario, puntuacion, fecha FROM comentarios_disp WHERE producto_id = ? ORDER BY fecha DESC");
$sql->execute([$dis_id]);
$comentarios = $sql->fetchAll(PDO::FETCH_ASSOC);
$promedio = count($comentarios) ? array_sum(array_column($comentarios, 'puntuacion')) / count($comentarios) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo htmlspecialchars($nombre); ?> - TechDispense</title>
	<link rel="icon" href="/img/favicon.ico">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
	<link rel="stylesheet" href="../../View/css/styles.css" />
    <link rel="stylesheet" href="../../View/css/footer.css" />
    <link rel="stylesheet" href="../../View/css/deilist.css" />
    <style>
        .badge-descuento-img {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #af0508ff, #f9d423);
            color: #fff;
            font-weight: bold;
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            z-index: 1;
        }
    </style>
</head>

<body>
	<header>
		<span class="logo" style="display: flex; flex-direction: column; align-items: center;">
			<img src="../../Source/img/WhatsApp_Image_2025-06-24_at_6.53.35_PM-removebg-preview (1).png" alt="TechDispense"
				style="height: 40px; border-radius: 12px;">
			<a href="">TechDispense</a>
		</span>

		<nav class="navigation-header">
			<a href="../../Controller/cerrar_sesion.php">Salir</a>
			<a href="../../View/modulos/productosCompra.php">Seguir Comprando</a>
		</nav>

		<ul class="social-links">
			<li><a href="../../View/modulos/inicioSesion.php"><i class="fa-solid fa-user"></i></a></li>
			<li><a href="https://www.facebook.com/people/TechDispense-Dispensadores/..."><i class="fa-brands fa-facebook"></i></a></li>
			<li><a href="https://www.instagram.com/techdispensedispensadores/"><i class="fa-brands fa-instagram"></i></a></li>
			<li><a href="../../View/modulos/compras.php"><i class="fa-solid fa-cart-shopping"></i><span id="num_cart"></span></a></li>
		</ul>

		<button class="btn-menu-responsive"><i class="fa-solid fa-bars-staggered"></i></button>

		<div class="menu-mobile">
			<div class="btn-close"><i class="fa-solid fa-x"></i></div>
			<span class="logo"><a href="/">TechDispense</a></span>
			<nav class="navigation-mobile">
				<a href="../../Controller/cerrar_sesion.php">Salir</a>
				<a href="../../View/modulos/producto.php">Seguir Comprando</a>
			</nav>
		</div>
	</header>

  <div class="chanel-product-page">
    <div class="chanel-product-container">
      <div class="chanel-product-image">
        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($nombre); ?>">
      </div>
      <div class="chanel-product-details">
        <div class="chanel-title"><?php echo htmlspecialchars($nombre); ?></div>
        <div class="chanel-description"><?php echo htmlspecialchars($descripcion); ?></div>
        <div class="chanel-ref">ID ref: <?php echo $dis_id; ?></div>
        
        <?php if ($descuento > 0): ?>
          <div class="chanel-price">
            <p style="font-weight: 600; margin-bottom: 5px;">Precio de renta por una Semana</p>
            <div>
                <span style="text-decoration: line-through; color: #888; font-size: 1rem;">
                    $<?php echo number_format($precio, 2); ?>
                </span>
                <span style="margin-left: 12px; color: #d32f2f; font-weight: 700; font-size: 1.25rem;">
                    $<?php echo number_format($precio - ($precio * $descuento / 100), 2); ?>
                </span>
            </div>
          </div>
          <div class="chanel-discount">Descuento: <?php echo $descuento; ?>% OFF</div>
        <?php else: ?>
          <div class="chanel-price">$<?php echo number_format($precio, 2); ?></div>
        <?php endif; ?>

        <div class="chanel-talla">Tamaño: <?php echo htmlspecialchars($tamano); ?></div>

        <button class="chanel-add-button" onclick="addProducto(<?php echo $dis_id; ?>, '<?php echo $token_tmp; ?>')">AGREGAR AL CARRITO</button>
        <div class="chanel-note">Instalación gratuita incluida en la renta.</div>
      </div>
    </div>

    <div class="chanel-comments">
      <h3>Comentarios</h3>

      <?php if ($comentarios): ?>
        <p><strong>Promedio:</strong>
        <?php for ($i = 0; $i < 5; $i++): ?>
          <span style="color:<?php echo $i < round($promedio) ? 'gold' : '#ccc'; ?>">★</span>
        <?php endfor; ?>
        </p>
        <?php foreach ($comentarios as $c): ?>
          <div class="chanel-simulated-comment">
            <div class="author"><?php echo htmlspecialchars($c['usuario']); ?> - <small><?php echo $c['fecha']; ?></small></div>
            <div class="stars">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <span><?php echo $i < $c['puntuacion'] ? '★' : '☆'; ?></span>
              <?php endfor; ?>
            </div>
            <div class="text"><?php echo htmlspecialchars($c['comentario']); ?></div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay comentarios aún.</p>
      <?php endif; ?>

      <?php if (isset($_SESSION['usuario'])): ?>
        <form action="../../models/comentarios_disp.php" method="POST" class="mt-4">
          <input type="hidden" name="producto_id" value="<?php echo $dis_id; ?>">
          <input type="hidden" name="usuario" value="<?php echo $_SESSION['usuario']; ?>">

          <div class="chanel-stars">
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <input type="radio" id="star<?php echo $i; ?>" name="puntuacion" value="<?php echo $i; ?>" required>
              <label for="star<?php echo $i; ?>">★</label>
            <?php endfor; ?>
          </div>

          <textarea name="comentario" placeholder="Escribe tu opinión..." required></textarea>
          <button type="submit">Enviar comentario</button>
        </form>
      <?php else: ?>
        <p><em>Inicia sesión para dejar un comentario.</em></p>
      <?php endif; ?>
    </div>
  </div>

<script>
function addProducto(id, token) {
  let formData = new FormData();
  formData.append('id', id);
  formData.append('token', token);

  fetch('../../models/carrito.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.ok) {
      // Actualizar el número del carrito en el header
      document.getElementById("num_cart").innerText = data.numero;
      alert('Producto agregado al carrito');
    } else {
      alert('Error al agregar al carrito');
    }
  })
  .catch(() => alert('Error en la comunicación con el servidor'));
}
</script>
</body>
</html>
