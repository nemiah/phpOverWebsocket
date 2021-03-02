<?php
session_start();

$_SESSION["test"] = "HI";

?>
<html>
    <head>
    	<script type="text/javascript" src="../phpOverWebsocket.js"></script>
    	<script type="text/javascript">
    		phpOverWebsocket.start('ws://localhost:8080', '<?php echo session_id(); ?>');
    		
    		var request = {
		    	url: 'action.php',
		    	data: 'par1=yes&par2=no',
		    	success: function(t){
			    	console.log(t);
		    		document.getElementById('answer').innerHTML = t;
		    	},
		    	type: "POST"
		    };
    	</script>
    </head>
    <body>
        <button id="sendRequest">Send request</button>
        <pre id="answer"></pre>
    </body>
    
    <script type="text/javascript">
    	document.getElementById("sendRequest").addEventListener("click", function(){
    		if(phpOverWebsocket.ready())
				phpOverWebsocket.send(request);
			else
				fetch('action.php', {
					method: request.type,
					body: request.data,
					headers: {
						"Content-type": "application/x-www-form-urlencoded"
					}
				})
				.then(response => response.text())
				.then(data => request.success(data));
    	}); 
    </script>
</html>
