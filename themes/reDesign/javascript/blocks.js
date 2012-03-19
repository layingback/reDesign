var PANEL_ANIMATION_DELAY = 10; /*ms*/
var PANEL_ANIMATION_STEPS = 10;
var PANEL_COLLAPSED_CLASS = "collapsed";
// The content class which height will be changed
var PANEL_CONTENT_CLASS   = "blockcontent";


function addClass(element, value) {
    var newClassName;
    if (!element.className) {
        element.className = value;
    } else {
        newClassName = element.className;
        newClassName += " ";
        newClassName += value;
        element.className = newClassName;
    }
}


function hasClass(ele,cls) {
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}


function removeClass(ele,cls) {
	if (hasClass(ele,cls)) {
		var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	}
}


/**
 * Start the expand/collapse animation of the panel
 * @param panel reference to the panel div
 */
function animateTogglePanel(panel, expanding) {
	// find the .blockcontent div
	var elements = panel.getElementsByTagName("div");
	var blockcontent = null;
	for (var i=0; i<elements.length; i++) {
		if (elements[i].className == PANEL_CONTENT_CLASS) {
			blockcontent = elements[i];
			break;
		}
	}

	// make sure the content is visible before getting its height
	blockcontent.style.display = "block";

	// get the height of the content
	var contentHeight = blockcontent.offsetHeight;

	// if panel is collapsed and expanding, we must start with 0 height
	if (expanding) {
		blockcontent.style.height = "0px";
	}

	var stepHeight = contentHeight / PANEL_ANIMATION_STEPS;
	var direction = (!expanding ? -1 : 1);

	setTimeout(function(){animateStep(blockcontent,1,stepHeight,direction)}, PANEL_ANIMATION_DELAY);
}

/**
 * Change the height of the target
 * @param blockcontent	reference to the panel content to change height
 * @param iteration		current iteration; animation will be stopped when iteration reaches PANEL_ANIMATION_STEPS
 * @param stepHeight	height increment to be added/substracted in one step
 * @param direction		1 for expanding, -1 for collapsing
 */
function animateStep(blockcontent, iteration, stepHeight, direction) {
	if (iteration<PANEL_ANIMATION_STEPS) {
		blockcontent.style.height = Math.round(((direction>0) ? iteration : 10 - iteration) * stepHeight) +"px";
		iteration++;
		setTimeout(function(){animateStep(blockcontent,iteration,stepHeight,direction)}, PANEL_ANIMATION_DELAY);
	// Last step
	} else {
		// set class for the panel
		if (direction < 0) {
			addClass(blockcontent.parentNode, PANEL_COLLAPSED_CLASS);
		} else {
			removeClass(blockcontent.parentNode, PANEL_COLLAPSED_CLASS);
		}

		// clear inline styles
		blockcontent.style.display = blockcontent.style.height = "";
	}
}


// Should be compatible with other themes, by keeping cookie design the same
function toggleBlockCollapsed(blockID) {
	// block content element id
	var bpe = document.getElementById('pe'+blockID);

	// If was collapsed (show it)
	if (bpe && hasClass(bpe, PANEL_COLLAPSED_CLASS)) {
		hiddenblocks[blockID] = null;
		animateTogglePanel(bpe, true);
	//If was not collapsed (collapse it)
	} else if (bpe) {
        hiddenblocks[blockID] = blockID;
		animateTogglePanel(bpe, false);
	}


	//Manage cookies
	var cookie = null;
	for (var q = 0; q < hiddenblocks.length; q++) {
		if (hiddenblocks[q] != null) {
			cookie = (cookie != null) ? (cookie+":"+hiddenblocks[q]) : hiddenblocks[q];
		}
	}
	if (cookie != null) {
		var exp = new Date();
		exp.setTime(exp.getTime() + (24 * 60 * 60 * 1000 * 365));
		var expstr = "; expires=" + exp.toGMTString();
		document.cookie = "hiddenblocks=" + escape(cookie) + expstr + "; path=/;";
	} else if (GetCookie("hiddenblocks")) {
		document.cookie = "hiddenblocks=:; expires = Thu, 01-Jan-70 00:00:01 GMT; path=/;";
	}
}
