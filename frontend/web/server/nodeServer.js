//HTTPS handler
var httpsMode = "disable"; // enable or disable

//If httpsMode is enable this two variable values are mandatory *, unless leave it
//var sslServerKeyFile = ''; //Full path of the Server SSL Key file in server
//var sslServerCrtFile = ''; //Full path of the Server SSL Crt file in server
 
var sslServerKeyFile = ''; //Full path of the Server SSL Key file in server
var sslServerCrtFile = ''; //Full path of the Server SSL Crt file in server
var sslBundleServerCrtFile = ''; //Full path of the Server SSL Crt file in server
 
var socket = require('socket.io'); 
var express = require('express');

if (httpsMode == "disable") {
	var http = require('http');
	var app = express();
	var server = http.createServer(app);
} else {
	var https = require('https');
	var fs = require('fs');
	var httpsOptions = {
		key: fs.readFileSync(sslServerKeyFile),
		cert: fs.readFileSync(sslServerCrtFile),
		ca: fs.readFileSync(sslBundleServerCrtFile)
	};
	var app = express();
	var server = https.createServer(httpsOptions, app);
}

var io = socket.listen(server);

io.sockets.on('connection', function (client) {
	 console.log("New client!");

	client.on('message', function (data) {
		console.log('Message received' + data.senderId + ":" + data.receiverId + ":" + data.message + ":" + data.offerId);
		io.sockets.in('/normal/' + data.senderId).emit('message', {
			receiver: data.receiverId,
			sender: data.senderId,
			message: data.message,
			offerId: data.offerId
		}); 
	});

	client.on('messageTyping', function (data) {
		console.log('Message Typing received' + data.senderId + ":" + data.receiverId + ":" + data.message);
		io.sockets.in('/normal/' + data.senderId).emit('messageTyping', {
			receiver: data.receiverId,
			senderId: data.senderId,
			message: data.message
		});
	});

	client.on('join', function (data) {
		console.log('Message Client Joined' + data.joinid); 
		client.join('/normal/' + data.joinid);
	});

	client.on('exchangejoin', function (data) {
		// console.log('Exchange Client Joined' + data.joinid);
		client.join('/exchange/' + data.joinid);
	});

	client.on('exmessage', function (data) {
		// console.log('Exchange received' + data.senderId + ":" + data.message);
		io.sockets.in('/exchange/' + data.senderId).emit('exmessage', {
			receiver: data.receiverId,
			sender: data.senderId,
			message: data.message,
			sourceId: data.sourceId
		});
	});

	client.on('exmessageTyping', function (data) {
		// console.log('Exchange Typing received ' + data.senderId + ":" + data.receiverId + ":" + data.message);
		io.sockets.in('/exchange/' + data.senderId).emit('exmessageTyping', {
			receiver: data.receiverId,
			senderId: data.senderId,
			message: data.message,
			sourceId: data.sourceId
		});
	});

});

server.listen(8088, () => console.log('Socket is running...'));