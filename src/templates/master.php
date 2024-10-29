<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= strip_tags($title) ?></title>
    <style type="text/css">
        <?= admin()->deque('css') ?>
        #fire-error-content {
            max-width: 450px;
            margin: 45px auto;
            text-align: center;
        }

        #fire-error-content h1 {
            font-weight: 500;
            margin: 15px 0 10px 0;
            font-size: 24px;
        }

        #fire-error-content p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        #fire-error-content a {
            display: inline-block;
            color: #5eead4;
        }

        #fire-loading-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background-color: rgba(0, 0, 0, 0.25);
            border-top-right-radius: 50px;
            border-bottom-right-radius: 50px;
            z-index: 9999;
            transition: width 0.3s;
        }
    </style>
</head>

<body class="bg-zinc-900 min-h-screen text-slate-100">
    <div id="fire-loading-progress"></div>

    <?= $template->include('includes/header') ?>

    <main id="fireContent">
        <?= $content ?>
    </main>

    <div id="fire-error-content" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" style="margin: auto;color: #f59e0b;" width="48" height="48"
            viewBox="0 0 24 24" fill="currentColor">
            <path d="M11.001 10h2v5h-2zM11 16h2v2h-2z"></path>
            <path
                d="M13.768 4.2C13.42 3.545 12.742 3.138 12 3.138s-1.42.407-1.768 1.063L2.894 18.064a1.986 1.986 0 0 0 .054 1.968A1.984 1.984 0 0 0 4.661 21h14.678c.708 0 1.349-.362 1.714-.968a1.989 1.989 0 0 0 .054-1.968L13.768 4.2zM4.661 19 12 5.137 19.344 19H4.661z">
            </path>
        </svg>
        <h1><?= __('Oops!') ?></h1>
        <p><?= __('We couldn\'t load the resource you\'re looking for.') ?></p>
        <a href="#" onclick="window.fireView.reload()"><?= __('Go Back') ?></a>
    </div>

    <script type="text/javascript">
        <?= fire_script(); ?>
        <?= admin()->deque('js') ?>
        document.addEventListener("DOMContentLoaded", function () {
            window.fireView.on('beforeLoad', () => {
                document.getElementById('fireContent').style.display = 'block';
                document.getElementById('fire-error-content').style.display = 'none';

                const progressBar = document.getElementById('fire-loading-progress');
                progressBar.style.width = '0%';
                progressBar.style.display = 'block';

                setTimeout(() => {
                    if (progressBar.style.width != '100%') {
                        progressBar.style.width = '50%';
                    }
                }, 100);
            });

            window.fireView.on('afterLoad', () => {
                const progressBar = document.getElementById('fire-loading-progress');
                progressBar.style.width = '100%';

                setTimeout(() => {
                    progressBar.style.display = 'none';
                }, 300);
            });

            window.fireView.on('onRender', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            window.fireView.on('onError', () => {
                document.getElementById('fireContent').style.display = 'none';
                document.getElementById('fire-error-content').style.display = 'block';
            });
        });
    </script>
</body>

</html>