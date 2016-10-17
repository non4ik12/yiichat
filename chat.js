function ajax(url, uId) {
    var XMLHttpRequest = require('xhr2');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var r = JSON.parse(this.response);
            clientsInfo[uId]["location"] = r.country+"/"+r.city;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();

    // Вещаем админам
    var admins_keys = Object.keys(admins), i;
    for (i = admins_keys.length-1; i >= 0; i--) {
        io.sockets.to(admins[admins_keys[i]]).emit("usersOnline", getUsersInfo());
    }

}

function getGeoLocation(uId) {
    ajax("http://ip-api.com/json/" + "95.47.151.4", uId);
}

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

    function getUsersInfo() {
        var result = [],
            keys = Object.keys(clients);
        for (var i = 0; i < 10; i++) {
            if (keys[i] === undefined) break;
            result.push(clientsInfo[keys[i]]);
        }
        return result;
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
            getGeoLocation(user.uid);
            clientsInfo[user.uid] = {
                ip: getIP(socket),
                browser: getBrowser(),
                uid: user.uid
            };
            console.log('User (' + getIP(socket) + ') новый.');
        }
        clients[user.uid] = socket.id;
        clientsInfo[user.uid]['page'] = user.page;

        // Вещаем админам
        var admins_keys = Object.keys(admins), i;
        for (i = admins_keys.length-1; i >= 0; i--) {
            io.sockets.to(admins[admins_keys[i]]).emit("usersOnline", getUsersInfo());
        }
    });
    socket.on("adminAuth", function(mod) {
        if (admins[mod.uid] !== undefined) {
            console.log('Admin (' + getIP(socket) + ') перешел на другую страницу страницам.');
        } else {
            console.log('Admin (' + getIP(socket) + ') зашел на сайт.');
        }
        socket.emit("usersOnline", getUsersInfo());
        admins[mod.uid] = socket.id;
        console.log(admins);
    });
    // socket.on("getUsersOnline", function() {
    //     socket.emit(clientsInfo);
    // })
    // 
});


