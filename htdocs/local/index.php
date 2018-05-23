<?php
/**
 * Created by PhpStorm.
 * User: Ankush
 * Date: 4/30/2018
 * Time: 5:57 PM
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <span id="message"></span>
            <form method="post" id="import_form" enctype="multipart/form-data">
                <label for="xmlInput">Select XML file</label>
                <input type="file" name="file" id="file">
                <input type="submit" name="submit" id="submit" value="Import">
            </form>
        </div>
    </div>
</div>

<script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#import_form').on('submit', function(event){
            event.preventDefault();

            $.ajax({
                url:"customImport.php",
                method:"POST",
                data: new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                beforeSend:function(){
                    $('#submit').attr('disabled','disabled'),
                        $('#submit').val('Importing...');
                },
                success:function(data)
                {
                    $('#message').html(data);
                    $('#import_form')[0].reset();
                    $('#submit').attr('disabled', false);
                    $('#submit').val('Import');
                }
            })

        });
    });
</script>
</body>
</html>
