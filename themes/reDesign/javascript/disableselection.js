/***********************************************
* Disable Text Selection script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
* Also added classfinder from http://www.webmasterworld.com/forum91/1729.htm
***********************************************/
function disableSelection() {
  var allPageTags = new Array();
  var allPageTags = document.getElementsByTagName("kbd");
  for (i=0;i<allPageTags.length;i++) {
      var target = allPageTags[i];
      //IE route
      if (typeof target.onselectstart!="undefined") {
        target.onselectstart=function(){return false}
      //Firefox route
      } else if (typeof target.style.MozUserSelect!="undefined") {
        target.style.MozUserSelect="none"
      //All other route (ie: Opera)
      } else {
        target.onmousedown=function(){return false}
      }

  }
}


function noTextSelect(id) {
      var target = document.getElementById(id);
      if (typeof target.onselectstart!="undefined") {
        target.onselectstart=function(){return false}
      } else if (typeof target.style.MozUserSelect!="undefined") {
        target.style.MozUserSelect="none"
      } else {
        target.onmousedown=function(){return false}
      }
}


function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  disableSelection();
});



/**
 * Header javascript utilites 
 */

function showlogin() {
  document.getElementById('log_in_box').style.display='';
  document.getElementById('headsearch').style.display='none';
	document.getElementById('loginandregister').style.display='none';
  document.getElementById('u_name').focus();
}

function closelogin() {
  document.getElementById('log_in_box').style.display='none';
  document.getElementById('headsearch').style.display='';
  document.getElementById('loginandregister').style.display='';
}

function showlangs() {

	var langs_pop_up = document.getElementById('languages_popup');

	if (langs_pop_up.style.display=='none') {
		langs_pop_up.style.display='';
	} else {
		langs_pop_up.style.display='none';
	}

}
function closelangs() {
	document.getElementById('languages_popup').style.display='none';
}
