<?php 
include 'koneksi.php';

// Logic to handle adding to queue
if (isset($_POST['tambah'])) {
    // Get the next queue number by finding the current max and adding 1
    $max_q_res = mysqli_query($conn, "SELECT MAX(queue_number) as max_q FROM queues");
    $max_q_row = mysqli_fetch_assoc($max_q_res);
    $next_queue_number = ($max_q_row['max_q'] ?? 0) + 1;

    // Insert the new queue number. The status will default to 'waiting' as per your table structure.
    $query = mysqli_query($conn, "INSERT INTO queues (queue_number) VALUES ('$next_queue_number')");

    if ($query) {
        header("Location: index.php?status=success&number=" . urlencode($next_queue_number));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {background-image: url("https://banyuwangikab.go.id/media/berita/original/gohclzzgjs_img-20230320-wa0024.jpg");}
        .queue-card { transition: transform 0.3s; }
        .queue-card:hover { transform: scale(1.02); }
        .number-display { font-size: 5rem; font-weight: bold; color: #0d6efd; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm mb-4 queue-card">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4">Total People Queueing :</h2>
                    <?php
                    ?>
                    <div id="totalQueue" class="number-display mb-4">
                        <?php echo $total_queue_numbers ? $total_queue_numbers['queue_number'] : '0'; ?>
                    </div>
                    <p class="text-muted">Please wait for your turn</p>
                    <p class="text-muted">The number that's in procces now : <span id="currentQueue"><?php echo $current_queue_numbers ? $current_queue_numbers['queue_number'] : '0'; ?></span></p>
                </div>
            </div>

            <form method="POST" action="">
                <button type="submit" name="tambah" class="btn btn-primary btn-lg w-100 py-3 fs-4 shadow">Take a Number</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_GET['number'])): ?>
<!-- Modal -->
<div class="modal fade" id="numberModal" tabindex="-1" aria-labelledby="numberModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center shadow">
      <div class="modal-header bg-primary text-white justify-content-center">
        <h5 class="modal-title fs-4" id="numberModalLabel">Your Queue Number</h5>
      </div>
      <div class="modal-body p-5">
        <p class="text-muted mb-3 fs-5">Please wait for this number to be called</p>
        <div class="display-1 fw-bold text-primary mb-3"><?php echo htmlspecialchars($_GET['number']); ?></div>
      </div>
      <div class="modal-footer justify-content-center border-0 pb-4">
        <button type="button" class="btn btn-secondary btn-lg px-5" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Automatically trigger the modal on page load -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('numberModal'));
        myModal.show();
        
        // Clean the URL back to just index.php so the modal doesn't reappear on auto-refresh
        window.history.replaceState(null, null, window.location.pathname);
    });
</script>
<?php endif; ?>

<!-- Polling script for dynamic updates -->
<script>
    function loadQueue() {
    fetch('get_data.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('totalQueue').innerText = data.total;
            document.getElementById('currentQueue').innerText = data.current;
        });
}

    // pertama load
    loadQueue();

    // refresh tiap 3 detik
    setInterval(loadQueue, 1);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>