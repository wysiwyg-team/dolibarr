<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body>
<form action="jsonResult.php" method="post">
    <label for="from">From: </label>
    <input type="text" id="datepicker1" class="datepicker" name="from">

    <label for="to">To: </label>
    <input type="text" id="datepicker2" class="datepicker" name="to">
    <input type="submit">
</form>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
//        changeMonth: true,
//        changeYear: true
    });
</script>
</body>
</html>


