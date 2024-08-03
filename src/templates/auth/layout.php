<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style type="text/css">
        <?= admin()->deque('css') ?>
    </style>
</head>

<body class="bg-zinc-900 min-h-screen text-slate-100">

    <main>
        <?= $content ?>
    </main>

</body>

</html>