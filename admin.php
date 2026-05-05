<?php
include 'koneksi.php';

// Logic to call the next queue (Update status from waiting to processing)
if (isset($_POST['call_next'])) {
    // Find the oldest 'waiting' queue number
    $next_res = mysqli_query($conn, "SELECT id FROM queues WHERE status='waiting' ORDER BY id ASC LIMIT 1");
    $next_row = mysqli_fetch_assoc($next_res);

    if ($next_row) {
        $id = $next_row['id'];
        // Update previous 'processing' to 'done'
        mysqli_query($conn, "UPDATE queues SET status='done' WHERE status='processing'");
        // Update the new one to 'processing'
        mysqli_query($conn, "UPDATE queues SET status='processing' WHERE id='$id'");
        header("Location: admin.php?status=called");
    } else {
        header("Location: admin.php?status=empty");
    }
}

// Logic to reset queue
if (isset($_POST['reset'])) {
    mysqli_query($conn, "TRUNCATE TABLE queues");
    header("Location: admin.php?status=reset");
}

// Get current serving number
$current_res = mysqli_query($conn, "SELECT queue_number FROM queues WHERE status='processing' ORDER BY id DESC LIMIT 1");
$current_row = mysqli_fetch_assoc($current_res);
$current_number = $current_row ? $current_row['queue_number'] : 'None';

// Get total waiting
$waiting_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM queues WHERE status='waiting'");
$waiting_row = mysqli_fetch_assoc($waiting_res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Queue Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="text-center mb-5">Admin Queue Dashboard</h1>

    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-info text-center shadow-sm" role="alert">
            <?php 
            if ($_GET['status'] == 'called') echo "Next queue number called successfully!";
            elseif ($_GET['status'] == 'empty') echo "No more people waiting in the queue.";
            elseif ($_GET['status'] == 'reset') echo "Queue has been reset.";
            ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center mb-4">
        <div class="col-md-5">
            <div class="card shadow-sm text-center">
                <div class="card-body py-4">
                    <h3 class="card-title text-muted mb-3">Currently Serving</h3>
                    <h1 id="currentQueue" class="display-1 fw-bold text-success"><?php echo $current_number; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow-sm text-center">
                <div class="card-body py-4">
                    <h3 class="card-title text-muted mb-3">Total Waiting</h3>
                    <h1 id="totalQueue" class="display-1 fw-bold text-warning"><?php echo $waiting_row ? $waiting_row['total'] : '0'; ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 text-center mt-4">
            <form method="POST" action="">
                <button type="submit" name="call_next" class="btn btn-primary btn-lg px-5 py-3 fs-4 shadow me-3">Call Next Number</button>
                <button type="submit" name="reset" class="btn btn-danger btn-lg px-5 py-3 fs-4 shadow" onclick="return confirm('Are you sure you want to reset the entire queue?');">Reset Queue</button>
            </form>
        </div>
    </div>
</div>

<script>
setTimeout(function() {
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        alertElement.style.display = 'none';
    }
}, 3000); // 3000 milliseconds = 3 seconds

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
    // refresh tiap 1 detik
    setInterval(loadQueue, 1000);
</script>

</body>
</html>