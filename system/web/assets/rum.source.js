

	/**
	 * Initialize namespace
	 */
	var Rum = new function() {

		/**
		 * Specifies the asyncronous request parameter
		 */
		var asyncParam = '';

		/**
		 * Specifies the validation timeout
		 */
		var validationTimeout = 10;

		/**
		 * Specifies whether a asyncronous validation attempt is ready
		 */
		var validationReady = true;

		/**
		 * Function to get a XMLDom object
		 */
		this.init = function(param, timeout) {
			asyncParam = param;
			validationTimeout = timeout;
		};

		/**
		 * Function to get a XMLDom object
		 */
		this.id = function(id) {
			return document.getElementById(id);
		};


		/**
		 * Function to flash a new message for n milliseconds
		 */
		this.flash = function(message, type, delay) {
			if(!delay) delay = 3000;
			if(this.id('messages')) {
				var li = document.createElement('li');
				var text = document.createTextNode(message);
				li.setAttribute('class', type);
//				addListener(a, 'click', function(){li.style.display='none';});
				li.appendChild(text);
				this.id('messages').appendChild(li);
				setTimeout(function() {
					fadeOut(li);
				}, delay);
			}
		};


		/**
		 * this.to clear all flash messages
		 */
		this.unflashAll = function() {
			if(this.id('messages')) {
				var messages = this.id('messages').childNodes;
				for(i=0;i<messages.length;i++)
				{
					messages[i].parentNode.removeChild(messages[i]);
				}
			}
		};


		/**
		 * this.to forward
		 */
		this.forward = function(url) {
			location.href=url;
		};


		/**
		 * this.to send a xmlhttp request.
		 */
		this.getParams = function( element ) {
			var params = '';
			var inputs = element.getElementsByTagName('input');
			var selects = element.getElementsByTagName('select');
			var textareas = element.getElementsByTagName('textarea');
			for (x=0;x<inputs.length;x++) {
				if(inputs[x].getAttribute('type')!='button' && inputs[x].getAttribute('type')!='submit' && inputs[x].getAttribute('type')!='image') {
					if(inputs[x].getAttribute('type')==='checkbox') {
						if(inputs[x].checked) {
							if(params) params = params + '&';
							params = params + inputs[x].getAttribute('name') + '=' + inputs[x].value;
						}
					}
					else {
						if(params) params = params + '&';
						params = params + inputs[x].getAttribute('name') + '=' + inputs[x].value;
					}
				}
			}
			for (x=0;x<selects.length;x++) {
				if(params) params = params + '&';
				params = params + selects[x].getAttribute('name') + '=' + selects[x].value;
			}
			for (x=0;x<textareas.length;x++) {
				if(params) params = params + '&';
				params = params + textareas[x].getAttribute('name') + '=' + textareas[x].value;
			}
			return params;
		}


		/**
		 * this.to send a xmlhttp request.
		 */
		this.sendAsync = function( url, params, method ) {

			http_request = this.createXMLHttpRequest();
			this.sendAsyncWithCallback(http_request, url, params, method);
		}


		/**
		 * this.to send a xmlhttp request.
		 */
		this.sendSync = function( url, params, method ) {

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
				temp.action=url;
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
		};


		/**
		 * this.to submit html forms
		 */
		this.submit = function(formElement) {

			var callback = evalFormResponse;
			createFrame(formElement, callback);
			return true;
		};


		/**
		 * this.to send a xmlhttp request.
		 */
		this.sendAsyncWithCallback = function( http_request, url, params, method, callback ) {

			if (method == null){
				method = 'GET';
			}

			if(params) {
				params += '&'+asyncParam+'=1';
			}
			else {
				params = '?'+asyncParam+'=1';
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
			//http_request.setRequestHeader("Content-length", params.length);
			//http_request.setRequestHeader("Connection", "close");
			http_request.send( params );
		};


		/**
		 * this.to send a xmlhttp request.
		 */
		this.evalAsync = function( url, params, method ) {

			http_request = this.createXMLHttpRequest();

                        if(http_request === null) {
                            console.log('browser does not support HTTP Request');
                        }

			var callback = function() { evalHttpResponse( http_request ); };
			this.sendAsyncWithCallback(http_request, url, params, method, callback);
		};


		/**
		 * this.to reset validation timer
		 */
		this.documentLoaded = function(formElement, iframeID) {

                        //changed frameElement to allow IE10 to work was var frameElement = document.getElementById(iframeID);
			var frameElement = (!document.getElementById(iframeID))?"":document.getElementById(iframeID);
			var documentElement = null;

			if (frameElement.contentDocument) {
				documentElement = frameElement.contentDocument;
			} else if (frameElement.contentWindow) {
				documentElement = frameElement.contentWindow.document;
			} else {
                                return;
                                //removed below to make this work in IE10
				//documentElement = window.frames[iframeID].document;
			}

			if (documentElement.location.href == "about:blank") {
				return;
			}
			//if (typeof(frameElement.completeCallback) == 'this.function =') {
				frameElement.completeCallback(formElement, documentElement.body.textContent);
			//}
		};


		/**
		 * Funciton to assert a Validation Message
		 */
		this.assert = function(id, msg) {
			if(this.id(id)) {
				if(this.id(id).className.indexOf(" invalid") === -1) {
					this.id(id).className = this.id(id).className + " invalid";
				}
				setText(this.id(id+"__err"), msg);
			}
			this.reset();
		};


		/**
		 * Funciton to clear Validation Message
		 */
		this.clear = function( id ) {
			if(this.id(id)) {
				if(this.id(id+"__err")) {
					this.id(id+"__err").style.display = "none";
				}
				this.id(id).className = this.id(id).className.replace(" invalid", "");
			}
			this.reset();
		};


		/**
		 * this.to reset validation timer
		 */
		this.reset  = function() {
			validationReady = false;
			window.setTimeout('setValidationReady()', validationTimeout);
		};


		/**
		 * this.to specify whether an asyncronous Validation attempt is ready
		 */
		this.isReady = function( id ) {
			if(hasText(this.id(id))) {
				return validationReady;
			}
			return false;
		};


		/**
		 * Function to get a xmlhttp object.
		 * @ignore
		 */
		this.createXMLHttpRequest = function() {
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
		};


		/**
		 * this.to set the Validation Ready flag
		 */
		setValidationReady = function() {
			validationReady = true;
		};


		/**
		 * this.to receive HTTP response
		 */
		getHttpResponse = function( http_request ) {

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
		};


		/**
		 * this.to parse HTTP response
		 */
		evalHttpResponse = function( http_request ) {
			eval(getHttpResponse(http_request));
		};


		/**
		 * this.to set the validation ready flag
		 */
		evalFormResponse = function(formElement, response) {
			eval(response);
			formElement.removeChild(Rum.id(formElement.getAttribute('id')+'__async'));
			formElement.setAttribute('target', '');
		};


		/**
		 * this.to create frame element
		 */
		createFrame = function(formElement, callback) {

			var frameName = 'f' + Math.floor(Math.random() * 99999);
			var divElement = document.createElement('DIV');
			var iFrameElement = document.getElementById(formElement.getAttribute('id') + '__async_postback');

			if(iFrameElement) {
				iFrameElement.parentNode.removeChild(iFrameElement);
			}

			divElement.id = formElement.getAttribute('id') + '__async_postback'
			divElement.innerHTML = '<iframe style="display:none" src="about:blank" id="'+frameName+'" name="'+frameName+'" onload="Rum.documentLoaded(Rum.id(\''+formElement.getAttribute('id')+'\'), \''+frameName+'\'); return true;"></iframe>';

			document.body.appendChild(divElement);

			var frameElement = document.getElementById(frameName);
			//if (callback && typeof(callback) == 'this.function =') {
				frameElement.completeCallback = callback;
			//}

			var input = document.createElement("input");
			input.setAttribute("type", "hidden");
			input.setAttribute("name", asyncParam);
			input.setAttribute("value", "1");
			input.setAttribute("id", formElement.getAttribute('id') + "__async");
			formElement.appendChild(input);

			formElement.setAttribute('target', frameName);
		};


		/**
		 * this.to set text of an element
		 */
		setText = function( element, text, status ) {

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
		};


		/**
		 * this.to return if element contains text
		 */
		hasText = function( element ) {
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
		};


		/**
		 * add listener to element
		 */
		addListener = function(element, eventName, handler) {
		  if (element.addEventListener) {
			element.addEventListener(eventName, handler, false);
		  }
		  else if (element.attachEvent) {
			element.attachEvent('on' + eventName, handler);
		  }
		  else {
			element['on' + eventName] = handler;
		  }
		}


		/**
		 * set opacity of element
		 */
		setOpacity = function(element, level) {
			if(level===0) {
				element.parentNode.removeChild(element);
			}
			else {
				element.style.opacity = level;
				element.style.MozOpacity = level;
				element.style.KhtmlOpacity = level;
				element.style.filter = "alpha(opacity=" + (level * 100) + ");";
			}
		}


		/**
		 * fadeout timer handler
		 */
		createTimeoutHandler = function( element, level ) {
			return function() { setOpacity( element, level ); };
		}


		/**
		 * fadeout element for n milliseconds
		 */
		fadeOut = function(element, duration) {
			var steps = 20;
			if(!duration) duration = 1000; // duration of fadeout
			for (var i = 1; i <= steps; i++) {
				setTimeout( createTimeoutHandler( element, 1-i/steps ), (i/steps) * duration);
			}
		}

		// GridView methods

		/**
		 * gridViewToggleDisplay
		 *
		 * toggle display attribute of table nodes
		 *
		 * @param	controlId		name of control
		 * @return	TRUE if successfull
		 */
		this.gridViewSelectAll = function( controlId )
		{
			var table = document.getElementById( controlId );
			var selectAll = document.getElementById( controlId + "__selectall" );
			var checkBoxes = table.getElementsByTagName( 'input' );

			for( var i = 0; i < checkBoxes.length; i++ )
			{
				if( checkBoxes[i].className == controlId + '__checkbox' )
				{
					checkBoxes[i].checked = selectAll.checked;
				}
			}
		}


		/**
		 * gridViewUnSelectAll
		 *
		 * toggle display attribute of table nodes
		 *
		 * @param	controlId		name of control
		 * @return	TRUE if successfull
		 */
		this.gridViewUnSelectAll = function( controlId ) {
			var trTags = document.getElementById( controlId ).getElementsByTagName( 'tr' );

			for( var i = 0; i < trTags.length; i++ ) {
				if( trTags[i].className == 'selected row' ) {
					trTags[i].className = 'row';
				}
				if( trTags[i].className == 'selected row_alt' ) {
					trTags[i].className = 'row_alt';
				}
			}
		}
	};
