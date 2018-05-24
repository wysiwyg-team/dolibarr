<?php
/* Copyright (C) 2016       Olivier Geffroy		<jeff@jeffinfo.com>
 * Copyright (C) 2016       Florian Henry		<florian.henry@open-concept.pro>
 * Copyright (C) 2016-2017  Alexandre Spangaro	<aspangaro@zendsi.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
include 'php_xml.php';

/**
 *  \file        htdocs/local/custom/customApi.php
 *  \brief        Api import facture from glpi to dolibarr
 */

require '../../main.inc.php';

// Class
require_once DOL_DOCUMENT_ROOT . '/core/lib/accounting.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT . '/accountancy/class/bookkeeping.class.php';
require_once DOL_DOCUMENT_ROOT . '/accountancy/class/accountancyexport.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formaccounting.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';

// Langs
$langs->load("accountancy");
$title_page = $langs->trans("Glpi Import");
llxHeader('', $title_page);

?>

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
<div class="container">
    <div class="div-table-responsive liste_titre liste_titre_bydiv centpercent">
        <form action="form.php" method="post" id="datefilter">
            <div id="msg">Enter date range:</div>
            <label for="from">From: </label>
            <input type="text" id="datepicker1" class="datepicker" name="from">

            <label for="to">To: </label>
            <input type="text" id="datepicker2" class="datepicker" name="to">
            <button type="submit">Submit</button>

        </form>
    </div>

    <div class="div-table-responsive liste_titre liste_titre_bydiv centpercent">
        <span id="message"></span>
        <form method="post" id="import_form" enctype="multipart/form-data">
            <label for="xmlInput">Select XML file</label>
            <input type="file" name="file" id="file" required>
            <input type="submit" name="submit" id="submit" value="Import">
    </div>


</div>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
//        changeMonth: true,
//        changeYear: true
    });

    $("#datefilter").submit(function () {
        $("#msg").text("Submitted Successfully");
    });

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
<?php

$xml = new php_xml();
$xml->getRecords();


llxFooter();

$db->close();

?>

