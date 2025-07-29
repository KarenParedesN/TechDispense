<?php

class Pago
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
            die("Error al conectar: " . $e->getMessage());
        }
    }

    public function cerrarBD()
    {
        $this->pdo = null;
    }


    public function mostrarBuscadorPedido()
    {
        $valorBuscado = isset($_GET['pedido_id']) ? htmlspecialchars($_GET['pedido_id']) : '';

        echo '
        <form method="GET" action="" class="mb-4 d-flex align-items-center gap-2">
            <label for="pedido_id" class="form-label mb-0 me-2">Buscar por ID Pedido:</label>
            <input type="number" name="pedido_id" id="pedido_id" value="' . $valorBuscado . '" required class="form-control" style="max-width: 150px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Buscar
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href=\'?\';">
                <i class="fas fa-sync-alt me-1"></i> Mostrar Todo
            </button>
        </form>
        ';
    }

    public function mostrarReportePagos()
    {
        $this->conectarBD();

        $pedido_id = isset($_GET['pedido_id']) ? $_GET['pedido_id'] : '';

        $sql = "
            SELECT 
                p.pedido_id,
                u.nombre,
                u.apellido_Paterno,
                u.apellido_Materno,
                u.cue_usu,
                p.total,
                p.paypal_order_id,
                d.cantidad,
                d.precio_unitario,
                d.subtotal,
                dp.dis_nombre AS nombre_producto
            FROM pedidos p
            INNER JOIN usuarios u ON p.usuario_id = u.id_usu
            LEFT JOIN pedido_detalles d ON p.pedido_id = d.pedido_id
            LEFT JOIN dispensadores dp ON d.dispensador_id = dp.dis_id
            WHERE p.paypal_order_id IS NOT NULL
        ";

        if ($pedido_id !== '') {
            $sql .= " AND p.pedido_id = :pedido_id ";
        }

        $sql .= " ORDER BY p.pedido_id DESC";

        $stmt = $this->pdo->prepare($sql);

        if ($pedido_id !== '') {
            $stmt->bindValue(':pedido_id', $pedido_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                                <th>ID Pedido</th>
                                <th>Usuario</th>
                                <th>Cuenta</th>
                                <th>Total</th>
                                <th>PayPal Order ID</th>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';

        if (!empty($pagos)) {
            foreach ($pagos as $pago) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($pago['pedido_id']) . '</td>';
                echo '<td>' . htmlspecialchars($pago['nombre'] . ' ' . $pago['apellido_Paterno'] . ' ' . $pago['apellido_Materno']) . '</td>';
                echo '<td>' . htmlspecialchars($pago['cue_usu']) . '</td>';
                echo '<td>$' . number_format($pago['total'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($pago['paypal_order_id']) . '</td>';
                echo '<td>' . htmlspecialchars($pago['nombre_producto']) . '</td>';
                echo '<td>$' . number_format($pago['precio_unitario'], 2) . '</td>';
                echo '<td>' . htmlspecialchars($pago['cantidad']) . '</td>';
                echo '<td>$' . number_format($pago['subtotal'], 2) . '</td>';
                echo '<td class="text-success fw-bold">Pagado</td>';
                echo '</tr>';
            }
        } else {
           echo '<tr><td colspan="10" style="text-align:center;">No se encontraron Pagos.</td></tr>';
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
