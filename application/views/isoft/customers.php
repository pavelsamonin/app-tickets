<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 13:07
 */
?>
<div class="col-lg-8 col-lg-offset-2">

    <h1><?php echo @$data['head']; ?></h1>

    <table class="table">
        <thead class="thead-inverse">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>CNP</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (@$data['customers'] as $customer) {
            ?>
            <tr>
                <th scope="row"><?php echo $customer->id; ?></th>
                <td><?php echo $customer->name; ?></td>
                <td><?php echo $customer->cnp; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

</div>


