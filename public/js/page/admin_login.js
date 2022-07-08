var admin_login = new function() {
  var self = this;

  // Run on page load
  this.init = function() {
    self.$username = $('.username');
    self.$password = $('.password');
    self.$login = $('.login');

    self.$login.click(self.login);
  };

  // Load into table
  this.login = function() {
    showSpinner();
    var request = {
      Login: {
        name: self.$username.val(),
        password: self.$password.val()
      }
    };
    ajaxRequest2.handler['Login'] = self.login_result;
    ajaxRequest2.request(request);
  };

  // Process result of login
  this.login_result = function(data) {
    if (data.status == 'OK') {
      window.location = 'admin.php';
    } else {
      toastMsg('Login Failed');
    }
  };
};

// Init page class
$(document).ready(admin_login.init);