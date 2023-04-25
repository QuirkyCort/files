var teacher = new function() {
  var self = this;

  // Run on page load
  this.init = function() {
    self.$tbody = $('tbody');
    self.$addClass = $('.addClass');

    self.$addClass.click(self.addClass);

    self.loadClasses();
  };

  // Random string
  this.randString = function(length) {
    var result           = '';
    var characters       = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
       result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  // Add a new class
  this.addClass = function() {
    var code = self.randString(3);
    var $body = $(
      '<input type="text" class="classDescription" placeholder="Description of class"></input>'
    );
    var options = {
      title: 'Add class',
      message: $body
    };

    confirmDialog(options, function() {
      showSpinner();
      var request = {
        Classes_add: {
          userCode: readGET('user'),
          classCode: code,
          description: $('.classDescription').val()
        }
      };
      ajaxRequest2.handler['Classes_add'] = self.classes_add;
      ajaxRequest2.request(request);
    });
  };

  // Load into table
  this.classes_add = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.loadClasses();
  };

  // Load classes
  this.loadClasses = function() {
    showSpinner();
    var request = {
      Classes_getAll: {
        code: readGET('user')
      }
    };
    ajaxRequest2.handler['Classes_getAll'] = self.classes_getAll;
    ajaxRequest2.request(request);
  };

  // Draw one row
  this.drawClass = function(c) {
    var $class = $(
      '<tr></tr>'
    );

    var $description = $('<td><a class="list" href=""></a> <a class="icon" href=""></a></td>');
    $description.find('a.list').text(c.description + ' (List)');
    $description.find('a.list').prop('href', HOST + 'class.php?class=' + c.code + '&user=' + readGET('user'));
    $description.find('a.icon').text('(Icon)');
    $description.find('a.icon').prop('href', HOST + 'mclass.php?class=' + c.code + '&user=' + readGET('user'));

    var $autoShare = $('<td class="autoShare"></td>');
    if (c.properties != null && c.properties.includes(CLASSES_PROPERTIES_AUTOSHARE)) {
      $autoShare.text('Disable Autoshare');
    } else {
      $autoShare.text('Enable Autoshare');
    }
    $autoShare.click(function(){
      showSpinner();
      var request = {
        Classes_toggleAutoShare: {
          userCode: readGET('user'),
          classCode: c.code
        }
      };
      ajaxRequest2.handler['Classes_toggleAutoShare'] = self.classes_toggleAutoShare;
      ajaxRequest2.request(request);
    });

    var $delete = $('<td class="delete">Delete</td>');
    $delete.click(function(){
      confirmDialog('Delete class? All files in this class will be lost.', function() {
        showSpinner();
        var request = {
          Classes_delete: {
            userCode: readGET('user'),
            classCode: c.code
          }
        };
        ajaxRequest2.handler['Classes_delete'] = self.classes_delete;
        ajaxRequest2.request(request);
      });
    });

    $class.append($description);
    $class.append($autoShare);
    $class.append($delete);

    return $class;
  };

  // Load into table
  this.classes_delete = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.loadClasses();
  };

  // Load into table
  this.classes_toggleAutoShare = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.loadClasses();
  };

  // Load into table
  this.classes_getAll = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.$tbody.empty();
    for (var i=0; i<data.classes.length; i++) {
      self.$tbody.append(self.drawClass(data.classes[i]));
    }
  };
};

// Init page class
$(document).ready(teacher.init);