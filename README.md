# phpOverWebsocket

After reading [The Future of Web Software Is HTML-over-WebSockets](https://alistapart.com/article/the-future-of-web-software-is-html-over-websockets/) I asked myself if it would be possible to replace HTML Ajax/fetch calls with a websocket connection and execute PHP directly without Apache or Nginx in between.

This is a proof of concept, that it does work! :) It also includes transferring the session id to get the same user session when executing the script over the websocket connection.
 
A webserver is still required to deliver the initial index.php but as soon as the websocket connection is open, all requests are handled over websocket. The effect on localhost is rather small but I think in a real environment with network latency it should shave off a few milliseconds.

With the added benefit that there is a channel open to push changes to the client any time.

I wrote this on a single weekend. This is only a proof of concept and has probably multiple problems and unconsidered security concerns.

## Try it yourself

	git clone https://github.com/nemiah/phpOverWebsocket.git
	cd phpOverWebsocket
	#download composer…
	php composer.phar install
	php server.php examples
	
This should set up a Ratchet websocket server and run it with my minimal example.

Open /examples/index.php in your browser. When the connection to the websocket server is established, a click on the button will send the request over the websocket. If you kill the server the request will be sent with fetch(). The result will be the same nonetheless.