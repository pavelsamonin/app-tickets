<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 12:51
 */
?>

<body>

<div class="container">

    <div class="row">

        <div class="col-lg-8 col-lg-offset-2">

            <h1>Add customer</h1>

            <p class="lead">This is a demo for our tutorial dedicated to crafting working Bootstrap contact form with PHP and AJAX background.</p>

            <!-- We're going to place the form here in the next step -->
            <form id="contact-form" method="post" action="/main/addCustomer" role="form">

                <div class="controls">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Customer's name *</label>
                                <input id="name" type="text" name="name" class="form-control" placeholder="Please enter the name *" required="required" data-error="Name is required.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cnp">CNP *</label>
                                <input id="cnp" type="text" name="cnp" class="form-control" placeholder="Please enter CNP *" required="required" data-error="CNP is required.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-success btn-send" value="Create">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted"><strong>*</strong> These fields are required.</p>
                        </div>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>


