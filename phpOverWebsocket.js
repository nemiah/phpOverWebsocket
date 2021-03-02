var phpOverWebsocket = {
	socket: null,
	reconnect: null,
	onopen: [],
	counter: 0,
	sessionid: null,
	lastRequests: [],
	start: function (websocketServerLocation, sessionid){
		phpOverWebsocket.sessionid = sessionid;
		
		phpOverWebsocket.socket = new WebSocket(websocketServerLocation);
		
		phpOverWebsocket.socket.onmessage = function(event) { 
			
			var response = JSON.parse(event.data);
			
			var request = {
				getResponseHeader: function(){
					return "";
				}
			};
			
			phpOverWebsocket.lastRequests[response.counter].success(response.answer.split("\r\n\r\n")[1], null, request);
			delete phpOverWebsocket.lastRequests[response.counter];
		};
		
		phpOverWebsocket.socket.onclose = function(){
			setTimeout(function(){
				phpOverWebsocket.start(websocketServerLocation, phpOverWebsocket.sessionid);
			}, 1000);
		};
	},
	
	send: function(data){
		data.sessionid = phpOverWebsocket.sessionid;
		data.counter = phpOverWebsocket.counter;
		
		phpOverWebsocket.lastRequests[phpOverWebsocket.counter] = data;
		
		var jsonString = JSON.stringify(data);
		
		phpOverWebsocket.socket.send(jsonString);
		
		phpOverWebsocket.counter++;
	},
	
	ready: function(){
		if(phpOverWebsocket.socket === null)
			return false;
		
		return phpOverWebsocket.socket.readyState === 1;
	}
};

//phpOverWebsocket.start('ws://localhost:8080', '<?php echo session_id(); ?>');
