var Rum=new function(){var asyncParam="";var validationTimeout=10;var validationReady=true;this.defaultAjaxStartHandler=function(e){};this.defaultAjaxCompletionHandler=function(e){};this.init=function(e,t){asyncParam=e;validationTimeout=t};this.id=function(e){return document.getElementById(e)};this.flash=function(e,t,n){if(!n)n=3e3;if(this.id("messages")){var r=document.createElement("li");var i=document.createTextNode(e);r.setAttribute("class",t);r.appendChild(i);this.id("messages").appendChild(r);setTimeout(function(){fadeOut(r)},n)}};this.unflashAll=function(){if(this.id("messages")){var e=this.id("messages").childNodes;for(i=0;i<e.length;i++){e[i].parentNode.removeChild(e[i])}}};this.forward=function(e){location.href=e};this.getParams=function(e){var t="";var n=e.getElementsByTagName("input");var r=e.getElementsByTagName("select");var i=e.getElementsByTagName("textarea");for(x=0;x<n.length;x++){if(n[x].getAttribute("type")!="button"&&n[x].getAttribute("type")!="submit"&&n[x].getAttribute("type")!="image"){if(n[x].getAttribute("type")==="checkbox"){if(n[x].checked){if(t)t=t+"&";t=t+n[x].getAttribute("name")+"="+n[x].value}}else{if(t)t=t+"&";t=t+n[x].getAttribute("name")+"="+n[x].value}}}for(x=0;x<r.length;x++){if(t)t=t+"&";t=t+r[x].getAttribute("name")+"="+r[x].value}for(x=0;x<i.length;x++){if(t)t=t+"&";t=t+i[x].getAttribute("name")+"="+i[x].value}return t};this.sendAsync=function(e,t,n){http_request=this.createXMLHttpRequest();this.sendAsyncWithCallback(http_request,e,t,n)};this.sendSync=function(e,t,n){if(n==null){n="GET"}if(t==null){t=""}if(n.toUpperCase()=="GET"&&t){if(e.indexOf("?")>-1){e=e+"&"+t}else{e=e+"?"+t}t="";location.href=e}else{t=t.split("&");var r=document.createElement("form");r.action=e;r.method="POST";r.style.display="none";for(var i=0;i<t.length;i++){param=t[i].split("=");var s=document.createElement("input");s.setAttribute("name",param[0]);s.setAttribute("value",param[1]);r.appendChild(s)}document.body.appendChild(r);r.submit()}};this.submit=function(e){var t=evalFormResponse;createFrame(e,t);return true};this.sendAsyncWithCallback=function(http_request,url,params,method,completionHandler){if(method==null){method="GET"}if(params){params+="&"+asyncParam+"=1"}else{params="?"+asyncParam+"=1"}if(method.toUpperCase()=="GET"&&params){if(url.indexOf("?")>-1){url=url+"&"+params}else{url=url+"?"+params}params=""}if(completionHandler!=null){eval("http_request.onreadystatechange="+completionHandler)}http_request.open(method,url,true);http_request.setRequestHeader("Content-type","application/x-www-form-urlencoded");http_request.send(params)};this.evalAsync=function(e,t,n,r,i){if(!r)r=this.defaultAjaxStartHandler;if(!i)i=this.defaultAjaxCompletionHandler;eventArgs={};t.split("&").forEach(function(e){a=e.split("=");eventArgs[a[0]]=a[1]});http_request=this.createXMLHttpRequest();if(http_request===null){console.log("browser does not support HTTP Request")}var s=function(){evalHttpResponse(http_request,i,eventArgs)};this.sendAsyncWithCallback(http_request,e,t,n,s);r(eventArgs)};this.documentLoaded=function(e,t){var n=!document.getElementById(t)?"":document.getElementById(t);var r=null;if(n.contentDocument){r=n.contentDocument}else if(n.contentWindow){r=n.contentWindow.document}else{return}if(r.location.href=="about:blank"){return}n.completeCallback(e,r.body.textContent)};this.assert=function(e,t){if(this.id(e)){if(this.id(e).className.indexOf(" invalid")===-1){this.id(e).className=this.id(e).className+" invalid"}setText(this.id(e+"__err"),t)}this.reset()};this.clear=function(e){if(this.id(e)){if(this.id(e+"__err")){this.id(e+"__err").style.display="none"}this.id(e).className=this.id(e).className.replace(" invalid","")}this.reset()};this.reset=function(){validationReady=false;window.setTimeout("setValidationReady()",validationTimeout)};this.isReady=function(e){if(hasText(this.id(e))){return validationReady}return false};this.createXMLHttpRequest=function(){if(window.XMLHttpRequest){http_request=new XMLHttpRequest;if(http_request.overrideMimeType){http_request.overrideMimeType("text/html")}}else if(window.ActiveXObject){try{http_request=new ActiveXObject("Msxml2.XMLHTTP")}catch(e){try{http_request=new ActiveXObject("Microsoft.XMLHTTP")}catch(e){}}}if(!http_request){alert("Cannot create XMLHTTP instance");return false}return http_request};setValidationReady=function(){validationReady=true};evalHttpResponse=function(http_request,completionHandler,eventArgs){eval(getHttpResponse(http_request,completionHandler,eventArgs))};getHttpResponse=function(e,t,n){if(e){if(e.readyState==4){if(e.status==200){response=e.responseText;t(n);return response}else{t(n);throw"Problem retrieving XML data"}}}};evalFormResponse=function(formElement,response){eval(response);formElement.removeChild(Rum.id(formElement.getAttribute("id")+"__async"));formElement.setAttribute("target","")};createFrame=function(e,t){var n="f"+Math.floor(Math.random()*99999);var r=document.createElement("DIV");var i=document.getElementById(e.getAttribute("id")+"__async_postback");if(i){i.parentNode.removeChild(i)}r.id=e.getAttribute("id")+"__async_postback";r.innerHTML='<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="Rum.documentLoaded(Rum.id(\''+e.getAttribute("id")+"'), '"+n+"'); return true;\"></iframe>";document.body.appendChild(r);var s=document.getElementById(n);s.completeCallback=t;var o=document.createElement("input");o.setAttribute("type","hidden");o.setAttribute("name",asyncParam);o.setAttribute("value","1");o.setAttribute("id",e.getAttribute("id")+"__async");e.appendChild(o);e.setAttribute("target",n)};setText=function(e,t,n){if(e){if(e.hasChildNodes()){while(e.childNodes.length>=1){e.removeChild(e.firstChild)}}var r=document.createElement("span");if(t.length>0){r.appendChild(document.createTextNode(t));e.style.display="block"}else{r.appendChild(document.createTextNode(""));e.style.display="none"}e.appendChild(r)}};hasText=function(e){if(e){if(e.hasChildNodes()){if(e.childNodes.length>=1){if(e.childNodes[0].textContent.length>0){return true}}}}return null};addListener=function(e,t,n){if(e.addEventListener){e.addEventListener(t,n,false)}else if(e.attachEvent){e.attachEvent("on"+t,n)}else{e["on"+t]=n}};setOpacity=function(e,t){if(t===0){e.parentNode.removeChild(e)}else{e.style.opacity=t;e.style.MozOpacity=t;e.style.KhtmlOpacity=t;e.style.filter="alpha(opacity="+t*100+");"}};createTimeoutHandler=function(e,t){return function(){setOpacity(e,t)}};fadeOut=function(e,t){var n=20;if(!t)t=1e3;for(var r=1;r<=n;r++){setTimeout(createTimeoutHandler(e,1-r/n),r/n*t)}};this.gridViewSelectAll=function(e){var t=document.getElementById(e);var n=document.getElementById(e+"__selectall");var r=t.getElementsByTagName("input");for(var i=0;i<r.length;i++){if(r[i].className==e+"__checkbox"){r[i].checked=n.checked}}};this.gridViewUnSelectAll=function(e){var t=document.getElementById(e).getElementsByTagName("tr");for(var n=0;n<t.length;n++){if(t[n].className=="selected row"){t[n].className="row"}if(t[n].className=="selected row_alt"){t[n].className="row_alt"}}}}