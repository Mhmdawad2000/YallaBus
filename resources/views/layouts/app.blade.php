<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YallaBus</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arial:400,600&display=swap">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007061;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        footer {
            background-color: #f0f0f0;
            color: #666;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        main {
            padding: 30px 15px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        YallaBus
    </header>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        © {{ date('Y') }} YallaBus. All rights reserved.
    </footer>

</body>

</html>
