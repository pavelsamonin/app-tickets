<?php
/**
 * Created by PhpStorm.
 * User: samonin
 * Date: 22.09.17
 * Time: 15:06
 */
?>
<form id="contact-form" method="post" action="/isoft/login.php" role="form">

    <div class="messages"></div>

    <div class="controls">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="login">Login *</label>
                    <input id="login" type="text" name="login" class="form-control" placeholder="Please enter your login *" required="required" data-error="Login is required.">
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input id="password" type="text" name="password" class="form-control" placeholder="Please enter your password *" required="required" data-error="Password is required.">
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="submit" class="btn btn-success btn-send" value="Log in">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="text-muted"><strong>*</strong> These fields are required.</p>
            </div>
        </div>
    </div>

</form>
