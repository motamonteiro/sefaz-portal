<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Nao adicione nada antes destas meta-tags -->
<title>{{ $portal->nmeSistemaAtual }}</title>
<link href="{{ config('sistema.cdn.img') }}favicon.png" rel="shortcut icon" type="image/png">
<link href="{{  config('sistema.cdn.css') }}estilos.min.css" rel="stylesheet">
<link href="{{ config('sistema.cdn.css') }}plugins.min.css" rel="stylesheet">
<link href="{{ url('css/app.css') }}" rel="stylesheet">
<link href="https://internet.sefaz.es.gov.br/conf/msg/css/msg.css" rel="stylesheet">
<!-- Hack para funcionamento de HTML5 e Media Queries no IE8 -->
<!-- ATENÇÃO: Não funcionará se abrir o arquivo com duplo clique, via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet" type="text/css">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">-->