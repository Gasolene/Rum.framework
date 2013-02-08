

	/**
	 * Initialize namespace
	 */
	var PHPRum = {};

	PHPRum.httpRequestObjects = Array();

	PHPRum.asyncParameter = '';

	/**
	 * Function to get a xmlhttp object.
	 */
	PHPRum.createXMLHttpRequest = function() {

		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			http_request = new XMLHttpRequest();

			if (http_request.overrideMimeType) {
				// set type accordingly to anticipated content type
				// http_request.overrideMimeType('text/xml');
				http_request.overrideMimeType('text/html');
			}
		} else if (window.ActiveXObject) { // IE
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}

		if (!http_request) {
			alert('Cannot create XMLHTTP instance');
			return false;
		}

		return http_request;
	}


	/**
	 * Function to send a xmlhttp request.
	 */
	PHPRum.sendHttpRequest = function( url, params, method, callback ) {

		if (method == null){
			method = 'GET';
		}

		if(params) {
			params += '&'+PHPRum.asyncParameter+'=1';
		}
		else {
			params = '?'+PHPRum.asyncParameter+'=1';
		}

		if (method.toUpperCase() == 'GET' && params){
			if( url.indexOf( '?' ) > -1 ) {
				url = url + '&' + params;
			}
			else {
				url = url + '?' + params;
			}
			params = '';
		}

		http_request = PHPRum.createXMLHttpRequest();

		if (callback != null){
			eval( 'http_request.onreadystatechange=' + callback );
		}

		http_request.open(method, url, true);
		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http_request.setRequestHeader("Content-length", params.length);
		http_request.setRequestHeader("Connection", "close");
		http_request.send( params );

		return http_request;
	}


	/**
	 * Function to send a xmlhttp request.
	 */
	PHPRum.sendPostBack = function( url, params, method ) {

		if (method == null){
			method = 'GET';
		}
		if (params == null){
			params = '';
		}

		if (method.toUpperCase() == 'GET' && params){
			if( url.indexOf( '?' ) > -1 ) {
				url = url + '&' + params;
			}
			else {
				url = url + '?' + params;
			}
			params = '';

			location.href = url;
		}
		else
		{
			params = params.split('&');
			var temp=document.createElement("form");
			temp.action=url+'/';
			temp.method="POST";
			temp.style.display="none";
			for(var x = 0; x < params.length; x++)
			{
				param = params[x].split('=');
				var input=document.createElement("input");
				input.setAttribute('name', param[0]);
				input.setAttribute('value', param[1]);
				temp.appendChild(input);
			}

			document.body.appendChild(temp);
			temp.submit();
		}
	}


	/**
	 * Function to parse a xmlhttp response.
	 */
	PHPRum.parseXML = function( text ) {
		var doc

		// code for IE
		if (window.ActiveXObject) {
			doc = new ActiveXObject("Microsoft.XMLDOM");
			//doc.async = "false";
			doc.loadXML(text);
		}
		// code for Mozilla, Firefox, Opera, etc.
		else if (document.implementation && document.implementation.createDocument) {
			var parser = new DOMParser();
			doc = parser.parseFromString(text,"text/xml");
		}
		else {
			alert('Cannot create DOMParser instance');
			return false;
		}

		return doc;
	}


	/**
	 * Function to receive HTTP response
	 */
	PHPRum.getHttpResponse = function( http_request_var ) {

		eval("var http_request = " + http_request_var);

		// if xmlhttp shows "loaded"
		if (http_request) {
			// if xmlhttp shows "loaded"
			if (http_request.readyState==4) {
				// if status "OK"
				if (http_request.status==200) {
					// get response
					response = http_request.responseText;
					eval(http_request_var + " = null");
					return response;
				}
				else {
					throw "Problem retrieving XML data";
				}
			}
		}
	}


	/**
	 * Function to parse HTTP response
	 */
	PHPRum.evalHttpResponse = function( http_request_var ) {
		eval(PHPRum.getHttpResponse(http_request_var));
	}

	/**
	 * Function to set opacity
	 */
	PHPRum.setOpacity = function(elementId, level) {
		document.getElementById(elementId).style.opacity = level;
		document.getElementById(elementId).style.MozOpacity = level;
		document.getElementById(elementId).style.KhtmlOpacity = level;
		document.getElementById(elementId).style.filter = "alpha(opacity=" + (level * 100) + ");";

		if(level<0.1) {
			document.getElementById(elementId).style.display = 'none';
		}
		else {
			document.getElementById(elementId).style.display = 'block';
		}
	}

	/**
	 * Function to fade in
	 */
	PHPRum.fadeIn = function(element, duration) {
		if(!duration) duration = 1000; /* 1000 millisecond fade = 1 sec */
		for (i = 0; i <= 1; i += (1 / 20)) {
			setTimeout("PHPRum.setOpacity('"+element.id+"'," + i + ")", i * duration);
		}
	}

	/**
	 * Function to fade out
	 */
	PHPRum.fadeOut = function(element, duration) {
		if(element.style.display != 'none') {
			if(!duration) duration = 1000; /* 1000 millisecond fade = 1 sec */
			for (i = 0; i <= 1; i += (1 / 20)) {
				setTimeout("PHPRum.setOpacity('"+element.id+"'," + (1 - i) + ")", i * duration);
			}
		}
	}

