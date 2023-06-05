<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Corporativo</title>
</head>
<style>
    .container{
        background-image: url(resources/images/mask1.png);
        min-height: 700px;
        background-size: auto 100%;
        background-repeat: no-repeat;
        background-position-x: -100px;
        width: 100%;
        min-width: 800px;
        max-width: 1100px;
        position: relative;
        text-align: right;
        font-family: sans-serif;
    }
    .clearfix::after, .container:after {
        content: "";
        display: table;
        clear: both;
    }
    .header {
        height: max-content;
        width: 300px;
        position: relative;
        display: inline-block;
        margin-right: 15px;
        margin-top: 15px;
    }
    .body {
        height: 500px;
        max-width: 50%;
        display: block;
        float:right;
        margin-top:40px;
        text-align:left;
    }

    .header img {
        width: 100%;
    }

    .footer {
        position: absolute;
        bottom: 10px;
        right: 10px;
        font-size: 12px;
        float:right;
    }
</style>
<body>
    <div class="container">
        <div class="header">
            <img src="resources/images/logo.png" alt="">
        </div>
        <div class="clearfix"></div>
        <div class="body">
            
        </div>
        <div class="clearfix"></div>
        <div class="footer">
            Este mensaje ha sido generado de forma automatica
        </div>
    </div>
</body>
</html>