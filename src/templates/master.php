<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        <?= file_get_contents(__DIR__ . '/../assets/app.css') ?>
    </style>
    <script>
        <?= file_get_contents(__DIR__ . '/../assets/app.js') ?>
    </script>
</head>

<body class="bg-zinc-900 min-h-screen text-slate-100">
    <?= $this->template('includes/header') ?>

    <?php
    if (isset($navigation)) {
        echo $this->template('includes/navigation', $navigation);
    }
    ?>

    <main>
        <?= $content ?>
    </main>

</body>

</html>