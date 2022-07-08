<?php
  $PAGE_TITLE = "Login";
  $ADDITIONAL_STYLESHEETS = '';
  $ADDITIONAL_SCRIPTS = '';
  include '../protected/adminTop.php';
?>

<div class="p-5">
  <form>
    <div class="form-group row">
      <label for="username" class="col-sm-2 col-form-label"><i class="fas fa-user"></i> Username</label>
      <div class="col-sm-10">
        <input type="text" class="username form-control" id="username" placeholder="Username">
      </div>
    </div>
    <div class="form-group row">
      <label for="password" class="col-sm-2 col-form-label"><i class="fas fa-lock"></i> Password</label>
      <div class="col-sm-10">
        <input type="password" class="password form-control" id="password" placeholder="Password">
      </div>
    </div>
    <button type="button" class="login btn btn-primary mb-2">Login</button>
  </form>
</div>

<script src="js/page/admin_login.js"></script>
</body>
</html>
