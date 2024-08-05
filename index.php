<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- title icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="./Restro/admin/assets/img/icons/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Restro/admin/assets/img/icons/logo.png">
    <title>Restaurant POS System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!--My Styles -->
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="main-logo-img">
                <img src="./images/logo.png" width="400" height="500" alt="">
            </div>
            <div class="title m-b-md">
                Restaurant Name
            </div>
            <div class="links">
                <a href="Restro/admin/">Admin Log In</a>
                <a href="Restro/cashier/">Cashier Log In</a>
                <a href="Restro/customer">Customer Log In</a>
            </div>
        </div>
    </div>
</body>
</html>