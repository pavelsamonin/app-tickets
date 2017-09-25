<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 13:03
 */
?>

<?php
$var = $_SERVER['HTTP_HOST'] == 'localhost:8888' ? "/isoft" : "";
?>
<script src="<?=$var?>/assets/js/jquery-3.2.1.min.js"></script>
<script src="<?=$var?>/assets/js/popper.min.js"></script>
<script src="<?=$var?>/assets/js/popper.min.js"></script>
<script src="<?=$var?>/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=$var?>/assets/js/api.js"></script>
<?=@$script?>
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });
</script>
</body>
</html>