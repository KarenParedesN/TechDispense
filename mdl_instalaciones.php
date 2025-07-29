<?php

class Instalacion
{
    private $pdo;

    public function conectarBD()
    {
        try {
           $this->pdo = new PDO(
    "mysql:host=localhost;dbname=u470346911_tecnodispense;charset=utf8",
    "u470346911_root",
    "]9pk&JBgJ"
);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function cerrarBD()
    {
        $this->pdo = null;
    }

   public function mostrarBuscador()
{
    $valorBuscado = isset($_GET['id_cita']) ? htmlspecialchars($_GET['id_cita']) : '';

    echo '
    <form method="GET" action="" class="mb-4 d-flex align-items-center gap-2">
        <label for="id_cita" class="form-label mb-0 me-2">Buscar por ID Cita:</label>
        <input type="number" name="id_cita" id="id_cita" value="' . $valorBuscado . '" required class="form-control" style="max-width: 150px;">
        <button type="submit" class="btn btn-primary">  <i class="fas fa-search me-1"></i>Buscar</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href=\'?\'">  <i class="fas fa-sync-alt me-1"></i>Mostrar Todo</button>
    </form>
    ';
}
  
    public function mostrarCitas($id_cita = null)
    {
        $this->conectarBD();

        if ($id_cita !== null && $id_cita !== '') {
            if (!ctype_digit($id_cita) || intval($id_cita) < 1) {
                echo '<p style="color:red;">El ID debe ser un número entero positivo.</p>';
                $citas = [];
            } else {
            
                $query = "SELECT id_cita, status_cita, telefono, direccion, fecha_hora_instalacion, pedido_id, nombre_instalador FROM citas WHERE id_cita = :id_cita ORDER BY id_cita DESC";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(':id_cita', $id_cita, PDO::PARAM_INT);
                $stmt->execute();
                $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            $query = "SELECT id_cita, status_cita, telefono, direccion, fecha_hora_instalacion, pedido_id, nombre_instalador FROM citas ORDER BY id_cita DESC";
            $stmt = $this->pdo->query($query);
            $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo '
        <style>
            table.table-bordered th,
            table.table-bordered td {
                border: 1px solid #dee2e6;
                padding: 8px;
            }
            table.table-bordered tbody tr {
                border-bottom: 1px solid #dee2e6;
            }
            table.table-bordered {
                border-collapse: collapse;
                width: 100%;
            }
        </style>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Reporte de Ventas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Cita</th>
                                <th>Status</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Fecha y Hora Instalación</th>
                                <th>ID Pedido</th>
                                <th>Nombre Instalador</th>
                            </tr>
                        </thead>
                        <tbody>';

        if (!empty($citas)) {
            foreach ($citas as $cita) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($cita['id_cita']) . '</td>';
                echo '<td>' . htmlspecialchars($cita['status_cita']) . '</td>';
                echo '<td>' . htmlspecialchars($cita['telefono']) . '</td>';
                echo '<td>' . htmlspecialchars($cita['direccion']) . '</td>';
                echo '<td>' . htmlspecialchars($cita['fecha_hora_instalacion']) . '</td>';
                echo '<td>' . htmlspecialchars($cita['pedido_id']) . '</td>';
                echo '<td>' . htmlspecialchars($cita['nombre_instalador']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7" style="text-align:center;">No se encontraron citas.</td></tr>';
        }

        echo '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>';

        $this->cerrarBD();
    }
}



?>
