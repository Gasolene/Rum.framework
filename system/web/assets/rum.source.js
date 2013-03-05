

	/**
	 * Initialize namespace
	 */
	var Rum = {};

	/**
	 * Specifies the asyncronous request parameter
	 */
	Rum.asyncParam = '';

	/**
	 * Specifies the asyncronous request parameter
	 */
	Rum.validationTimeout = 10;

	/**
	 * Specifies whether a asyncronous validation attempt is ready
	 */
	Rum.validationReady = true;

	/**
	 * Function to get a xmlhttp object.
	 */
	Rum.init = function(param, timeout) {
		Rum.asyncParam = param;
		Rum.validationTimeout = timeout;
	}


	/**
	 * Function to get a xmlhttp object.
	 */
	Rum.id = function(id) {
		return document.getElementById(id);
	}


	/**
	 * Function to set a new message
	 */
	Rum.flash = function(message, type) {
		if(Rum.id('messages')) {
			var li = document.createElement('li');
			li.setAttribute('class', type);
			li.innerHTML = message;
			Rum.id('messages').appendChild(li);
		}
	}


	/**
	 * Function to forward
	 */
	Rum.forward = function(url) {
		location.href=url;
	}


	/**
	 * Function to get a xmlhttp object.
	 * @ignore
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
	Rum.sendAsync = function( http_request, url, params, method, callback ) {

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
	Rum.evalAsync = function( url, params, method ) {

		http_request = Rum.createXMLHttpRequest();
		var callback = function() { Rum.evalHttpResponze( http_request ); };
		Rum.sendAsync(http_request, url, params, method, callback);
	}


	/**
	 * Function to receive HTTP response
	 */
	Rum.getHttpResponse = function( http_request ) {

		// if xmlhttp shows "loaded"
		if (http_request) {
			// if xmlhttp shows "loaded"
			if (http_request.readyState==4) {
				// if status "OK"
				if (http_request.status==200) {
					// get response
					response = http_request.responseText;
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
	Rum.evalHttpResponze = function( http_request ) {
		eval(Rum.getHttpResponse(http_request));
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
	 * Function to submit html forms
	 */
	Rum.submit = function( form, callback ) {

		Rum.createFrame(form, callback);
		return true;
	}


	/**
	 * Function to set the validation ready flag
	 */
	Rum.evalFormResponse = function(form, response) {
		eval(response);
		form.removeChild(document.getElementById(form.getAttribute('id')+'__async'));
		form.setAttribute('target', '');
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
		divElement.innerHTML = '<iframe style="display:none" src="about:blank" id="'+frameName+'" name="'+frameName+'" onload="Rum.documentLoaded(Rum.id(\''+form.getAttribute('id')+'\'), \''+frameName+'\'); return true;"></iframe>';

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
	 * Funciton to assert a Validation Message
	 */
	Rum.assert = function( id, msg ) {
		Rum.setText(Rum.id(id), msg);
		Rum.reset();
	}

	/**
	 * Funciton to clear Validation Message
	 */
	Rum.clear = function( id ) {
		if(Rum.id(id)) {
			Rum.id(id).style.display = 'none';
			this.className = this.className.replace('invalid', '');
		}
		Rum.reset();
	}

	/**
	 * Function to reset validation timer
	 */
	Rum.reset = function() {
		Rum.validationReady = false;
		window.setTimeout('Rum.setValidationReady()', Rum.validationTimeout);
	}

	/**
	 * Function to set the Validation Ready flag
	 */
	Rum.setValidationReady = function() {
		Rum.validationReady = true;
	}

	/**
	 * Function to specify whether an asyncronous Validation attempt is ready
	 */
	Rum.isReady = function( id ) {
		if(Rum.hasText(Rum.id(id))) {
			return Rum.validationReady;
		}
		return false;
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
