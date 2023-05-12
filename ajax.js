// ajax library file
// jQuery is loaded

// Args:
// type : one of GET, POST, DELETE, PUT
// url : wherezit goin ?
// sendData : js object with data to send
// responseType : one of : text, html, json, jsonp, xml
// target functions : object target to invoke to handle response
// fncSuccess and fncFail
function AjaxRequest( type, url, sendData, responseType, fncSuccess, fncFail )
{
  var options = {};
  options["type"] = type;
  options["url"] = url;
  options["data"] = sendData;
  options["dataType"] = responseType;
  options["success"] = fncSuccess;
  options["error"] = fncFail;
  $.ajax( options );
}