const url = 'wss://files.aposteriori.com.sg:8080/files'
// const url = 'ws://localhost:8080/files'

var ws = new function() {
  var self = this;

  this.connect = function() {
    self.connection = new WebSocket(url);
    self.connection.onopen = self.onopen;
    self.connection.onerror = self.onerror;
    self.connection.onmessage = self.onmessage;
  };

  // Register with server using classCode when connected
  this.onopen = function() {
    var data = {
      type: 'register',
      classCode: readGET('class')
    }
    if (readGET('user')) {
      data.teacher = true;
    } else {
      data.teacher = false;
    }
    self.connection.send(JSON.stringify(data));
  };

  // Reconnect on close
  this.onclose = function(e) {
    console.log('Socket is closed. Reconnect will be attempted in 5 second.', e.reason);
    setTimeout(self.connect, 5000);
  };

  // Display error on console
  this.onerror = function(error) {
    console.log('WebSocket error');
    console.log(error);
  };

  // Receive message and pass it to handler
  this.onmessage = function(e) {
    var data = JSON.parse(e.data);

    if (data.type == 'addedFile') {
      self.onAddedFile(data);
    }
  };

  // Empty handler for messages. To be filled.
  this.onAddedFile = function(data) {};

  // Notify server that a file has been added
  this.sendAddedFile = function() {
    var data = {
      type: 'addedFile'
    }
    if (readGET('user')) {
      data.teacher = true;
    } else {
      data.teacher = false;
    }
    self.connection.send(JSON.stringify(data));
  };
}

$(document).ready(ws.connect);
