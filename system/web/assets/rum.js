var Rum=new function(){var asyncParam="";var validationTimeout=10;var validationReady=true;this.init=function(e,t){asyncParam=e;validationTimeout=t};this.id=function(e){return document.getElementById(e)};this.flash=function(e,t){if(this.id("messages")){var n=document.createElement("a");var r=document.createElement("li");var i=document.createTextNode(e);r.setAttribute("class",t);n.appendChild(document.createTextNode(" dismiss"));n.setAttribute("class","dismiss");n.setAttribute("title","Dismiss");addListener(n,"click",function(){r.style.display="none"});r.appendChild(i);r.appendChild(n);this.id("messages").appendChild(r)}};this.unflashAll=function(){if(this.id("messages")){var e=this.id("messages").childNodes;for(i=0;i<e.length;i++){e[i].parentNode.removeChild(e[i])}}};this.forward=function(e){location.href=e};this.sendAsync=function(e,t,n){http_request=this.createXMLHttpRequest();this.sendAsyncWithCallback(http_request,e,t,n)};this.sendSync=function(e,t,n){if(n==null){n="GET"}if(t==null){t=""}if(n.toUpperCase()=="GET"&&t){if(e.indexOf("?")>-1){e=e+"&"+t}else{e=e+"?"+t}t="";location.href=e}else{t=t.split("&");var r=document.createElement("form");r.action=e+"/";r.method="POST";r.style.display="none";for(var i=0;i<t.length;i++){param=t[i].split("=");var s=document.createElement("input");s.setAttribute("name",param[0]);s.setAttribute("value",param[1]);r.appendChild(s)}document.body.appendChild(r);r.submit()}};this.submit=function(e){var t=evalFormResponse;createFrame(e,t);return true};this.sendAsyncWithCallback=function(http_request,url,params,method,callback){if(method==null){method="GET"}if(params){params+="&"+asyncParam+"=1"}else{params="?"+asyncParam+"=1"}if(method.toUpperCase()=="GET"&&params){if(url.indexOf("?")>-1){url=url+"&"+params}else{url=url+"?"+params}params=""}if(callback!=null){eval("http_request.onreadystatechange="+callback)}http_request.open(method,url,true);http_request.setRequestHeader("Content-type","application/x-www-form-urlencoded");http_request.setRequestHeader("Content-length",params.length);http_request.setRequestHeader("Connection","close");http_request.send(params)};this.evalAsync=function(e,t,n){http_request=this.createXMLHttpRequest();if(http_request===null){console.log("browser does not support HTTP Request")}var r=function(){evalHttpResponse(http_request)};this.sendAsyncWithCallback(http_request,e,t,n,r)};this.documentLoaded=function(e,t){var n=document.getElementById(t);var r=null;if(n.contentDocument){r=n.contentDocument}else if(n.contentWindow){r=n.contentWindow.document}else{r=window.frames[t].document}if(r.location.href=="about:blank"){return}n.completeCallback(e,r.body.textContent)};this.assert=function(e,t){if(this.id(e)){if(this.id(e).className.indexOf(" invalid")===-1){this.id(e).className=this.id(e).className+" invalid"}setText(this.id(e+"__err"),t)}this.reset()};this.clear=function(e){if(this.id(e)){if(this.id(e+"__err")){this.id(e+"__err").style.display="none"}this.id(e).className=this.id(e).className.replace(" invalid","")}this.reset()};this.reset=function(){validationReady=false;window.setTimeout("setValidationReady()",validationTimeout)};this.isReady=function(e){if(hasText(this.id(e))){return validationReady}return false};this.createXMLHttpRequest=function(){if(window.XMLHttpRequest){http_request=new XMLHttpRequest;if(http_request.overrideMimeType){http_request.overrideMimeType("text/html")}}else if(window.ActiveXObject){try{http_request=new ActiveXObject("Msxml2.XMLHTTP")}catch(e){try{http_request=new ActiveXObject("Microsoft.XMLHTTP")}catch(e){}}}if(!http_request){alert("Cannot create XMLHTTP instance");return false}return http_request};setValidationReady=function(){validationReady=true};getHttpResponse=function(e){if(e){if(e.readyState==4){if(e.status==200){response=e.responseText;return response}else{throw"Problem retrieving XML data"}}}};evalHttpResponse=function(http_request){eval(getHttpResponse(http_request))};evalFormResponse=function(formElement,response){eval(response);formElement.removeChild(Rum.id(formElement.getAttribute("id")+"__async"));formElement.setAttribute("target","")};createFrame=function(e,t){var n="f"+Math.floor(Math.random()*99999);var r=document.createElement("DIV");var i=document.getElementById(e.getAttribute("id")+"__async_postback");if(i){i.parentNode.removeChild(i)}r.id=e.getAttribute("id")+"__async_postback";r.innerHTML='<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="Rum.documentLoaded(Rum.id(\''+e.getAttribute("id")+"'), '"+n+"'); return true;\"></iframe>";document.body.appendChild(r);var s=document.getElementById(n);s.completeCallback=t;var o=document.createElement("input");o.setAttribute("type","hidden");o.setAttribute("name",asyncParam);o.setAttribute("value","1");o.setAttribute("id",e.getAttribute("id")+"__async");e.appendChild(o);e.setAttribute("target",n)};setText=function(e,t,n){if(e){if(e.hasChildNodes()){while(e.childNodes.length>=1){e.removeChild(e.firstChild)}}var r=document.createElement("span");if(t.length>0){r.appendChild(document.createTextNode(t));e.style.display="block"}else{r.appendChild(document.createTextNode(""));e.style.display="none"}e.appendChild(r)}};hasText=function(e){if(e){if(e.hasChildNodes()){if(e.childNodes.length>=1){if(e.childNodes[0].textContent.length>0){return true}}}}return null};addListener=function(e,t,n){if(e.addEventListener){e.addEventListener(t,n,false)}else if(e.attachEvent){e.attachEvent("on"+t,n)}else{e["on"+t]=n}}}