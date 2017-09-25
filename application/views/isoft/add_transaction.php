<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 15:02
 */
?>
<body>
<script>var route = 'addTransaction';</script>
<div class="container">

    <div class="row">

        <div class="col-lg-8 col-lg-offset-2">

            <h1>Add transaction</h1>

            <form id="contact-form" method="post" role="form">

                <div class="controls">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customerId">CustomerId *</label>
                                <input id="customerId" type="text" name="customerId" class="form-control" placeholder="Please enter the CustomerId *" required="required" data-error="Name is required.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount *</label>
                                <input id="amount" type="text" name="amount" class="form-control" placeholder="Please enter Amount *" required="required" data-error="CNP is required.">
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