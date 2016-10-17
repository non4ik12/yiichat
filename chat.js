var PORT = 8008;
var options = {
    //    'log level': 0
};
var clients = [],
    admins = [],
    clientsInfo = [];
var express = require('express');
var app = express();
var http = require('http');
var server = http.createServer(app);
var io = require('socket.io').listen(server, options);
server.listen(PORT);
app.use('/static', express.static(__dirname + '/static'));
app.get('/', function(req, res) {
    res.sendfile(__dirname + '/index.html');
});
io.on('connection', function(socket) {
    var address = socket.handshake.address,
        ip;
    ip = (address.address === undefined) ? address : address.address;

    function getIP(sockInfo) {
        var address = sockInfo.handshake.address;
        console.log(socket.request.connection.remoteAddress);
        return (address.address === undefined) ? address : address.address;
    }

    function getBrowser() {
        var agent = socket.request.headers['user-agent'],
            browser;
        if (agent.indexOf('Firefox') > -1) {
            browser = 'Firefox';
        } else if (agent.indexOf('MSIE') > -1) {
            browser = 'IE';
        } else if (agent.indexOf('iPad') > -1) {
            browser = 'IPad';
        } else if (agent.indexOf('Android') > -1) {
            browser = 'Android';
        } else if (agent.indexOf('Chrome') > -1) {
            browser = 'Chrome';
        } else if (agent.indexOf('Safari') > -1) {
            browser = 'Safari';
        } else if (agent.indexOf('AIR') > -1) {
            browser = 'Air';
        } else if (agent.indexOf('Fluid') > -1) {
            browser = 'Fluid';
        }
        return browser;
    }

    function showChat(uid) {
        io.to(clients[uid]).emit('show chat', '1');
    }

    function getModerator() {
        var ak = Object.keys(admins);
        return ak[Math.floor(Math.random() * ak.length)];
    }
    socket.on('disconnect', function() {
        // console.log('user disconnected');
    });
    socket.on('to user', function(data) {
        console.log('Admin (' + getIP(socket) + '): Send message to: ' + data.uid);
        io.sockets.to(clients[data.uid]).emit('to user', {
            msg: data.msg,
            uid: data.aid
        });
    });
    socket.on('to moderator', function(data) {
        data.uip = getIP(socket);
        if (data.moderatorUid !== undefined) {
            // Если клиенту назначен модератор
            console.log('User (' + getIP(socket) + '): Send message to defined: ' + data.moderatorUid);
            io.sockets.to(admins[data.moderatorUid]).emit(data);
        } else {
            // Назначаем модератора
            var modUid = getModerator();
            console.log('User (' + getIP(socket) + '): Send message to: ' + modUid);
            io.sockets.to(admins[modUid]).emit("to moderator", data);
        }
    });
    socket.on("userAuth", function(user) {
        if (clients[user.uid] !== undefined) {
            console.log('User (' + getIP(socket) + ') зашел на страницу: ' + user.page);
            showChat(user.uid);
        } else {
            clientsInfo[user.uid] = {
                ip: getIP(socket),
                browser: getBrowser(),
            }
            console.log('User (' + getIP(socket) + ') новый.');
        }
        console.log(clientsInfo);
        console.log('--------------------');
        clients[user.uid] = socket.id;
    });
    socket.on("adminAuth", function(mod) {
        if (admins[mod.uid] !== undefined) {
            console.log('Admin (' + getIP(socket) + ') перешел на другую страницу страницам.');
        } else {
            console.log('Admin (' + getIP(socket) + ') зашел на сайт.');
        }
        console.log(clientsInfo['be3557f5-f200-4658-9c60-c602aa82c32a']);
        console.log(clientsInfo);
        console.log('--------------------');
        // socket.emit("usersOnline", clientsInfo['be3557f5-f200-4658-9c60-c602aa82c32a']);
        admins[mod.uid] = socket.id;
        console.log(admins);
    });

    // socket.on("getUsersOnline", function() {
    //     socket.emit(clientsInfo);
    // })
});