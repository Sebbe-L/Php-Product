<?php
$response = file_get_contents('http://localhost:3000/api/products');
$products = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktlista</title>
</head>

<body>
    <h1>Produkter</h1>
    <a href="manage_products.php?action=create">Skapa ny produkt</a>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?php echo htmlspecialchars($product['name']); ?> -
                <?php echo htmlspecialchars($product['price']); ?> SEK
                <a href="manage_products.php?action=update&id=<?php echo $product['id']; ?>">Redigera</a>
                <a href="manage_products.php?action=delete&id=<?php echo $product['id']; ?>">Ta bort</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="http://localhost:3000/api/export/csv">Exportera till CSV</a>
    <a href="http://localhost:3000/api/export/xml">Exportera till XML</a>
</body>

</html>