


	/**
	* disableButtons
	*
	* disable all but the active button
	*
	* @param	form 		form element
	* @param	button 		button element
	* @param	text		button text
	* @return	TRUE if successfull
	*/
	PHPRum.disableButtons = function( form, button, text ) {

		var inputTags = form.getElementsByTagName( 'input' );
		var len = inputTags.length;

		for( var i = 0; i < len; i++ ) {
			if( inputTags[i].getAttribute( 'type' ) === 'submit' ||
				inputTags[i].getAttribute( 'type' ) === 'button' ) {

				if( inputTags[i].name !== button.name ) {
					inputTags[i].disabled = 'disabled';
				}
				else {
					dummy = document.createElement('input');
					dummy.type = 'button';
					dummy.value = text;
					dummy.disabled = true;
					dummy.className = button.className;

					parent = button.parentNode;
					parent.insertBefore(dummy, button);

					button.style.display = 'none';
					return;
				}
			}
		}
	}


