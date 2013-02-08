

	/**
	 * gridViewToggleDisplay
	 *
	 * toggle display attribute of table nodes
	 *
	 * @param	controlId		name of control
	 * @return	TRUE if successfull
	 */
	PHPRum.gridViewSelectAll = function( controlId )
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
	PHPRum.gridViewUnSelectAll = function( controlId ) {
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


	/**
	 * gridViewAjaxCallback
	 *
	 * @return	void
	 */
	PHPRum.gridViewAjaxCallback = function() {

		// if xmlhttp shows "loaded"
		if (http_request) {
			// if xmlhttp shows "loaded"
			if (http_request.readyState==4) {
				// if status "OK"
				if (http_request.status==200) {
					// ...place some code here...
					eval(http_request.responseText);
				}
				else {
					// Problem retrieving XML data
				}
			}
		}
	}


