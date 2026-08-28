<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Information System</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 m-0">

<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
<div class="login-box col-12 col-sm-8 col-md-6 col-lg-4 p-4 shadow-lg bg-white rounded-4">

    <div class="logo">
    <img src="logo.png" alt="SIBTI Logo">
</div>

   <h2>Southern Institute of Business and Technology, Inc.</h2>

<p class="subtitle">
    Student Information System
</p>
    <form action="login.php" method="POST">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-primary"><i class="fas fa-user"></i></span>
                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Enter Username"
                    required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-primary"><i class="fas fa-lock"></i></span>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter Password"
                    required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold btn-login">
            <i class="fas fa-sign-in-alt me-1"></i>
            Login
        </button>

    </form>

    <div class="footer">
        © <?php echo date("Y"); ?> Student Information System
    </div>

</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>