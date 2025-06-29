<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Not Available</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .unavailable-container {
            max-width: 500px;
            padding: 2rem;
            border-radius: 12px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

    <div class="unavailable-container text-center">
        <h1 class="text-danger mb-3">🚫 Table #{{ $table->number }} is not available</h1>
        <p class="text-muted mb-4">Please contact a staff member for assistance or scan a different table.</p>
    </div>

</body>
</html>
