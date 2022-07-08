const HOST = 'files.aposteriori.com.sg';
// const HOST = 'localhost';
const PORT = 8080;
const PATH = '/files';

const fs = require('fs');
const https = require('https');
const WebSocket = require('ws')

const server = https.createServer({
  cert: fs.readFileSync('/etc/letsencrypt/live/files.aposteriori.com.sg/cert.pem'),
  key: fs.readFileSync('/etc/letsencrypt/live/files.aposteriori.com.sg/privkey.pem')
});

const wss = new WebSocket.Server({ server });

wss.on('connection', function(ws) {
  ws.on('message', function(data) {
    handleIncoming(data, ws);
  });
  ws.on('pong', hit);
});

function handleIncoming(data, sender) {
  data = JSON.parse(data);
  if (data.type == 'register') {
    register(data, sender);
  } else if (data.type == 'addedFile') {
    notifyNewFile(data, sender);
  } else {
    console.log('Unrecognized data type');
    console.log(data);
  }
};

function register(data, sender) {
  sender.classCode = data.classCode;
  sender.teacher = data.teacher;
}

function notifyNewFile(data, sender) {
  let dataStr = JSON.stringify(data);

  wss.clients.forEach(function(client) {
    if (client !== sender && client.readyState === WebSocket.OPEN) {
      if (client.classCode == sender.classCode) {
        if (data.teacher == true) {
          client.send(dataStr);
        } else if (client.teacher == true) {
          client.send(dataStr);
        }
      }
    }
  });
}

function hit() {
  this.strikes = 0;
}

const interval = setInterval(function() {
  wss.clients.forEach(function(ws) {
    if (ws.strikes === 3) {
      ws.terminate();
    }

    ws.strikes++;
    ws.ping();
  });
}, 30000);

wss.on('close', function close() {
  clearInterval(interval);
});

server.listen(8080);