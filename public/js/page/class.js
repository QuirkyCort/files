var classPage = new function() {
  var self = this;

  // Run on page load
  this.init = function() {
    self.dragEnterCount = 0;
    self.$classDescription = $('.classDescription');
    self.$usernameBtn = $('#usernameDisplay');
    self.$getLink = $('.getLink');
    self.$username = $('#usernameDisplay > span');
    self.$teachers = $('table.teachers tbody');
    self.$students = $('table.students tbody');
    self.$addClass = $('.addClass');
    self.$fileInput = $('#upload');
    self.$dropArea = $('.dropArea');
    self.$progressArea = $('.progressArea');

    self.$addClass.click(self.addClass);
    self.$fileInput.change(function(){
      self.uploadFile(self.$fileInput[0].files);
    });
    self.$dropArea[0].addEventListener('dragover', function(e) {
      e.stopPropagation();
      e.preventDefault();
    }, false);
    self.$dropArea[0].addEventListener('dragenter', function(e) {
      self.$dropArea.addClass('hover');
      self.dragEnterCount++;
    }, false);
    self.$dropArea[0].addEventListener('dragleave', function(e) {
      self.dragEnterCount--;
      if (self.dragEnterCount <= 0) {
        self.dragEnterCount = 0;
        self.$dropArea.removeClass('hover');
      }
    }, false);
    self.$dropArea[0].addEventListener('drop', function(e) {
      self.dragEnterCount = 0;
      self.$dropArea.removeClass('hover');
      e.stopPropagation();
      e.preventDefault();
      self.uploadFile(e.dataTransfer.files);
    }, false);

    self.$getLink.click(self.displayLink);
    self.$usernameBtn.click(self.requestName);

    self.$username.text(localStorage.getItem('name'));
    if (self.$username.text() == '' && readGET('user') == null) {
      self.requestName();
    }

    self.selfGenID = localStorage.getItem('selfGenID');
    if (! self.selfGenID) {
      self.selfGenID = self.randString(10);
      localStorage.setItem('selfGenID', self.selfGenID);
    }

    self.loadFiles();

    self.lostFocusTime = 0;
    ws.onAddedFile = self.loadFiles;
    document.addEventListener('visibilitychange', function(e) {
      if (document.visibilityState == 'visible') {
        document.title = 'A Posteriori Files';
        setTimeout(self.removeNew, 3000);
      } else {
        self.lostFocusTime = Date.now();
      }
    });
    self.timer = setInterval(self.updateTime, 15 * 1000);
  };

  // Remove highlight from new files
  this.removeNew = function(){
    $('tr.new').each(function(i, tr) {
      tr.classList.remove('new');
    });
  }

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

  // Display Link to page
  this.displayLink = function() {
    var URL  = 'https://a9i.sg/files?class=' + readGET('class');
    var URL2  = 'https://a9i.sg/mfiles?class=' + readGET('class');
    var $body = $(
      '<div>' +
        '<p>List:<br><span>' + URL + '</span>&nbsp;&nbsp;<i class="far fa-copy copy" data-original-title="Copied!"></i></p>' +
        '<p>Icon:<br><span>' + URL2 + '</span>&nbsp;&nbsp;<i class="far fa-copy copy2" data-original-title="Copied!"></i></p>' +
      '</div>'
    );

    var $copy = $body.find('.copy');
    $copy.tooltip({
      trigger: 'click',
      placement: 'top'
    });
    $copy.click(function() {
      $copy.tooltip('show');
      setTimeout(function() {
        $copy.tooltip('hide');
      }, 500);

      let $textarea = $('<textarea style="position: absolute; top: -9999px; left: -9999px;"></textarea>');
      $('body').append($textarea);
      $textarea.val(URL);
      $textarea[0].select();
      $textarea[0].setSelectionRange(0, 99999); /*For mobile devices*/
      document.execCommand("copy");
      $('body').remove($textarea);
    });

    var $copy2 = $body.find('.copy2');
    $copy2.tooltip({
      trigger: 'click',
      placement: 'top'
    });
    $copy2.click(function() {
      $copy2.tooltip('show');
      setTimeout(function() {
        $copy2.tooltip('hide');
      }, 500);

      let $textarea = $('<textarea style="position: absolute; top: -9999px; left: -9999px;"></textarea>');
      $('body').append($textarea);
      $textarea.val(URL2);
      $textarea[0].select();
      $textarea[0].setSelectionRange(0, 99999); /*For mobile devices*/
      document.execCommand("copy");
      $('body').remove($textarea);
    });

    var options = {
      title: 'Link for Students',
      message: $body
    };
    acknowledgeDialog(options);
  };

  // Ask user for name
  this.requestName = function() {
    var $body = $(
      '<p>Please provide your name (so the teacher can know who uploaded the file)</p>' +
      '<input type="text" id="nameInput"></input>'
    );
    var options = {
      title: 'Your Name',
      message: $body
    };
    $body.siblings('#nameInput').val(self.$username.text());

    acknowledgeDialog(options, function() {
      var name = $('#nameInput').val().trim();
      if (name == '') {
        self.requestName();
      } else {
        self.$username.text(name);
        localStorage.setItem('name', name);
      }
    });
  };

  // Upload file
  this.uploadFile = function(files) {
    var request = {
      Files_add: {
        classCode: readGET('class'),
        userCode: readGET('user'),
        userName: self.$username.text(),
        selfGenID: self.selfGenID
      }
    };
    ajaxRequest2.handler['Files_add'] = self.files_add;

    var $progress;
    var start = function() {
      $progress = addProgressSpinner(self.$progressArea);
    };
    var progress = function(e) {
      $progress.progress(100 * e.loaded / e.total);
    };
    var completed = function(e) {
      $progress.completed();
    };
    ajaxRequest2.requestMulti(request, files, start, progress, completed);
  }

  // Display result of upload
  this.files_add = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    toastMsg('Files Uploaded');
    ws.sendAddedFile();
    self.loadFiles();
  };

  // Load Files
  this.loadFiles = function() {
    var request = {
      Classes_get: {
        classCode: readGET('class')
      },
      Files_getAll: {
        classCode: readGET('class'),
        userCode: readGET('user'),
        selfGenID: self.selfGenID
      }
    };
    ajaxRequest2.handler['Classes_get'] = self.classes_get;
    ajaxRequest2.handler['Files_getAll'] = self.files_getAll;
    ajaxRequest2.request(request);
  };

  // Show class name
  this.classes_get = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.$classDescription.text(data.class.description);
  }

  // Format date nicely
  this.formatDate = function(unixTime) {
    let now = Date.now() / 1000;

    if ((now - unixTime) < 60) {
      return '< 1 min ago';
    } else if ((now - unixTime) < 90) {
      return '1 min ago';
    } else if ((now - unixTime) < 3600) {
      return Math.round((now - unixTime) / 60) + ' mins ago';
    } else if ((now - unixTime) < 3630) {
      return '1 hr ago';
    } else if ((now - unixTime) < 7200) {
      return '1 hr and ' + Math.round((now - unixTime - 3600) / 60) + ' mins ago';
    } else {
      var date = new Date(unixTime * 1000);

      var hr = date.getHours();
      var min = ('0' + date.getMinutes()).substr(-2);
      var period = 'AM';
      if (hr == 12) {
        period = 'PM';
      } else if (hr > 12) {
        hr -= 12;
        period = 'PM';
      } else if (hr == 0) {
        hr = 12;
      }
      var timeStr = hr + ':' + min + ' ' + period;

      var dateStr = unixTimeToDateString(unixTime);
      var todayStr = unixTimeToDateString(now);

      if (dateStr == todayStr) {
        dateStr = 'Today';
      }

      return timeStr + ', ' + dateStr;
    }
  };

  // Toggle share
  this.toggleShare = function(fileKey) {
    showSpinner();
    var request = {
      Files_toggleShare: {
        classCode: readGET('class'),
        userCode: readGET('user'),
        fileKey: fileKey
      }
    };
    ajaxRequest2.handler['Files_toggleShare'] = self.files_toggleShare;
    ajaxRequest2.request(request);
  };

  // Display result
  this.files_toggleShare = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    ws.sendAddedFile();
    self.loadFiles();
  };

  // Delete file
  this.deleteFile = function(fileKey) {
    confirmDialog('Delete File?', function(){
      showSpinner();
      var request = {
        Files_delete: {
          classCode: readGET('class'),
          userCode: readGET('user'),
          selfGenID: self.selfGenID,
          fileKey: fileKey
        }
      };
      ajaxRequest2.handler['Files_delete'] = self.files_delete;
      ajaxRequest2.request(request);
    });
  };

  // Display result
  this.files_delete = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    ws.sendAddedFile();
    self.loadFiles();
  };

  // Update the time using store unix time
  this.updateTime = function() {
    $('[unixTime]').each(function(i, ele) {
      ele.textContent = self.formatDate(parseInt(ele.attributes.unixTime.value));
    });
  };

  // Get file extension
  this.getExtension = function(filename) {
    return filename.split('.').pop();
  };

  // Draw one row
  this.drawTeachersFile = function(file) {
    var $file = $(
      '<tr></tr>'
    );
    if (document.visibilityState != 'visible' && file.date > self.lostFocusTime / 1000) {
      $file.addClass('new');
    } else if (file.date > Date.now() / 1000 - 2) {
      $file.addClass('new');
      setTimeout(self.removeNew, 3000);
    }

    var $filename = $('<td><a href=""></a><div class="delete"></div></td>');
    $filename.find('a').text(file.fileName);
    $filename.find('a').prop('href', HOST + 'get/' + file.fileKey + '.' + self.getExtension(file.fileName));
    if (readGET('user')) {
      $filename.find('.delete').text('Delete');
      $filename.find('.delete').click(function() {
        self.deleteFile(file.fileKey);
      });
    }

    var $date = $('<td></td>');
    $date.attr('unixTime', file.date);
    $date.text(self.formatDate(file.date));

    $file.append($filename);
    $file.append($date);

    return $file;
  };

  // Draw one row
  this.drawStudentsFile = function(file) {
    var $file = $(
      '<tr></tr>'
    );
    if (document.visibilityState != 'visible' && file.date > self.lostFocusTime / 1000) {
      $file.addClass('new');
    } else if (file.date > Date.now() / 1000 - 2) {
      $file.addClass('new');
      setTimeout(self.removeNew, 3000);
    }

    var $filename = $('<td><a href=""></a><div class="shareState"></div><div class="share"></div><div class="delete"></div></td>');
    $filename.find('a').text(file.fileName);
    $filename.find('a').prop('href', HOST + 'get/' + file.fileKey + '.' + self.getExtension(file.fileName));
    if (readGET('user')) {
      if (file.share == 1) {
        $filename.find('.shareState').text('(Shared with students)');
        $filename.find('.shareState').addClass('unshare');
        $filename.find('.share').text('Unshare');
      } else {
        $filename.find('.shareState').text('(Hidden from students)');
        $filename.find('.share').text('Share');
      }
      $filename.find('.share').click(function() {
        self.toggleShare(file.fileKey);
      });
    }
    if (readGET('user') || file.own) {
      $filename.find('.delete').text('Delete');
      $filename.find('.delete').click(function() {
        self.deleteFile(file.fileKey);
      });
    }

    var $date = $('<td></td>');
    $date.attr('unixTime', file.date);
    $date.text(self.formatDate(file.date));

    var $userName = $('<td></td>');
    $userName.text(file.userName);

    $file.append($filename);
    $file.append($date);
    $file.append($userName);

    return $file;
  };

  // Load into table
  this.files_getAll = function(data) {
    if (data.status != 'OK') {
      showErrorModal(data.errorMsg);
      return;
    }
    self.$teachers.empty();
    self.$students.empty();
    for (var i=0; i<data.teachers.length; i++) {
      self.$teachers.append(self.drawTeachersFile(data.teachers[i]));
    }
    for (var i=0; i<data.students.length; i++) {
      self.$students.append(self.drawStudentsFile(data.students[i]));
    }

    var newFiles = $('tr.new').length;
    if (newFiles > 0 && document.visibilityState != 'visible') {
      document.title = 'A Posteriori Files (' + newFiles + ')';
    } else {
      document.title = 'A Posteriori Files';
    }
  };
};

function addProgressSpinner($container) {
  var $div = $(
    '<div class="progressSpinner">' +
      '<div class="progressCircle"></div>' +
      '<i class="fas fa-sync fa-spin progressSpinner"></i>' +
    '</div>'
  );
  var $progressCircle = $div.find('.progressCircle');

  $div.progress = function(percent) {
    $progressCircle.css('width', percent + '%');
    $progressCircle.css('height', percent + '%');
  };

  $div.completed = function() {
    $div.remove();
  }

  $container.append($div);
  return $div;
}

// Init page class
$(document).ready(classPage.init);