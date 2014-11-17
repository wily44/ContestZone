/*
   Copyright 2014 Wilbur Yang

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

/**
 * Changes the background color of the document to "elem"
 */
function fillBody(elem) {
	document.bgColor = elem;
	document.body.style.backgroundColor = elem;
}

/**
 * Repeats a character to effectively create a gray color string
 */
function grayColor(elem) {
	var str = "#";
	for(var i = 0; i < 6; i++) str += elem;
	return str;
}

/**
 * Flashes the background color through a series of grayscale colors given
 */
function flash(i, delay, bgCycle) {
	if(i == 16) return;
	//alert(pause);
	fillBody(grayColor(bgCycle.substring(i, i + 1)));
	var t = setTimeout(function() {
		flash(i + 1, delay, bgCycle);
	}, delay);
}

/**
 * Fades the background from black to white
 */
function fadeToWhite(delay) {
	var bgCycle = "0123456789ABCDEF";
	flash(0, delay, bgCycle);
}

/**
 * Fades the background from white to black
 */
function fadeToBlack(delay) {
	var bgCycle = "FEDCBA9876543210";
	flash(0, delay, bgCycle);
}

/**
 * Fades the object through 2 alpha values, in a logarithmic fashion
 */
function flashOpacity(delay, sta, sto, element) {
	if(Math.abs(sta - sto) < 1) return;
	element.style.opacity = sta * .01;
	var t = setTimeout(function() {
		flashOpacity(delay, sta < sto ? sta += (sto - sta + 10) / 10 : sta - (sta - sto + 10) / 10, sto, element);
	}, delay);
}

/**
 * Fades the content of the webpage (in the wrapper div element)
 */
function fadeInContent(delay) {
	flashOpacity(delay, 0, 95, document.getElementById("wrapper"));
}

/**
 * Draws the logo with a canvas, if there is one
 */
function drawLogo() {
	var canvas = document.getElementById("logoCanvas");
	if(canvas != null)
	{
		var cxt = canvas.getContext("2d");
		cxt.fillStyle="#FF0000";
		cxt.fillRect(0, 0, 150, 75);
	}
}

/**
 * Initializes the webpage
 */
function init() {
	setFocus('start_here');
	document.getElementById("wrapper").style.opacity = 0;
	//drawLogo();
	//var delay = 5;
	//fadeToBlack(delay);
	//setTimeout(function(){fadeToWhite(delay);}, delay * 16);
	//setTimeout(function(){fadeInContent(20);}, delay * 32);
	fadeInContent(1);
}

/**
 * Progressively increments the size of the element
 */
function modSize(element, st, sp, delay) {
	if(element.size == sp)
		return;
	element.size = st;
	setTimeout(function(){modSize(element, st < sp ? st + 1 : st - 1, sp, delay)}, delay);
}

/**
 * Delays the modSize by d ms
 */
function delayModSize(element, st, sp, delay, d) {
	setTimeout(function(){modSize(element, st, sp, delay)}, d);
}

/**
 * Limits the size of text input to limit
 */
function limitText(element, limit) {
	if(element.value.length > limit)
		element.value = element.value.substring(0, limit);
}

/**
 * Sets the focus to an element
 */
function setFocus(id) {
	element = document.getElementById(id);
	if(element) element.focus();
}

function pixels(str) {
	alert(str);
	return str.substring(0, str.length - 2);
}

function retreatUp(element, start, stop) {
	if(start == stop)
		return;
	start--;
	element.style.top = start + "px";
	setTimeout(function() {
		retreatUp(element, start, stop)
	}, 7);
}

function setOpacity(element, alpha) {
	element.style.opacity = alpha;
}

function setSiteOpacity() {
	var element = document.getElementById("wrapper");
	setOpacity(element, 0.95);
}
