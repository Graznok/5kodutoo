<?php
include 'header.php'; 
?>

<?php
    // Loeb bänneri pilte kataloogist
    function getBannersFromDirectory($directory) {
        $images = [];
        foreach (glob($directory . "/b*.jpg") as $file) {
            $images[] = $file;
        }
        return $images;
    }

    // Bänneritekstide massiiv
    $bannerTexts = [
        [
            "headline" => "Osta 1, saad 1 tasuta",
            "subheadline" => "Parim pakkumine",
            "description" => "Ära jäta seda pakkumist kasutamata!"
        ],
        [
            "headline" => "Kõik rohelised tooted -20%",
            "subheadline" => "Allahindlus loodustoodetele",
            "description" => "Roheline eluviis, roheline soodustus!"
        ],
        [
            "headline" => "Mega allahindlus",
            "subheadline" => "Kõik suvised tooted -50%",
            "description" => "Suvised pakkumised ainult piiratud ajaks!"
        ],
        [
            "headline" => "Telli kohe!",
            "subheadline" => "Vaid täna saad tasuta kohaletoimetuse",
            "description" => "Säästa rohkem ja telli täna!"
        ]
    ];

    // Loo juhuslikud bännerid
    $imgDirectory = 'img';
    $banners = getBannersFromDirectory($imgDirectory);

    shuffle($banners);
    shuffle($bannerTexts);
    $selectedBanners = array_slice($banners, 0, 2); 
    $selectedTexts = array_slice($bannerTexts, 0, 2);

    // Loeb piiratud arvu tooteid CSV failist
    function getLimitedProducts($csvFile, $rowLimit) {
        $products = [];
        $headers = fgetcsv($csvFile); // Päise rida
        $count = 0;
        while (($row = fgetcsv($csvFile)) !== false && $count < $rowLimit) {
            if (count($row) === count($headers)) {
                $products[] = array_combine($headers, $row);
                $count++;
            }
        }
        return $products;
    }
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suvalised Bännerid ja Tooted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section img { width: 100%; height: 300px; object-fit: cover; }
        .hero-text { position: absolute; top: 50%; left: 40%; transform: translateY(-50%); color: white; }
        .btn-banner { border: 2px solid white; color: white; background-color: transparent; padding: 10px 20px; cursor: pointer; }
        .btn-banner:hover { background-color: white; color: black; }
        .card-wrapper { border: 2px solid #ccc; border-radius: 30px; padding: 10px; background-color: white; height: 100%; }
        .card { border-radius: 20px; border: 1px solid white; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }
        .card img { border-radius: 20px; max-height: 200px; object-fit: cover; }
        .section-title { font-weight: bold; text-align: center; margin: 30px 0 20px; font-size: 24px; }
    </style>
</head>
<body>
<div class="container-lg mt-2 pt-4">   
    <div class="row g-0">
        <?php foreach ($selectedBanners as $index => $image): ?>
            <div class="col-md-6 p-2 position-relative"> 
                <img src="<?php echo $image; ?>" alt="Banner Image" class="img-fluid" style="width: 100%; height: 300px;">
                <div class="position-absolute top-50 start-40 translate-middle-y p-4 text-white">
                    <p class="fw-bold fs-5 mb-0"><?php echo htmlspecialchars($selectedTexts[$index]['subheadline']); ?></p>
                    <p class="fw-bold fs-1 mb-0"><?php echo htmlspecialchars($selectedTexts[$index]['headline']); ?></p>
                    <p class="fs-6"><?php echo htmlspecialchars($selectedTexts[$index]['description']); ?></p>
                    <button class="btn-banner">Vaata lähemalt</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container">
    <h2 class="section-title">Parimad pakkumised</h2>
</div>

<div class="container mt-5">
    <div class="row row-cols-1 row-cols-md-4 g-4">

        <?php
        $csvFile = fopen('tooted.csv', 'r');
        if ($csvFile !== false) {
            $products = getLimitedProducts($csvFile, 8); // Loeme esimesed 8 toodet
            foreach ($products as $product) {
                $image = isset($product['img']) ? $product['img'] : 'img/default.jpg';
                $title = isset($product['toote_nimi']) ? $product['toote_nimi'] : 'Pealkiri puudub';
                $price = isset($product['hind']) ? $product['hind'] : 'Hind puudub';
                ?>
                <div class="col">
                    <div class="card-wrapper"> 
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($title); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                <p class="card-text fw-bold"><?php echo htmlspecialchars($price); ?> €</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            fclose($csvFile);
        } else {
            echo '<p>Tooted ei ole saadaval.</p>';
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<br>
<br>
<br>
<br>
<br>

<?php

          include 'Footer.php'; 
        ?>
