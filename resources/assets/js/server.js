var express = require('express');
var app = express();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var _ = require('lodash');

app.set('port', (process.env.PORT || 3000));

server.listen(app.get('port'), function() {
    console.log('NBBNETS Chat Server is running on port ', app.get('port'));
});

var messages = [], users = [];

io.on('connection', function(socket) {

    let ms = _.sortBy(messages,['date'],['desc']);
    socket.emit('init', _.slice(ms,0,200));

    socket.on('send', ({from,to,text}) => {
        let message = {
            from, to, text, date : new Date(), seen : false
        };
        messages.push(message);
        io.emit('read',message);
    });

    socket.on('seen',({from,to,text}) => {
        let message_index = _.findIndex(messages,{from,to,text});
        messages[message_index].seen = true;
        socket.emit('seen-message',message_index);
    });

    socket.on('add-user', function(user_id){
        users.push({ id: socket.id, user_id });
        io.emit('update-users', users);
    });

    socket.on('disconnect', function() {
        users = users.filter(function(user){
            return user.id != socket.id;
        });
        io.emit('update-users', users);
    });

});