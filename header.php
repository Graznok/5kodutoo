<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iseseisevtöö5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-lg">
            <a class="navbar-brand ms-5" href="#">AnuTursk.ee</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link me-3" href="Index.php">Avaleht</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="pood.php">Pood</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="kontakt.php">Kontakt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="admin.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="ostukorv.php">
                            <i class="bi bi-bag-fill"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

            <script>
                
                document.addEventListener('DOMContentLoaded', function () {
                    const navLinks = document.querySelectorAll('.nav-link');
                    const currentPath = window.location.pathname;

                navLinks.forEach(link => {
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });
                });
            </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
