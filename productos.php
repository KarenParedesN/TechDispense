<?php
class Productos
{
    private $pdo;

    public function conectarBD()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=dispensador;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function cerrarBD()
    {
        $this->pdo = null;
    }

    public function eliminarProducto($id)
    {
        $sql = "DELETE FROM productos WHERE id_produ = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function agregarProducto($data, $file)
    {
        $nombreFoto = '';

        if (isset($file['foto_produ']) && $file['foto_produ']['error'] === 0) {
            $rutaCarpeta = "../../../Source/img/";
            $nombreFoto = time() . "_" . basename($file['foto_produ']['name']);
            $rutaCompleta = $rutaCarpeta . $nombreFoto;

            if (!move_uploaded_file($file['foto_produ']['tmp_name'], $rutaCompleta)) {
                return false;
            }
        }

        $sql = "INSERT INTO productos (nombre_produ, descripcion_produ, tamaño_produ, stock_produ, precio_produ, descuento_produ, foto_produ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_produ'],
            $data['descripcion_produ'],
            $data['tamaño_produ'],
            $data['stock_produ'],
            $data['precio_produ'],
            $data['descuento_produ'],
            $nombreFoto
        ]);
    }

    public function modificarProducto($data, $file)
    {
        $nombreFoto = isset($data['foto_actual']) ? $data['foto_actual'] : '';

        if (isset($file['foto_produ']) && $file['foto_produ']['error'] === 0) {
            $rutaCarpeta = "../../../Source/img/";
            $nombreFoto = time() . "_" . basename($file['foto_produ']['name']);
            $rutaCompleta = $rutaCarpeta . $nombreFoto;

            if (move_uploaded_file($file['foto_produ']['tmp_name'], $rutaCompleta)) {
                if (!empty($data['foto_actual']) && file_exists($rutaCarpeta . $data['foto_actual'])) {
                    unlink($rutaCarpeta . $data['foto_actual']);
                }
            } else {
                return false;
            }
        }

        $sql = "UPDATE productos SET nombre_produ=?, descripcion_produ=?, tamaño_produ=?, stock_produ=?, precio_produ=?, descuento_produ=?, foto_produ=? WHERE id_produ=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_produ'],
            $data['descripcion_produ'],
            $data['tamaño_produ'],
            $data['stock_produ'],
            $data['precio_produ'],
            $data['descuento_produ'],
            $nombreFoto,
            $data['modificar_id_produ']
        ]);
    }

    public function mostrarProductos()
    {
        $this->conectarBD();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id_produ'])) {
            if ($this->eliminarProducto($_POST['eliminar_id_produ'])) {
                echo '<div class="alert alert-success">Producto eliminado correctamente.</div>';
            } else {
                echo '<div class="alert alert-danger">Error al eliminar producto.</div>';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_id_produ'])) {
            if ($this->modificarProducto($_POST, $_FILES)) {
                echo '<div class="alert alert-success">Producto modificado correctamente.</div>';
            } else {
                echo '<div class="alert alert-danger">Error al modificar producto.</div>';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
            if ($this->agregarProducto($_POST, $_FILES)) {
                echo '<div class="alert alert-success">Producto agregado correctamente.</div>';
            } else {
                echo '<div class="alert alert-danger">Error al agregar producto.</div>';
            }
        }

        $productos = $this->pdo->query("SELECT * FROM productos ORDER BY id_produ ASC")->fetchAll(PDO::FETCH_ASSOC);

        echo '
        <!-- Bootstrap CSS y FontAwesome -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Modal Agregar Producto -->
        <div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">
              <input type="hidden" name="agregar_producto" value="1">
              <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarProductoLabel">Agregar Producto</h5>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="agregar_nombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="agregar_nombre" name="nombre_produ" required>
                </div>

                <div class="mb-3">
                  <label for="agregar_desc" class="form-label">Descripción</label>
                  <textarea class="form-control" id="agregar_desc" name="descripcion_produ" rows="3"></textarea>
                </div>

                <div class="mb-3">
                  <label for="agregar_tam" class="form-label">Tamaño</label>
                  <input type="text" class="form-control" id="agregar_tam" name="tamaño_produ">
                </div>

                <div class="mb-3">
                  <label for="agregar_stock" class="form-label">Stock</label>
                  <input type="number" class="form-control" id="agregar_stock" name="stock_produ" min="0">
                </div>

                <div class="mb-3">
                  <label for="agregar_precio" class="form-label">Precio</label>
                  <input type="number" step="0.01" class="form-control" id="agregar_precio" name="precio_produ" required>
                </div>

                <div class="mb-3">
                  <label for="agregar_descuento" class="form-label">Descuento (%)</label>
                  <input type="number" step="0.01" class="form-control" id="agregar_descuento" name="descuento_produ" min="0" max="100">
                </div>

                <div class="mb-3">
                  <label for="agregar_foto" class="form-label">Foto</label>
                  <input type="file" class="form-control" id="agregar_foto" name="foto_produ" accept="image/*">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Agregar Producto</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal Modificar Producto -->
        <div class="modal fade" id="modalModificarProducto" tabindex="-1" aria-labelledby="modalModificarProductoLabel" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalModificarProductoLabel">Modificar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="modificar_id_produ" id="mod_id">
                <input type="hidden" name="foto_actual" id="foto_actual">

                <div class="mb-3">
                  <label for="mod_nombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="mod_nombre" name="nombre_produ" required>
                </div>

                <div class="mb-3">
                  <label for="mod_desc" class="form-label">Descripción</label>
                  <textarea class="form-control" id="mod_desc" name="descripcion_produ" rows="3"></textarea>
                </div>

                <div class="mb-3">
                  <label for="mod_tam" class="form-label">Tamaño</label>
                  <input type="text" class="form-control" id="mod_tam" name="tamaño_produ">
                </div>

                <div class="mb-3">
                  <label for="mod_stock" class="form-label">Stock</label>
                  <input type="number" class="form-control" id="mod_stock" name="stock_produ" min="0">
                </div>

                <div class="mb-3">
                  <label for="mod_precio" class="form-label">Precio</label>
                  <input type="number" step="0.01" class="form-control" id="mod_precio" name="precio_produ" required>
                </div>

                <div class="mb-3">
                  <label for="mod_descuento" class="form-label">Descuento (%)</label>
                  <input type="number" step="0.01" class="form-control" id="mod_descuento" name="descuento_produ" min="0" max="100">
                </div>

                <div class="mb-3">
                  <label for="mod_foto" class="form-label">Foto</label>
                  <input type="file" class="form-control" id="mod_foto" name="foto_produ" accept="image/*">
                  <img id="preview_foto" src="" alt="Foto actual" style="max-width: 150px; margin-top: 10px; display:none; border-radius:8px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
              </div>
            </form>
          </div>
        </div>

        <style>
          table.table-bordered th, table.table-bordered td {
            border: 1px solid #dee2e6;
            vertical-align: middle;
          }
          img.product-foto {
            max-width: 80px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            display: block;
            margin: 0 auto;
          }
        </style>

        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Gestión de Productos</h6>
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
              <i class="fas fa-plus me-2"></i> Agregar Producto
            </button>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>ID</th><th>Nombre</th><th>Descripción</th><th>Tamaño</th><th>Stock</th><th>Precio</th><th>Descuento (%)</th><th>Foto</th><th>Acciones</th>
                  </tr>
                </thead>
                <tbody>';
                
        foreach ($productos as $p) {
            $fotoUrl = !empty($p['foto_produ']) ? "../../../Source/img/" . htmlspecialchars($p['foto_produ']) : "../../../Source/img/sinimagen.jpg";

            echo '<tr>
              <td>' . htmlspecialchars($p['id_produ']) . '</td>
              <td>' . htmlspecialchars($p['nombre_produ']) . '</td>
              <td>' . htmlspecialchars($p['descripcion_produ']) . '</td>
              <td>' . htmlspecialchars($p['tamaño_produ']) . '</td>
              <td>' . htmlspecialchars($p['stock_produ']) . '</td>
              <td>$' . number_format($p['precio_produ'], 2) . '</td>
              <td>' . htmlspecialchars($p['descuento_produ']) . '%</td>
              <td><img src="' . $fotoUrl . '" alt="Foto Producto" class="product-foto"></td>
              <td>
                <form method="POST" style="display:inline-block;" onsubmit="return confirm(\'¿Seguro que deseas eliminar este producto?\')">
                  <input type="hidden" name="eliminar_id_produ" value="' . htmlspecialchars($p['id_produ']) . '">
                  <button type="submit" class="btn btn-danger btn-sm" title="Eliminar producto"><i class="fas fa-trash-alt"></i></button>
                </form>
                <button type="button" class="btn btn-warning btn-sm" 
                  data-bs-toggle="modal" data-bs-target="#modalModificarProducto"
                  onclick=\'llenarFormulario(' . json_encode($p) . ')\'
                  title="Modificar producto">
                  <i class="fas fa-edit"></i>
                </button>
              </td>
            </tr>';
        }

        echo '
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Scripts Bootstrap y JS para el preview y llenar el formulario -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        function llenarFormulario(producto) {
            document.getElementById("mod_id").value = producto.id_produ;
            document.getElementById("mod_nombre").value = producto.nombre_produ;
            document.getElementById("mod_desc").value = producto.descripcion_produ;
            document.getElementById("mod_tam").value = producto.tamaño_produ;
            document.getElementById("mod_stock").value = producto.stock_produ;
            document.getElementById("mod_precio").value = producto.precio_produ;
            document.getElementById("mod_descuento").value = producto.descuento_produ;
            document.getElementById("foto_actual").value = producto.foto_produ;

            let preview = document.getElementById("preview_foto");
            if (producto.foto_produ) {
                preview.src = "../../../Source/img/" + producto.foto_produ;
                preview.style.display = "block";
            } else {
                preview.style.display = "none";
                preview.src = "";
            }
        }

        document.getElementById("mod_foto").addEventListener("change", function(event) {
            const [file] = event.target.files;
            const preview = document.getElementById("preview_foto");
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = "block";
            } else {
                preview.src = "";
                preview.style.display = "none";
            }
        });
        </script>
        ';

        $this->cerrarBD();
    }
}
?>
