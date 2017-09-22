<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 13:07
 */
var_dump($data);
?>
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
    foreach ($data as $customer) {
        ?>
        <tr>
            <th scope="row"><?php echo $customer['id']; ?></th>
            <td><?php echo $customer['name']; ?></td>
            <td><?php echo $customer['cnp']; ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>

