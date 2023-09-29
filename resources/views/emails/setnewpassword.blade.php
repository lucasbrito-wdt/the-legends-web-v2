<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('otserver.server.serverName') }} - Nova senha para sua conta</title>
</head>
<style type="text/css">
    html, body{
        margin: 0;
        padding: 0;
    }
    .header{
        width: 100%;
        height: 80px;
        background: #000;
        position: fixed;
        top: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .logo {
        height: 70px;
        pointer-events: none;
    }
    .main{
        background: #CCC;
        flex: 1;
        display:-webkit-flex;
        display:-ms-flexbox;
        display:flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .container {
        margin: 80px 0;
        padding: 10px;
        text-align: center;
        flex: 1;
    }
    .footer{
        width: 100%;
        height: 80px;
        background: #000;
        position: fixed;
        bottom: 0;
    }
</style>
<body>
    <div class="header">
        <img class="logo" src="{{ asset('images/general/Logo.png') }}"/>
    </div>
    <div class="main">
        <div class="container">
            <h3>Seu nome de conta e senha!</h3>
            <p>Senha alterada para sua conta na interface de conta perdida no servidor <a href="{{ config('otserver.server.url') }}"><b>{{ config('otserver.server.serverName')}}</b></a>.</p>
            <p>Nome da conta: <b>{{ htmlspecialchars($name) }}</b></p>
            <p>Nova senha: <b>{{ htmlspecialchars($newpassword) }}</b></p>
        </div>
    </div>
    <footer class="footer"></footer>
</body>
</html>
