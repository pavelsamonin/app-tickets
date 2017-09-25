<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 13:02
 */
?>
<html>
<head>
    <?php
    $var = $_SERVER['HTTP_HOST'] == 'localhost:8888' ? "/isoft" : "";
    ?>
    <title><?php echo $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=$var?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
</head>
<script>
    var token = window.sessionStorage.token ? window.sessionStorage.token : null;
    if(window.location.href != 'http://localhost:8888/isoft/' && window.location.href != 'http://localhost:8888/isoft/main/'){
        if(token == null)
            document.location.replace('http://localhost:8888/isoft');
    }
</script>
