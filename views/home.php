<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
</head>
<body>
    Home page render from View

    <?php $sendTime = $data['sendTime'] ?? 'Nothing has been sent' ?>
    <?php $message = $data['message'] ?? 'Nothing has been sent' ?>

    <br>
    <br>
    Message:
    <?= htmlspecialchars($message, ENT_QUOTES) ?>
    <br>
    Unix time:
    <?= htmlspecialchars($sendTime, ENT_QUOTES) ?>

</body>
</html>