    <?php
       include 'header.php';

$csv_file = 'tooted.csv'; 
$pildid_dir = 'img/'; 

// Kontrollime, kas kaust ja CSV fail eksisteerivad
if (!is_dir($pildid_dir)) {
    mkdir($pildid_dir, 0777, true);
}
if (!file_exists($csv_file)) {
    $file = fopen($csv_file, 'w');
    fclose($file);
}

// Funktsioon ID määramiseks
function get_next_id($csv_file) {
    if (!file_exists($csv_file)) {
        return 1;
    }
    $rows = file($csv_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $max_id = 0;
    foreach ($rows as $row) {
        $data = str_getcsv($row);
        if (isset($data[0]) && is_numeric($data[0])) {
            $max_id = max($max_id, (int)$data[0]);
        }
    }
    return $max_id + 1;
}

// Toote lisamine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    if (!empty($_POST['img']) && !empty($_POST['name']) && !empty($_POST['price'])) {
        $file_path = $_POST['img'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $next_id = get_next_id($csv_file);
        $file = fopen($csv_file, 'a');
        if ($file) {
            fputcsv($file, [$next_id, $name, $price, $file_path]);
            fclose($file);

            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    }
}

// Toote kustutamine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $rows = array_map('str_getcsv', file($csv_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    $new_rows = array_filter($rows, function ($row) use ($delete_id) {
        return $row[0] != $delete_id;
    });

    $file = fopen($csv_file, 'w');
    if ($file) {
        foreach ($new_rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin leht</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dropdown-menu img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            margin-right: 10px;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <!-- Toote lisamine -->
    <h2>Lisa uus toode</h2>
    <form method="POST" class="mb-3">
        <div class="mb-2">
            <label>Vali pilt</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Vali pilt
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                    $image_files = glob($pildid_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    if ($image_files) {
                        foreach ($image_files as $image) {
                            $image_name = basename($image);
                            echo '<li>
                                <button class="dropdown-item" type="button" data-value="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '">
                                    <img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($image_name, ENT_QUOTES, 'UTF-8') . '">
                                    ' . htmlspecialchars($image_name, ENT_QUOTES, 'UTF-8') . '
                                </button>
                            </li>';
                        }
                    } else {
                        echo '<li class="dropdown-item">Pilte ei leitud</li>';
                    }
                    ?>
                </ul>
                <input type="hidden" name="img" id="selectedImage" required>
            </div>
        </div>
        <div class="mb-2">
            <label for="name">Toote nimi</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-2">
            <label for="price">Hind</label>
            <input type="text" id="price" name="price" class="form-control" required>
        </div>
        <button type="submit" name="add_product" class="btn btn-primary">Lisa toode</button>
    </form>

    <!-- Toote tabel -->
    <h2>Tooted</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Toode</th>
            <th>Hind</th>
            <th>Pilt</th>
            <th>Kustuta</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (file_exists($csv_file)) {
            $handle = fopen($csv_file, 'r');
            fgetcsv($handle, 1000, ','); // Ignoreerime päise rea
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8') . ' €</td>';
                echo '<td><img src="' . htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8') . '" alt="Toode" class="product-img"></td>';
                echo '<td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="delete_id" value="' . htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8') . '">
                        <button type="submit" class="btn btn-danger btn-sm">Kustuta</button>
                    </form>
                </td>';
                echo '</tr>';
            }
            fclose($handle);
        }
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const dropdownButton = document.getElementById('dropdownMenuButton');
    const hiddenInput = document.getElementById('selectedImage');

    dropdownItems.forEach(item => {
        item.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            dropdownButton.innerHTML = `
                <img src="${value}" style="width: 30px; height: 30px; object-fit: cover; margin-right: 10px;">
                ${this.textContent.trim()}
            `;
            hiddenInput.value = value;
        });
    });
});
</script>
</body>
</html>

<br>
<br>
<br>
<br>

<?php
include 'footer.php';
?>

