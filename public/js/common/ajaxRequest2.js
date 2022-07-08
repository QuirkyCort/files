/* exported ajaxRequest2 */

var ajaxRequest2 = {
  handler: {},
  internalHandler: {},
  path: 'ajax/request.php',

  // Set ajax request
  request: function(data, failHandler) {
    if (typeof failHandler == 'undefined') {
      failHandler = function(status) {
        hideSpinner();
        console.log(status);
        toastMsg('Unable to connect. Please check your connection.');
      };
    }

    var param = {
      data: {
        input: JSON.stringify(data)
      },
      type: 'POST',
      url: HOST + ajaxRequest2.path,
      xhrFields: {
        withCredentials: true
      },
      timeout: 20000
    };

    if (typeof CSRF !== 'undefined') {
      param.data.CSRF = CSRF;
    }

    return $.ajax(param)
      .done(ajaxRequest2.process)
      .fail(failHandler);
  },

  // Multipart Ajax request
  requestMulti: function (data, files, onStart, onProgress, onloadend) {
    if (typeof onStart === 'undefined') {
      onStart = function () {
        console.log('Upload Started');
      };
    }
    if (typeof onProgress === 'undefined') {
      onProgress = function (e) {
        if (e.lengthComputable) {
          console.log(e.loaded + ' of ' + e.total);
        }
      };
    }
    var formData = new FormData();

    formData.append('input', JSON.stringify(data));

    if (typeof CSRF !== 'undefined') {
      formData.append('CSRF', CSRF);
    }

    // files.forEach(function($ele) {
    //   formData.append('files[]', $ele[0].files[0]);
    // });
    for (let i=0; i<files.length; i++) {
      formData.append('files[]', files[i]);
    }
    // files.forEach(function(file) {
    //   formData.append('files[]', file);
    // });

    var param = {
      url: HOST + ajaxRequest2.path,
      data: formData,
      method: 'POST',
      contentType: false,
      processData: false,
      xhrFields: {
        withCredentials: true
      },
      xhr: function () {
        var xhr = $.ajaxSettings.xhr();
        xhr.upload.onprogress = onProgress;
        xhr.upload.onloadend = onloadend;
        return xhr;
      }
    };

    var ajaxObj = $.ajax(param)
      .done(ajaxRequest2.process)
      .fail(function() {
        hideSpinner();
        toastMsg('Unable to connect. Please check your connection.');
      });
    onStart(ajaxObj);
  },

  // Process ajax response, triggering the individual handlers
  process: function(data) {
    hideSpinner();

    if (typeof data === 'undefined' || data === null) {
      showErrorModal('Empty reply from server');
      return;
    }

    if (typeof data.errorMsg !== 'undefined') {
      showErrorModal([data.errorMsg]);
      return;
    }

    var keys;
    if (typeof data.order !== 'undefined') {
      keys = data.order;
    } else {
      keys = [];
      for (var key in data) {
        if (key !== 'order') {
          keys.push(key);
        }
      }
    }

    for (var responseName of keys) {
      if (typeof ajaxRequest2.handler[responseName] !== 'undefined') {
        ajaxRequest2.handler[responseName](data[responseName]);
      } else {
        console.log('ajaxHandler: No handler found ('+responseName+')');
      }
    }
  }

};

// ajaxRequest2.internalHandler['Users_getMyDetails'] = function(data) {
//   if (data.status == 'OK') {
//     CSRF = data.CSRF;

//     if (data.guest) {
//       localStorage.removeItem('CSRF');
//     } else {
//       localStorage.setItem('CSRF', data.CSRF);
//     }
//   }
// };