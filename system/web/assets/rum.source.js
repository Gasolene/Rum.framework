

	/**
	 * Initialize namespace
	 */
	var Rum = {};

	Rum.objs = Array();

	/**
	 * Specifies the asyncronous request parameter
	 */
	Rum.asyncParam = '';

	/**
	 * Specifies whether a asyncronous validation attempt is ready
	 */
	Rum.validationReady = true;

	/**
	 * Function to get a xmlhttp object.
	 */
	Rum.id = function(id) {
		return document.getElementById(id);
	}

	/**
	 * Function to get a xmlhttp object.
	 */
	Rum.createXMLHttpRequest = function() {

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
	Rum.sendAsync = function( url, params, method, callback ) {

		if (method == null){
			method = 'GET';
		}

		if(params) {
			params += '&'+Rum.asyncParam+'=1';
		}
		else {
			params = '?'+Rum.asyncParam+'=1';
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

		http_request = Rum.createXMLHttpRequest();

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
	Rum.sendSync = function( url, params, method ) {

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
	 * Function to receive HTTP response
	 */
	Rum.getHttpResponse = function( http_request_var ) {

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
	Rum.eval = function( http_request_var ) {
		eval(Rum.getHttpResponse(http_request_var));
	}

	/**
	 * Function to set opacity
	 */
	Rum.setOpacity = function(elementId, level) {
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
	Rum.fadeIn = function(element, duration) {
		if(!duration) duration = 1000; /* 1000 millisecond fade = 1 sec */
		for (i = 0; i <= 1; i += (1 / 20)) {
			setTimeout("Rum.setOpacity('"+element.id+"'," + i + ")", i * duration);
		}
	}

	/**
	 * Function to fade out
	 */
	Rum.fadeOut = function(element, duration) {
		if(element.style.display != 'none') {
			if(!duration) duration = 1000; /* 1000 millisecond fade = 1 sec */
			for (i = 0; i <= 1; i += (1 / 20)) {
				setTimeout("Rum.setOpacity('"+element.id+"'," + (1 - i) + ")", i * duration);
			}
		}
	}


	/**
	 * Function to submit html forms
	 */
	Rum.submit = function( form, callback ) {

		Rum.createFrame(form, callback);
		return true;
	}


	/**
	 * Function to create frame element
	 */
	Rum.createFrame = function( form, callback ) {

		var frameName = 'f' + Math.floor(Math.random() * 99999);
		var divElement = document.createElement('DIV');
		var iFrameElement = document.getElementById(form.getAttribute('id') + '__async_postback');

		if(iFrameElement) {
			iFrameElement.parentNode.removeChild(iFrameElement);
		}

		divElement.id = form.getAttribute('id') + '__async_postback'
		divElement.innerHTML = '<iframe style="display:none" src="about:blank" id="'+frameName+'" name="'+frameName+'" onload="Rum.documentLoaded(document.getElementById(\''+form.getAttribute('id')+'\'), \''+frameName+'\'); return true;"></iframe>';

		document.body.appendChild(divElement);

		var frameElement = document.getElementById(frameName);
		if (callback && typeof(callback) == 'function') {
			frameElement.completeCallback = callback;
		}

		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", Rum.asyncParam);
		input.setAttribute("value", "1");
		input.setAttribute("id", form.getAttribute('id') + "__async");
		form.appendChild(input);

		form.setAttribute('target', frameName);
	}


	/**
	 * Function to reset validation timer
	 */
	Rum.documentLoaded = function(form, iframeID) {

		var frameElement = document.getElementById(iframeID);
		var documentElement = null;

		if (frameElement.contentDocument) {
			documentElement = frameElement.contentDocument;
		} else if (frameElement.contentWindow) {
			documentElement = frameElement.contentWindow.document;
		} else {
			documentElement = window.frames[iframeID].document;
		}

		if (documentElement.location.href == "about:blank") {
			return;
        }
		if (typeof(frameElement.completeCallback) == 'function') {
			frameElement.completeCallback(form, documentElement.body.textContent);
		}
	}

	/**
	 * Funciton to clear Error Message
	 */
	Rum.setErrMsg = function( id, msg ) {
		Rum.setText(Rum.id(id), msg);
	}

	/**
	 * Funciton to clear Error Message
	 */
	Rum.clrErrMsg = function( id ) {
		if(Rum.id(id)) {
			Rum.id(id).style.display = 'none';
			this.className = this.className.replace('invalid', '');
		}
	}

	/**
	 * Function to set text of an element
	 */
	Rum.setText = function( element, text, status ) {

		if ( element ) {
			if ( element.hasChildNodes() ) {
				while ( element.childNodes.length >= 1 ) {
					element.removeChild( element.firstChild );
				}
			}
			var span = document.createElement('span');

			if(text.length>0) {
				span.appendChild(document.createTextNode(text));
				element.style.display = 'block';
			}
			else {
				span.appendChild(document.createTextNode(''));
				element.style.display = 'none';
			}

			element.appendChild(span);
		}
	}


	/**
	 * Function to return if element contains text
	 */
	Rum.hasText = function( element ) {

		if ( element ) {
			if ( element.hasChildNodes() ) {
				if ( element.childNodes.length >= 1 ) {
					if(element.childNodes[0].textContent.length>0) {
						return true;
					}
				}
			}
		}
		return null;
	}


	/**
	 * Function to reset validation timer
	 */
	Rum.resetValidationTimer = function(timeout) {
		Rum.validationReady = false;
		window.setTimeout('Rum.setValidationReady()', timeout);
	}


	/**
	 * Function to specify whether an asyncronous validation attempt is ready
	 */
	Rum.isValidationReady = function() {
		return Rum.validationReady;
	}


	/**
	 * Function to set the validation ready flag
	 */
	Rum.setValidationReady = function() {
		Rum.validationReady = true;
	}


	/**
	 * Function to set the validation ready flag
	 */
	Rum.evalFormResponse = function(form, response) {
		eval(response);
		form.removeChild(document.getElementById(form.getAttribute('id')+'__async'));
		form.setAttribute('target', '');
	}
