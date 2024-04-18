<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Training Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        header{color:#FFF;background:#4e5657;padding-top:20px;padding-bottom:20px;margin-top:20px;margin-bottom:20px;}
        footer{color:#FFF;background:#4e5657;padding-top:20px;padding-bottom:20px;margin-top:20px;}
        footer a{color:#FFF;text-decoration: none;}
        footer a:hover{color:#f7a600;}
        .table table{
            --bs-table-bg: transparent;
            --bs-table-accent-bg: transparent;
            --bs-table-striped-color: #212529;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
            --bs-table-active-color: #212529;
            --bs-table-active-bg: rgba(0, 0, 0, 0.1);
            --bs-table-hover-color: #212529;
            --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
        }
        .table table > :not(caption) > * > * {
            padding: 0.5rem 0.5rem;
            background-color: var(--bs-table-bg);
            border-bottom-width: 1px;
            box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
        }
        .table table > tbody {
            vertical-align: inherit;
        }
        .table table > thead {
            vertical-align: bottom;
        }
        .table table > :not(:last-child) > :last-child > * {
            border-bottom-color: currentColor;
        }
        .image img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            min-width: 100%;
        }
        .image.image-style-block-align-right img, .image.image-style-block-align-left img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            min-width:auto !important;
        }
    </style>
    <link href="/css/ckeditorfrontend.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-6"><img src="/img/logo2x.png" class="img-fluid"></div>
            <div class="col-sm-6"><img src="/img/img.png" class="img-fluid float-end"></div>
        </div>
    </div>
</header>
<div class="container ck-content">
    @yield('content')
</div>
<br><br>
<footer>

</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="/js/jquery.js"></script>
@yield('javascript')
</body>
</html>
