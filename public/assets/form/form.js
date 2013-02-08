<!--//--><![CDATA[//><!--

	/**
	 * Specifies whether a asyncronous validation attempt is ready
	 */
	PHPRum.validationReady = true;


	/**
	 * Function to submit html forms
	 */
	PHPRum.submitForm = function( form, callback ) {

		PHPRum.createFrame(form, callback);
		return true;
	}


	/**
	 * Function to create frame element
	 */
	PHPRum.createFrame = function( form, callback ) {

		var frameName = 'f' + Math.floor(Math.random() * 99999);
		var divElement = document.createElement('DIV');
		var iFrameElement = document.getElementById(form.getAttribute('id') + '__async_postback');

		if(iFrameElement) {
			iFrameElement.parentNode.removeChild(iFrameElement);
		}

		divElement.id = form.getAttribute('id') + '__async_postback'
		divElement.innerHTML = '<iframe style="display:none" src="about:blank" id="'+frameName+'" name="'+frameName+'" onload="PHPRum.documentLoaded(document.getElementById(\''+form.getAttribute('id')+'\'), \''+frameName+'\'); return true;"></iframe>';

		document.body.appendChild(divElement);

		var frameElement = document.getElementById(frameName);
		if (callback && typeof(callback) == 'function') {
			frameElement.completeCallback = callback;
		}

		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", PHPRum.asyncParameter);
		input.setAttribute("value", "1");
		input.setAttribute("id", form.getAttribute('id') + "__async");
		form.appendChild(input);

		form.setAttribute('target', frameName);
	}


	/**
	 * Function to reset validation timer
	 */
	PHPRum.documentLoaded = function(form, iframeID) {

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
	 * Function to set text of an element
	 */
	PHPRum.setText = function( element, text, status ) {

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
	PHPRum.hasText = function( element ) {

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
	PHPRum.resetValidationTimer = function(timeout) {
		PHPRum.validationReady = false;
		window.setTimeout('PHPRum.setValidationReady()', timeout);
	}


	/**
	 * Function to specify whether an asyncronous validation attempt is ready
	 */
	PHPRum.isValidationReady = function() {
		return PHPRum.validationReady;
	}


	/**
	 * Function to set the validation ready flag
	 */
	PHPRum.setValidationReady = function() {
		PHPRum.validationReady = true;
	}


	/**
	 * Function to set the validation ready flag
	 */
	PHPRum.evalFormResponse = function(form, response) {
		eval(response);
		form.removeChild(document.getElementById(form.getAttribute('id')+'__async'));
		form.setAttribute('target', '');
	}


//--><!]]>