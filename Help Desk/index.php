<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Desk - Login</title>
    <link rel="stylesheet" href="./bootstrap-italia/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Titillium_Web/titillium-web.css">
    <link rel="stylesheet" href="./bootstrap-italia/fonts/Roboto_Mono/roboto-mono.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="login-container section">
        <h1 class="text-center mb-4">Help Desk</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                Username o password non validi
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Accedi</button>
        </form>
    </div>

    <script src="./bootstrap-italia/js/bootstrap-italia.bundle.min.js"></script>
</body>
</html> 