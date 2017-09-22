<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 14:58
 */
?>
<div class="col-lg-8 col-lg-offset-2">

    <h1><?php echo @$data['head']; ?></h1>

    <table class="table">
        <thead class="thead-inverse">
        <tr>
            <th>#</th>
            <th>CustomerId</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach (@$data['transactions'] as $ctransaction) {
            ?>
            <tr>
                <th scope="row"><?php echo $ctransaction['id']; ?></th>
                <td><?php echo $ctransaction['customerId']; ?></td>
                <td><?php echo $ctransaction['amount']; ?></td>
                <td><?php echo $ctransaction['date']; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

</div>