<?php
  include 'top.php';
?>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">A Posteriori Files Sharing</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="admin.php">Home</a>
        </li>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="admin_users_view.php">Users</a>
        </li>
        <?php
          if (isset($USER)) {
        ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo $USER['name']; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="logout dropdown-item" href="#">Logout</a>
            </div>
          </li>
        <?php
          } else {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="admin_login.php">Login</a>
          </li>
        <?php
          }
        ?>
      </ul>
    </div>
  </nav>

<script>
  var topPage = new function() {
    var self = this;

    this.$logout = $('nav .logout');

    // Run on page load
    this.init = function() {
      self.$logout.click(self.logout);
      self.markCurrentPage();
    };

    // Set nav item to grey if it is listing the current page
    this.markCurrentPage = function() {
      let currentFile = window.location.pathname.replace(/^.*\//,'');
      $('.nav-item').each(function(i, ele) {
        if (ele.innerHTML.indexOf(currentFile) != -1) {
          $(ele).addClass('active');
        }
      });
    };

    // Logout
    this.logout = function() {
      showSpinner();

      var request = {
        Logout: null
      };
      ajaxRequest2.handler['Logout'] = function(data) {
        if (data.status == 'OK') {
          acknowledgeDialog('You have logged out', function() { window.location = 'admin.php'; } );
        } else {
          showErrorModal(data.errorMsg);
        }
      };
      ajaxRequest2.request(request);
    }
  }

  // Init page class
  $(document).ready(topPage.init);
</script>
