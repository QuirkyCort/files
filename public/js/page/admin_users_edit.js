/* global USERS_ROLE_DESCRIPTION */

var admin_users_edit = new function() {
  var self = this;

  // Run on page load
  this.init = function() {
    self.$name = $('#name');
    self.$code = $('#code');
    self.$password = $('#password');
    self.$roles = $('.rolesFlex');
    self.$createButton = $('button.create');
    self.$editButton = $('button.edit');
    self.$back = $('button.back');
    self.$back.click(function() { window.location = 'admin_users_view.php'; });

    self.$createButton.click(self.createUser);
    self.$editButton.click(function() { confirmDialog('Amend User?', self.editUser); });

    self.userKey = readGET('userKey');

    if (self.userKey) {
      showSpinner();
      var request = {
        Users_get: {
          userKey: self.userKey
        }
      };
      ajaxRequest2.handler['Users_get'] = self.users_get;
      ajaxRequest2.request(request);
    }
  };

  // Create a new user
  this.createUser = function() {
    showSpinner();

    var roles = '';
    self.$roles.find('input:checked').each(function() { roles += $(this).val(); });

    var request = {
      Users_add: {
        name: self.$name.val(),
        code: self.$code.val(),
        password: self.$password.val(),
        roles: roles
      }
    };
    ajaxRequest2.handler['Users_add'] = function(data) {
      if (data.status == 'OK') {
        acknowledgeDialog('New User Created', function() { window.location = 'admin_users_view.php'; } );
      } else {
        showErrorModal(data.errorMsg);
      }
    };
    ajaxRequest2.request(request);
  };

  // Edit an existing user
  this.editUser = function() {
    showSpinner();

    var roles = '';
    self.$roles.find('input:checked').each(function() { roles += $(this).val(); });

    var request = {
      Users_edit: {
        userKey: self.userKey,
        name: self.$name.val(),
        code: self.$code.val(),
        password: self.$password.val(),
        roles: roles
      }
    };
    ajaxRequest2.handler['Users_edit'] = function(data) {
      if (data.status == 'OK') {
        acknowledgeDialog('User\'s details updated', function() { window.location = 'admin_users_view.php'; } );
      } else {
        showErrorModal(data.errorMsg);
      }
    };
    ajaxRequest2.request(request);
  };

  // Load into form
  this.users_get = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.$name.val(data.user.name);
    self.$code.val(data.user.code);
    for (var role in USERS_ROLE_DESCRIPTION) {
      for (var i=0; i<data.user.roles.length; i+=2) {
        if (data.user.roles.slice(i, i+2) == role) {
          self.$roles.find('input[value='+role+']').prop('checked', true);
          break;
        }
      }
    }
  };
};

// Init page class
$(document).ready(admin_users_edit.init);