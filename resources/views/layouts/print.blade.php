<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tanda Terima')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 14px;
        }
        @media print {
            @page { size: A4; margin: 10mm; }
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .signature {
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        @yield('content')
    </div>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>