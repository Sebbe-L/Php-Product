<?php
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'create') {
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price']
        ];
        $json = json_encode($data);

        $ch = curl_init('http://localhost:3000/api/products');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($ch);
        curl_close($ch);

        header('Location: index.php');
        exit;
    }

    if ($action === 'update') {
        $productId = $_POST['id'];
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price']
        ];
        $json = json_encode($data);

        $ch = curl_init("http://localhost:3000/api/products/$productId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($ch);
        curl_close($ch);

        header('Location: index.php');
        exit;
    }

    if ($action === 'delete') {
        $productId = $_POST['id'];

        $ch = curl_init("http://localhost:3000/api/products/$productId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($action); ?> Produkt</title>
</head>

<body>
    <?php if ($action === 'create' || $action === 'update'): ?>
        <h1><?php echo ucfirst($action); ?> Produkt</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?? ''; ?>">
            <div>
                <label for="name">Namn:</label>
                <input type="text" id="name" name="name" value="">
            </div>
            <div>
                <label for="price">Pris:</label>
                <input type="text" id="price" name="price" value="">
            </div>
            <button type="submit"><?php echo ucfirst($action); ?></button>
        </form>
    <?php elseif ($action === 'delete'): ?>
        <h1>Ta bort produkt</h1>
        <p>Är du säker på att du vill ta bort produkten?</p>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <button type="submit">Ja, ta bort</button>
            <a href="index.php">Nej, tillbaka</a>
        </form>
    <?php endif; ?>
</body>

</html>