<?php
include 'koneksi.php';

// Get total waiting
$res_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM queues WHERE status='waiting'");
$row_total = mysqli_fetch_assoc($res_total);
$total = $row_total ? $row_total['total'] : 0;

// Get current processing
$res_current = mysqli_query($conn, "SELECT queue_number FROM queues WHERE status='processing' ORDER BY id DESC LIMIT 1");
$row_current = mysqli_fetch_assoc($res_current);
$current = $row_current ? $row_current['queue_number'] : 0;

header('Content-Type: application/json');
echo json_encode([
    'total' => $total,
    'current' => $current
]);
?>
