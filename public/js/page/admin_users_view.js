/* global USERS_ROLE_DESCRIPTION */

var admin_users_view = new function() {
  var self = this;

  // Run on page load
  this.init = function() {
    self.$tbody = $('tbody');
    self.$addUser = $('.addUser');

    self.$addUser.click(function() { window.location = 'admin_users_edit.php'; });

    self.loadUsers();
  };

  // Load users
  this.loadUsers = function() {
    showSpinner();
    var request = {
      Users_getAll: null
    };
    ajaxRequest2.handler['Users_getAll'] = self.users_getAll;
    ajaxRequest2.request(request);
  };

  // Draw one row
  this.drawUser = function(user) {
    var $user = $(
      '<tr></tr>'
    );

    var $name = $('<td></td>');
    $name.text(user.name);

    var $code = $('<td></td>');
    if (user.code) {
      $code.text(HOST + 'teacher.php?user=' + user.code);
    }

    var $roles = $('<td></td>');
    var rolesString = '';
    for (var i=0; i<user.roles.length; i+=2) {
      rolesString += USERS_ROLE_DESCRIPTION[user.roles.slice(i, i+2)] + ',';
    }
    rolesString = rolesString.slice(0, -1);
    $roles.text(rolesString);

    var $action = $(
      '<td>' +
      '<i class="fas fa-edit clickable"></i>&nbsp;&nbsp;' +
      '<i class="fas fa-trash-alt clickable"></i>' +
      '</td>'
    );
    $action.find('.fa-edit').click(function() { window.location = 'admin_users_edit.php?userKey='+user.userKey; });
    $action.find('.fa-trash-alt').click(function() {
      confirmDialog('Delete User: <strong class="text-danger">'+user.name+'</strong>', function() { self.deleteUser(user.userKey); });
    });

    $user.append($name);
    $user.append($code);
    $user.append($roles);
    $user.append($action);

    return $user;
  };

  // Load into table
  this.users_getAll = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.$tbody.empty();
    for (var i=0; i<data.users.length; i++) {
      self.$tbody.append(self.drawUser(data.users[i]));
    }
  };

  // Delete user
  this.deleteUser = function(userKey) {
    showSpinner();

    var request = {
      Users_delete: {
        userKey: userKey
      }
    };
    ajaxRequest2.handler['Users_delete'] = function(data) {
      if (data.status == 'OK') {
        toastMsg('User Deleted');
        self.loadUsers();
      } else {
        showErrorModal(data.errorMsg);
      }
    };
    ajaxRequest2.request(request);
  };
};

// Init page class
$(document).ready(admin_users_view.init);