function confirmSubmit() {
  sButton = "Are you sure you wish to Submit this information?"

  var intAnswer = confirm (sButton)
  if (intAnswer) {
  	disableSubmitButton()
  	setTimeout(enableSubmitButton,30000);
  	return true;
  } else {
  	return false;
  }
}

function disableSubmitButton() {
	eleArray = document.getElementsByTagName("INPUT");
  
  for (i=0; i<eleArray.length;i++) {
  	if (eleArray[i].name=="submit") {
  		eleArray[i].style.visibility="hidden";
  	}
  }
}

function enableSubmitButton() {
	EleArray = document.getElementsByTagName("INPUT");
  
  for (i=0; i<eleArray.length;i++) {
  	if (eleArray[i].name=="submit") {
  		eleArray[i].style.visibility="visible";
  	}
  }
}

function resizeTopMenu() {
	if (document.getElementById("topmenu")!=null) {
		var strtext = "";
		strtext += "Your browser\'s dimensions are: \n";
		if (document.body) {
			strtext += "document.body - ";
			strtext += document.body.clientWidth+" by "+document.body.clientHeight+"\n";
		}
		if (document.body) {
			strtext += "document.body scroll - ";
			strtext += document.body.scrollWidth+" by "+document.body.scrollHeight+"\n";
		} 
		if (document.body) {
			strtext += "document.body offset - ";
			strtext += document.body.offsetWidth+" by "+document.body.offsetHeight+"\n";
		} 
		if (window.innerWidth) { //if browser supports window.innerWidth
			strtext += "window.innerWidth - ";
			strtext += window.innerWidth+" by "+window.innerHeight+"\n";
		}
		if (window.screen) { //if browser supports window.innerWidth
			strtext += "window.screen - ";
			strtext += window.screen.availWidth+" by "+window.screen.availHeight+"\n";
		}	
		if (document.getElementById("maincontentarea")) {
			strtext += "maincontentarea - ";
			strtext += document.getElementById("maincontentarea").clientWidth+" by "+document.getElementById("maincontentarea").clientHeight+"\n";
		}
		//alert(strtext);
	}
	
	var intscreenwidth = document.body.clientWidth;
	//var intlastbodywidth = document.body.scrollWidth;
	var intlastbodywidth = document.getElementById("maincontentarea").scrollWidth;
	var intnewbodywidth = 0;
	
	if (intscreenwidth<intlastbodywidth) {
		document.getElementById("menuleft_more").style.display="";
		document.getElementById("menuitem_more").style.display="";
		document.getElementById("menuright_more").style.display="";
	}
	
	var intcount = 0;
	while ((intcount==0 && intscreenwidth<intlastbodywidth) || (intcount!=0 && intscreenwidth<intnewbodywidth) && (intlastbodywidth!=intnewbodywidth ) ) {
		intlastbodywidth = document.getElementById("maincontentarea").scrollWidth;
		var intmenutabid = arrMenuDisplay[arrMenuDisplay.length-intcount-1];		
		document.getElementById("menuleft_"+intmenutabid).style.display="none";
		document.getElementById("menuitem_"+intmenutabid).style.display="none";
		document.getElementById("menuright_"+intmenutabid).style.display="none";
		document.getElementById("menuright_"+intmenutabid).style.display="none";
		document.getElementById("menudropitem_"+intmenutabid).style.display="";
		intnewbodywidth = document.getElementById("maincontentarea").scrollWidth;
		//alert (intnewbodywidth+","+intscreenwidth);
		intcount++;
	} 
	
	if (intlastbodywidth==intnewbodywidth) {
		var intmenutabid = arrMenuDisplay[arrMenuDisplay.length-intcount];		
		document.getElementById("menuleft_"+intmenutabid).style.display="";
		document.getElementById("menuitem_"+intmenutabid).style.display="";
		document.getElementById("menuright_"+intmenutabid).style.display="";
		document.getElementById("menuright_"+intmenutabid).style.display="";
		document.getElementById("menudropitem_"+intmenutabid).style.display="none";
		intnewbodywidth = document.getElementById("maincontentarea").scrollWidth;
	}
}

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}

function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}

function utf8toucs(strtext){	
	var bstr = '';
	var arraystr = strtext.split(/&#/g);	
	for (i=0; i<arraystr.length; i++) {
		if (arraystr[i].length>0) {
			//converting #& to %u
			strtemp = arraystr[i].match(/[0-9]*/g)[0];
			if (strtemp.length>0) {
				intchar = parseInt(strtemp);
				if (intchar.toString(16).length==1) 
					strchar = '%u000'+intchar.toString(16);
				else if (intchar.toString(16).length==2) 
					strchar = '%u00'+intchar.toString(16);
				else if (intchar.toString(16).length==3) 
					strchar = '%u0'+intchar.toString(16);
				else if (intchar.toString(16).length==4) 
					strchar = '%u'+intchar.toString(16);
					
				//recovering the rest of string
				intlength = strtemp.length+1;
				strremain = arraystr[i].substring(intlength);
			
				bstr+=unescape(strchar)+strremain;
			}
			else 
				bstr+=arraystr[i];
		}
	}
	return bstr;
}

function FCKEditor_removetoolbar( editorInstance ){
	//finding toolbar in FCKeditor
	var temp = editorInstance.EditorWindow.parent.FCK.ToolbarSet._TargetElement.parentNode;
	while (temp.tagName!="TD") {
		temp=temp.parentNode;
	}
	//disable toolbar
	temp.style.display="none";
}									  
														

/*
No rightclick script v.2.5
(c) 1998 barts1000
barts1000@aol.com
Don't delete this header!
*/

/*

var isNS = (navigator.appName == "Netscape") ? 1 : 0;

if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);

function mischandler(){
  return false;
}

function mousedownhandler(e) {
	var myevent = (isNS) ? e : event;
	var eventbutton = (isNS) ? myevent.which : myevent.button;
   if((eventbutton==2)||(eventbutton==3)) 
   	 return false;
}

function mouseuphandler(e) {
	var myevent = (isNS) ? e : event;
	var eventbutton = (isNS) ? myevent.which : myevent.button;
	if((eventbutton==2)||(eventbutton==3)) 
		return false;
}

document.oncontextmenu = mischandler;
document.onmousedown = mousedownhandler;
document.onmouseup = mouseuphandler;
*/

function convertToEntities(strtext) {
	var tstr = strtext;
	var bstr = '';
	for(i=0; i<tstr.length; i++)
	{
		if(tstr.charCodeAt(i)>127) {
			bstr += '&#' + tstr.charCodeAt(i) + ';';
		}
		else {
			bstr += tstr.charAt(i);
		}
	}
	return bstr;
}


function redirect(strURL)
{
	window.location.href=strURL;
}

function RemoveHTML( strText )
{
	var regEx = /<[^>]*>/g;
	return strText.replace(regEx, "");
}
	
function GetE( elementId )
{
	return document.getElementById( elementId )  ;
}

function OpenFileBrowser( strurl, width, height )
{
	// oEditor must be defined.
	
	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes,scrollbars=yes" ;
	sOptions += ",width=" + width ;
	sOptions += ",height=" + height ;

	// The "PreserveSessionOnFileBrowser" because the above code could be 
	// blocked by popup blockers.

	if ( navigator.appName == "Microsoft Internet Explorer" )
	{
		// The following change has been made otherwise IE will open the file 
		// browser on a different server session (on some cases):
		// http://support.microsoft.com/default.aspx?scid=kb;en-us;831678
		// by Simone Chiaretta.
		
		var oWindow = window.open( strurl, null ,sOptions ) ;
		//var oWindow = window.open( strurl ) ;
		if ( oWindow )
		{
			// Detect Yahoo popup blocker.
			try
			{
				oWindow.opener = window ;
			}
			catch(e)
			{
				alert( "window block" ) ;
			}
		}
		else
			alert( "window block" ) ;
  }
  else
	window.open( strurl, null, sOptions ) ;
}

function ExecAJAX(strURI,strAJAXCallBack,async,method,strUsername,strPasswd,parameters){
	var browser=navigator.appName;
	var b_version=navigator.appVersion;
	
	var http_request = false;

	//location of redirector
	if (browser!="Microsoft Internet Explorer"){ // overriding security policy	
		
	 	try
  	{
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
          //http_request.overrideMimeType('text/html');
          // See note below about this line
      }
    } catch(E){
    	alert (E);
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
      alert('Giving up :( Cannot create an XMLHTTP instance');
      return false;
  }
  
	try
	{	
		if (strUsername!="" || strPasswd!="") {
			http_request.open(method, strURI, async, strUsername, strPasswd);
		}
		else {
  		http_request.open(method, strURI, async);
  	}
  	if (method.toUpperCase()=="POST") {
	  	http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	    http_request.setRequestHeader("Content-length", parameters.length);
	    http_request.setRequestHeader("Connection", "close");
	    http_request.send(parameters);
	  }
  }
  catch (E)
  {
  		alert (E);
  }
  
  if (async) {
  	
  	http_request.onreadystatechange= function () {
  		eval(strAJAXCallBack);
  	}
  	
  	
  	//http_request.onreadystatechange=strAJAXCallBack;
  }
  
  http_request.send(null);
  
  if (!async) {
  	return eval(strAJAXCallBack);
	}
	else {
  	return true;
  }
}

function doAJAX(strURI,strAJAXCallBack) {
	return ExecAJAX(strURI,strAJAXCallBack,false,"GET","","","");
}

function doPOSTAJAX(strURI,strAJAXCallBack,parameters) {
	return ExecAJAX(strURI,strAJAXCallBack,false,"POST","","",parameters);
}

function doAJAXA(strURI,strAJAXCallBack) {
	return ExecAJAX(strURI,strAJAXCallBack,true,"GET","","","");
}

var win = null;
var win = null;
function NewWindow(mypage,myname,w,h,scroll){
LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',menubar=no,status=no,location=no,toolbar=noresizable=no'
win = window.open(mypage,myname,settings)
}

function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	}
	return [curleft,curtop];
}

/*
Name: jsDate
Desc: VBScript native Date functions emulated for Javascript
Author: Rob Eberhardt, Slingshot Solutions - http://slingfive.com/
Note: see jsDate.txt for more info
*/

// constants
vbGeneralDate=0; vbLongDate=1; vbShortDate=2; vbLongTime=3; vbShortTime=4;  // NamedFormat
vbUseSystemDayOfWeek=0; vbSunday=1; vbMonday=2; vbTuesday=3; vbWednesday=4; vbThursday=5; vbFriday=6; vbSaturday=7;	// FirstDayOfWeek
vbUseSystem=0; vbFirstJan1=1; vbFirstFourDays=2; vbFirstFullWeek=3;	// FirstWeekOfYear

// arrays (1-based)
Date.MonthNames = [null,'January','February','March','April','May','June','July','August','September','October','November','December'];
Date.WeekdayNames = [null,'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

Date.IsDate = function(p_Expression){
	return !isNaN(new Date(p_Expression));		// <-- review further
}

Date.CDate = function(p_Date){
	if(Date.IsDate(p_Date)){ return new Date(p_Date); }

	var strTry = p_Date.replace(/\-/g, '/').replace(/\./g, '/').replace(/ /g, '/');	// fix separators
	strTry = strTry.replace(/pm$/i, " pm").replace(/am$/i, " am");	// and meridian spacing
	if(Date.IsDate(strTry)){ return new Date(strTry); }

	var strTryYear = strTry + '/' + new Date().getFullYear();	// append year
	if(Date.IsDate(strTryYear)){ return new Date(strTryYear); }
	

	if(strTry.indexOf(":")){	// if appears to have time
		var strTryYear2 = strTry.replace(/ /, '/' + new Date().getFullYear() + ' ');	// insert year
		if(Date.IsDate(strTryYear2)){ return new Date(strTryYear2); }

		var strTryDate = new Date().toDateString() + ' ' + p_Date;	// pre-pend current date
		if(Date.IsDate(strTryDate)){ return new Date(strTryDate); }
	}
	
	return false;	// double as looser IsDate
	//throw("Error #13 - Type mismatch");	// or is this better? 
}
 
Date.DateAdd = function(p_Interval, p_Number, p_Date){
	if(!Date.CDate(p_Date)){	return "invalid date: '" + p_Date + "'";	}
	if(isNaN(p_Number)){	return "invalid number: '" + p_Number + "'";	}	

	p_Number = new Number(p_Number);
	var dt = Date.CDate(p_Date);
	
	switch(p_Interval.toLowerCase()){
		case "yyyy": {
			dt.setFullYear(dt.getFullYear() + p_Number);
			break;
		}
		case "q": {
			dt.setMonth(dt.getMonth() + (p_Number*3));
			break;
		}
		case "m": {
			dt.setMonth(dt.getMonth() + p_Number);
			break;
		}
		case "y":			// day of year
		case "d":			// day
		case "w": {		// weekday
			dt.setDate(dt.getDate() + p_Number);
			break;
		}
		case "ww": {	// week of year
			dt.setDate(dt.getDate() + (p_Number*7));
			break;
		}
		case "h": {
			dt.setHours(dt.getHours() + p_Number);
			break;
		}
		case "n": {		// minute
			dt.setMinutes(dt.getMinutes() + p_Number);
			break;
		}
		case "s": {
			dt.setSeconds(dt.getSeconds() + p_Number);
			break;
		}
		case "ms": {	// JS extension
			dt.setMilliseconds(dt.getMilliseconds() + p_Number);
			break;
		}
		default: {
			return "invalid interval: '" + p_Interval + "'";
		}
	}
	return dt;
}

Date.DateDiff = function(p_Interval, p_Date1, p_Date2, p_FirstDayOfWeek){
	if(!Date.CDate(p_Date1)){	return "invalid date: '" + p_Date1 + "'";	}
	if(!Date.CDate(p_Date2)){	return "invalid date: '" + p_Date2 + "'";	}
	p_FirstDayOfWeek = (isNaN(p_FirstDayOfWeek) || p_FirstDayOfWeek==0) ? vbSunday : parseInt(p_FirstDayOfWeek);	// set default & cast

	var dt1 = Date.CDate(p_Date1);
	var dt2 = Date.CDate(p_Date2);

	// correct DST-affected intervals ("d" & bigger)
	if("h,n,s,ms".indexOf(p_Interval.toLowerCase())==-1){
		if(p_Date1.toString().indexOf(":") ==-1){ dt1.setUTCHours(0,0,0,0) };	// no time, assume 12am
		if(p_Date2.toString().indexOf(":") ==-1){ dt2.setUTCHours(0,0,0,0) };	// no time, assume 12am
	}


	// get ms between UTC dates and make into "difference" date
	var iDiffMS = dt2.valueOf() - dt1.valueOf();
	var dtDiff = new Date(iDiffMS);

	// calc various diffs
	var nYears  = dt2.getUTCFullYear() - dt1.getUTCFullYear();
	var nMonths = dt2.getUTCMonth() - dt1.getUTCMonth() + (nYears!=0 ? nYears*12 : 0);
	var nQuarters = parseInt(nMonths / 3);	//<<-- different than VBScript, which watches rollover not completion
	
	var nMilliseconds = iDiffMS;
	var nSeconds = parseInt(iDiffMS / 1000);
	var nMinutes = parseInt(nSeconds / 60);
	var nHours = parseInt(nMinutes / 60);
	var nDays  = parseInt(nHours / 24);	// <-- now fixed for DST switch days
	var nWeeks = parseInt(nDays / 7);


	if(p_Interval.toLowerCase()=='ww'){
			// set dates to 1st & last FirstDayOfWeek
			var offset = Date.DatePart("w", dt1, p_FirstDayOfWeek)-1;
			if(offset){	dt1.setDate(dt1.getDate() +7 -offset);	}
			var offset = Date.DatePart("w", dt2, p_FirstDayOfWeek)-1;
			if(offset){	dt2.setDate(dt2.getDate() -offset);	}
			// recurse to "w" with adjusted dates
			var nCalWeeks = Date.DateDiff("w", dt1, dt2) + 1;
	}
	// TODO: similar for 'w'?
	
	
	// return difference
	switch(p_Interval.toLowerCase()){
		case "yyyy": return nYears;
		case "q": return nQuarters;
		case "m":	return nMonths;
		case "y":			// day of year
		case "d": return nDays;
		case "w": return nWeeks;
		case "ww":return nCalWeeks; // week of year	
		case "h": return nHours;
		case "n": return nMinutes;
		case "s": return nSeconds;
		case "ms":return nMilliseconds;	// not in VBScript
		default : return "invalid interval: '" + p_Interval + "'";
	}
}

Date.DatePart = function(p_Interval, p_Date, p_FirstDayOfWeek){
	if(!Date.CDate(p_Date)){	return "invalid date: '" + p_Date + "'";	}

	var dtPart = Date.CDate(p_Date);
	
	switch(p_Interval.toLowerCase()){
		case "yyyy": return dtPart.getFullYear();
		case "q": return parseInt(dtPart.getMonth() / 3) + 1;
		case "m": return dtPart.getMonth() + 1;
		case "y": return Date.DateDiff("y", "1/1/" + dtPart.getFullYear(), dtPart) + 1;	// day of year
		case "d": return dtPart.getDate();
		case "w": return Date.Weekday(dtPart.getDay()+1, p_FirstDayOfWeek);		// weekday
		case "ww":return Date.DateDiff("ww", "1/1/" + dtPart.getFullYear(), dtPart, p_FirstDayOfWeek) + 1;	// week of year
		case "h": return dtPart.getHours();
		case "n": return dtPart.getMinutes();
		case "s": return dtPart.getSeconds();
		case "ms":return dtPart.getMilliseconds();	// <-- JS extension, NOT in VBScript
		default : return "invalid interval: '" + p_Interval + "'";
	}
}

Date.MonthName = function(p_Month, p_Abbreviate){
	if(isNaN(p_Month)){	// v0.94- compat: extract real param from passed date
		if(!Date.CDate(p_Month)){	return "invalid month: '" + p_Month + "'";	}
		p_Month = DatePart("m", Date.CDate(p_Month));
	}

	var retVal = Date.MonthNames[p_Month];
	if(p_Abbreviate==true){	retVal = retVal.substring(0, 3)	}	// abbr to 3 chars
	return retVal;
}

Date.WeekdayName = function(p_Weekday, p_Abbreviate, p_FirstDayOfWeek){
	if(isNaN(p_Weekday)){	// v0.94- compat: extract real param from passed date
		if(!Date.CDate(p_Weekday)){	return "invalid weekday: '" + p_Weekday + "'";	}
		p_Weekday = DatePart("w", Date.CDate(p_Weekday));
	}
	p_FirstDayOfWeek = (isNaN(p_FirstDayOfWeek) || p_FirstDayOfWeek==0) ? vbSunday : parseInt(p_FirstDayOfWeek);	// set default & cast

	var nWeekdayNameIdx = ((p_FirstDayOfWeek-1 + parseInt(p_Weekday)-1 +7) % 7) + 1;	// compensate nWeekdayNameIdx for p_FirstDayOfWeek
	var retVal = Date.WeekdayNames[nWeekdayNameIdx];
	if(p_Abbreviate==true){	retVal = retVal.substring(0, 3)	}	// abbr to 3 chars
	return retVal;
}

// adjusts weekday for week starting on p_FirstDayOfWeek
Date.Weekday=function(p_Weekday, p_FirstDayOfWeek){	
	p_FirstDayOfWeek = (isNaN(p_FirstDayOfWeek) || p_FirstDayOfWeek==0) ? vbSunday : parseInt(p_FirstDayOfWeek);	// set default & cast

	return ((parseInt(p_Weekday) - p_FirstDayOfWeek +7) % 7) + 1;
}

Date.FormatDateTime = function(p_Date, p_NamedFormat){
	if(p_Date.toUpperCase().substring(0,3) == "NOW"){	p_Date = new Date()	};
	if(!Date.CDate(p_Date)){	return "invalid date: '" + p_Date + "'";	}
	if(isNaN(p_NamedFormat)){	p_NamedFormat = vbGeneralDate	};

	var dt = Date.CDate(p_Date);

	switch(parseInt(p_NamedFormat)){
		case vbGeneralDate: return dt.toString();
		case vbLongDate:		return Format(p_Date, 'DDDD, MMMM D, YYYY');
		case vbShortDate:		return Format(p_Date, 'MM/DD/YYYY');
		case vbLongTime:		return dt.toLocaleTimeString();
		case vbShortTime:		return Format(p_Date, 'HH:MM:SS');
		default:	return "invalid NamedFormat: '" + p_NamedFormat + "'";
	}
}

Date.Format = function(p_Date, p_Format, p_FirstDayOfWeek, p_firstweekofyear) {
	if(!Date.CDate(p_Date)){	return "invalid date: '" + p_Date + "'";	}
	if(!p_Format || p_Format==''){	return dt.toString()	};

	var dt = Date.CDate(p_Date);

	// Zero-padding formatter
	this.pad = function(p_str){
		if(p_str.toString().length==1){p_str = '0' + p_str}
		return p_str;
	}

	var ampm = dt.getHours()>=12 ? 'PM' : 'AM'
	var hr = dt.getHours();
	if (hr == 0){hr = 12};
	if (hr > 12) {hr -= 12};
	var strShortTime = hr +':'+ this.pad(dt.getMinutes()) +':'+ this.pad(dt.getSeconds()) +' '+ ampm;
	var strShortDate = (dt.getMonth()+1) +'/'+ dt.getDate() +'/'+ new String( dt.getFullYear() ).substring(2,4);
	var strLongDate = Date.MonthName(dt.getMonth()+1) +' '+ dt.getDate() +', '+ dt.getFullYear();		//

	var retVal = p_Format;
	
	// switch tokens whose alpha replacements could be accidentally captured
	retVal = retVal.replace( new RegExp('C', 'gi'), 'CCCC' ); 
	retVal = retVal.replace( new RegExp('mmmm', 'gi'), 'XXXX' );
	retVal = retVal.replace( new RegExp('mmm', 'gi'), 'XXX' );
	retVal = retVal.replace( new RegExp('dddddd', 'gi'), 'AAAAAA' ); 
	retVal = retVal.replace( new RegExp('ddddd', 'gi'), 'AAAAA' ); 
	retVal = retVal.replace( new RegExp('dddd', 'gi'), 'AAAA' );
	retVal = retVal.replace( new RegExp('ddd', 'gi'), 'AAA' );
	retVal = retVal.replace( new RegExp('timezone', 'gi'), 'ZZZZ' );
	retVal = retVal.replace( new RegExp('time24', 'gi'), 'TTTT' );
	retVal = retVal.replace( new RegExp('time', 'gi'), 'TTT' );

	// now do simple token replacements
	retVal = retVal.replace( new RegExp('yyyy', 'gi'), dt.getFullYear() );
	retVal = retVal.replace( new RegExp('yy', 'gi'), new String( dt.getFullYear() ).substring(2,4) );
	retVal = retVal.replace( new RegExp('y', 'gi'), Date.DatePart("y", dt) );
	retVal = retVal.replace( new RegExp('q', 'gi'), Date.DatePart("q", dt) );
	retVal = retVal.replace( new RegExp('mm', 'gi'), (dt.getMonth() + 1) );	
	retVal = retVal.replace( new RegExp('m', 'gi'), (dt.getMonth() + 1) );	
	retVal = retVal.replace( new RegExp('dd', 'gi'), this.pad(dt.getDate()) );
	retVal = retVal.replace( new RegExp('d', 'gi'), dt.getDate() );
	retVal = retVal.replace( new RegExp('hh', 'gi'), this.pad(dt.getHours()) );
	retVal = retVal.replace( new RegExp('h', 'gi'), dt.getHours() );
	retVal = retVal.replace( new RegExp('nn', 'gi'), this.pad(dt.getMinutes()) );
	retVal = retVal.replace( new RegExp('n', 'gi'), dt.getMinutes() );
	retVal = retVal.replace( new RegExp('ss', 'gi'), this.pad(dt.getSeconds()) ); 
	retVal = retVal.replace( new RegExp('s', 'gi'), dt.getSeconds() ); 
	retVal = retVal.replace( new RegExp('t t t t t', 'gi'), strShortTime ); 
	retVal = retVal.replace( new RegExp('am/pm', 'g'), dt.getHours()>=12 ? 'pm' : 'am');
	retVal = retVal.replace( new RegExp('AM/PM', 'g'), dt.getHours()>=12 ? 'PM' : 'AM');
	retVal = retVal.replace( new RegExp('a/p', 'g'), dt.getHours()>=12 ? 'p' : 'a');
	retVal = retVal.replace( new RegExp('A/P', 'g'), dt.getHours()>=12 ? 'P' : 'A');
	retVal = retVal.replace( new RegExp('AMPM', 'g'), dt.getHours()>=12 ? 'pm' : 'am');
	// (always proceed largest same-lettered token to smallest)

	// now finish the previously set-aside tokens 
	retVal = retVal.replace( new RegExp('XXXX', 'gi'), Date.MonthName(dt.getMonth()+1, false) );	//
	retVal = retVal.replace( new RegExp('XXX',  'gi'), Date.MonthName(dt.getMonth()+1, true ) );	//
	retVal = retVal.replace( new RegExp('AAAAAA', 'gi'), strLongDate ); 
	retVal = retVal.replace( new RegExp('AAAAA', 'gi'), strShortDate ); 
	retVal = retVal.replace( new RegExp('AAAA', 'gi'), Date.WeekdayName(dt.getDay()+1, false, p_FirstDayOfWeek) );	// 
	retVal = retVal.replace( new RegExp('AAA',  'gi'), Date.WeekdayName(dt.getDay()+1, true,  p_FirstDayOfWeek) );	// 
	retVal = retVal.replace( new RegExp('TTTT', 'gi'), dt.getHours() + ':' + this.pad(dt.getMinutes()) );
	retVal = retVal.replace( new RegExp('TTT',  'gi'), hr +':'+ this.pad(dt.getMinutes()) +' '+ ampm );
	retVal = retVal.replace( new RegExp('CCCC', 'gi'), strShortDate +' '+ strShortTime ); 

	// finally timezone
	tz = dt.getTimezoneOffset();
	timezone = (tz<0) ? ('GMT-' + tz/60) : (tz==0) ? ('GMT') : ('GMT+' + tz/60);
	retVal = retVal.replace( new RegExp('ZZZZ', 'gi'), timezone );

	return retVal;
}

// ====================================

/* if desired, map new methods to direct functions
*/
IsDate = Date.IsDate;
CDate = Date.CDate;
DateAdd = Date.DateAdd;
DateDiff = Date.DateDiff;
DatePart = Date.DatePart;
MonthName = Date.MonthName;
WeekdayName = Date.WeekdayName;
Weekday = Date.Weekday;
FormatDateTime = Date.FormatDateTime;
Format = Date.Format;



/* and other capitalizations for easier porting
isDate = IsDate;
dateAdd = DateAdd;
dateDiff = DateDiff;
datePart = DatePart;
monthName = MonthName;
weekdayName = WeekdayName;
formatDateTime = FormatDateTime;
format = Format;

isdate = IsDate;
dateadd = DateAdd;
datediff = DateDiff;
datepart = DatePart;
monthname = MonthName;
weekdayname = WeekdayName;
formatdatetime = FormatDateTime;

ISDATE = IsDate;
DATEADD = DateAdd;
DATEDIFF = DateDiff;
DATEPART = DatePart;
MONTHNAME = MonthName;
WEEKDAYNAME = WeekdayName;
FORMATDATETIME = FormatDateTime;
FORMAT = Format;
*/
