<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Relatório de Produtos</title>
    <link rel="shortcut icon" href="https://farmacotecnica.com.br/favicon.ico">
    <style>

        * {
        margin: 0;
        padding: 0;


        }

        nav.header {
            background-color: #272727;
            margin-bottom: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light header">
        <div class="container-fluid">
          <div class="d-none d-sm-block col-sm-4 col text-start">
            <a class="navbar-brand" href="#">
              <img src="{{asset('logo-farmacotecnica.png')}}" alt="" width="160">
            </a>
          </div>
          <div class="col-sm-4 col fs-1 text-center text-white">
            <h1 style="overflow: hidden">Relatório de Produtos</h1>
          </div>
          <div class="d-none d-sm-block col-sm-4 col text-end">
            <a href="" class="navbar-brand">
              <img src="{{asset('logo-mram.png')}}" alt="" width="180">
            </a>
          </div>
        </div>
      </nav>

    <div id="app">
        @yield('content')
    </div>

    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
