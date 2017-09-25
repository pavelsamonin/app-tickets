<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 14:58
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

                        <h3><?php echo @$data['user']; ?></h3>
                        <table class="table">
                            <thead class="thead-inverse">
                            <tr>
                                <th>#</th>
                                <th>CustomerId</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach (@$data['transactions'] as $transaction) {
                                ?>
                                <tr data-id="<?php echo $transaction->id; ?>"
                                    data-amount="<?php echo $transaction->amount; ?>">
                                    <th scope="row"><?php echo $transaction->id; ?></th>
                                    <td><?php echo $transaction->customerId; ?></td>
                                    <td><?php echo $transaction->amount; ?></td>
                                    <td><?php echo $transaction->date; ?></td>
                                    <td>
                                        <p data-placement="top" data-toggle="tooltip" title="Edit">
                                            <button class="btn btn-warning btn-xs" data-title="Edit" data-toggle="modal"
                                                    data-target="#edit" data-method="edit"
                                                    data-attr="<?php echo $transaction->id; ?>">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </button>
                                        </p>
                                    </td>
                                    <td>
                                        <p data-placement="top" data-toggle="tooltip" title="Delete">
                                            <button class="btn btn-danger btn-xs" data-title="Delete"
                                                    data-toggle="modal"
                                                    data-target="#delete" data-method="delete"
                                                    data-attr="<?php echo $transaction->id; ?>">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        </p>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>

                    </div>

                    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                                class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                    <h4 class="modal-title custom_align" id="Heading">Edit Your Detail</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input class="form-control" id="amount" name="amount" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="id">TransactionId</label>
                                        <input class="form-control" id="id" name="name" type="text" disabled>
                                    </div>
                                </div>
                                <div class="modal-footer ">
                                    <button type="button" id="editSubmit" class="btn btn-warning btn-lg"
                                            style="width: 100%;"><span
                                                class="glyphicon glyphicon-ok-sign"></span> Update
                                    </button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>


                    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                                class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                    <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="alert alert-danger"><span
                                                class="glyphicon glyphicon-warning-sign"></span> Are you sure you
                                        want to delete this Record?
                                    </div>

                                </div>
                                <div class="modal-footer ">
                                    <button type="button" id="deleteSubmit" class="btn btn-success"><span
                                                class="glyphicon glyphicon-ok-sign"></span> Yes
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span
                                                class="glyphicon glyphicon-remove"></span> No
                                    </button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>