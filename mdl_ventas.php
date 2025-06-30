<?php

class Ventas
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

    public function mostrarVentas()
    {
        $this->conectarBD();

        $query = "
            SELECT
                v.id_venta,
                v.claveTransaccion,
                v.fecha,
                v.totalVenta,
                v.estatus,
                u.nombre_usu,
                i.id_cita,
                i.status_cita,
                p.nombre_produ
            FROM ventas v
            LEFT JOIN usuarios u ON v.USUARIOS_id_usu = u.id_usu
            LEFT JOIN instalaciones i ON i.VENTA_id_venta = v.id_venta
            LEFT JOIN productos p ON i.PRODUCTOS_id_produ = p.id_produ
            ORDER BY v.id_venta DESC
        ";

        try {
            $stmt = $this->pdo->query($query);
            $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '
            <style>
                table.table-bordered th,
                table.table-bordered td {
                    border: 1px solid #dee2e6;
                }
                table.table-bordered tbody tr {
                    border-bottom: 1px solid #dee2e6;
                }
            </style>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Reporte de Ventas</h6>
    
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Venta</th>
                                    <th>Clave Transacción</th>
                                    <th>Fecha</th>
                                    <th>Total Venta</th>
                                    <th>Status del pago</th>
                                    <th>Nombre Usuario</th>
                                    <th>Producto Comprado</th>
                                </tr>
                            </thead>
                            <tbody>';

            foreach ($ventas as $venta) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($venta['id_venta']) . '</td>';
                echo '<td>' . htmlspecialchars($venta['claveTransaccion']) . '</td>';
                echo '<td>' . htmlspecialchars($venta['fecha']) . '</td>';
                echo '<td>$' . number_format($venta['totalVenta'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($venta['estatus']) . '</td>';
                echo '<td>' . htmlspecialchars($venta['nombre_usu'] ?? 'No disponible') . '</td>';
                echo '<td>' . htmlspecialchars($venta['nombre_produ'] ?? 'Sin producto') . '</td>';
                echo '</tr>';
            }

            echo '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';

        } catch (PDOException $e) {
            echo "Error al obtener ventas: " . $e->getMessage();
        }

        $this->cerrarBD();
    }
}
?>
