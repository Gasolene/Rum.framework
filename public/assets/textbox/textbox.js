<!--//--><![CDATA[//><!--


	/**
	 * Function to update watermark
	 */
	PHPRum.textboxUpdateWatermark = function(textbox, watermark)
	{
		if(textbox.value=='' || textbox.value==watermark || textbox.value.substr(0, watermark.length)==watermark)
		{
			textbox.value = watermark;
			if(textbox.className.indexOf('watermark') == -1)
			{
				textbox.className = textbox.className + " watermark";
			}
		}
		else
		{
			if(textbox.className.indexOf('watermark') > -1)
			{
				textbox.className = textbox.className.replace('watermark', '');
			}
		}
	}


//--><!]]>