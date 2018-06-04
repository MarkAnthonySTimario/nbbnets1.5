var express = require('express');
var app = express();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var _ = require('lodash');

app.set('port', (process.env.PORT || 3000));

server.listen(app.get('port'), function() {
    console.log('NBBNETS Chat Server is running on port ', app.get('port'));
});

var messages = [
    {
        from : 'badz' , to : '13006', text : 'kamusta?' , date : new Date() , seen : false
    }
];

io.on('connection', function(socket) {

    // socket.emit('init-chat', messages);

    socket.on('load-messages', user => {
        var msgs = _.filter(messages, m => {
            if(m.from.toUpperCase() == user.toUpperCase() || m.to.toUpperCase() == user.toUpperCase()){
                return m;
            }
        })
        socket.emit('init-chat', msgs);
    });
});

