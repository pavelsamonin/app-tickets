<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 13:07
 */
?>
<div id="wrapper" class="active">

    <!-- Sidebar -->
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul id="sidebar_menu" class="sidebar-nav">
            <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon"
                                                                             class="glyphicon glyphicon-align-justify"></span></a>
            </li>
        </ul>
        <ul class="sidebar-nav" id="sidebar">
            <li><a href="/main/showCustomers">showCustomers<span class="sub_icon glyphicon glyphicon-link"></span></a>
            </li>
            <li><a href="/main/showTransactions">showTransactions<span
                            class="sub_icon glyphicon glyphicon-link"></span></a></li>
        </ul>
    </div>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <!-- Keep all page content within the page-content inset div! -->
        <div class="page-content inset">
            <div class="container">

                <div class="row">

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


