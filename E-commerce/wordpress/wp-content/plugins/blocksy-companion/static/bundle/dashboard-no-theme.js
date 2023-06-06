!function(){"use strict";var e=window.wp.element,t=window.wp.i18n;function n(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,o=new Array(t);n<t;n++)o[n]=e[n];return o}var o=function(){var o,a,r=(o=(0,e.useState)(!1),a=2,function(e){if(Array.isArray(e))return e}(o)||function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],o=!0,a=!1,r=void 0;try{for(var c,l=e[Symbol.iterator]();!(o=(c=l.next()).done)&&(n.push(c.value),!t||n.length!==t);o=!0);}catch(e){a=!0,r=e}finally{try{o||null==l.return||l.return()}finally{if(a)throw r}}return n}}(o,a)||function(e,t){if(e){if("string"==typeof e)return n(e,t);var o=Object.prototype.toString.call(e).slice(8,-1);return"Object"===o&&e.constructor&&(o=e.constructor.name),"Map"===o||"Set"===o?Array.from(e):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?n(e,t):void 0}}(o,a)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()),c=r[0],l=r[1];return(0,e.createElement)("div",{className:"ct-theme-required"},(0,e.createElement)("h2",null,(0,e.createElement)("span",null,(0,e.createElement)("svg",{viewBox:"0 0 24 24"},(0,e.createElement)("path",{d:"M12,23.6c-1.4,0-2.6-1-2.8-2.3L8.9,20h6.2l-0.3,1.3C14.6,22.6,13.4,23.6,12,23.6z M24,17.8H0l3.1-2c0.5-0.3,0.9-0.7,1.1-1.3c0.5-1,0.5-2.2,0.5-3.2V7.6c0-4.1,3.2-7.3,7.3-7.3s7.3,3.2,7.3,7.3v3.6c0,1.1,0.1,2.3,0.5,3.2c0.3,0.5,0.6,1,1.1,1.3L24,17.8zM6.1,15.6h11.8c0,0-0.1-0.1-0.1-0.2c-0.7-1.3-0.7-2.9-0.7-4.2V7.6c0-2.8-2.2-5.1-5.1-5.1c-2.8,0-5.1,2.2-5.1,5.1v3.6c0,1.3-0.1,2.9-0.7,4.2C6.1,15.5,6.1,15.6,6.1,15.6z"}))),(0,t.__)("Action Required - Install Blocksy Theme","blocksy-companion")),(0,e.createElement)("p",null,(0,t.__)("Blocksy Companion is the complementary plugin to Blocksy theme. It adds a bunch of great features to the theme and acts as an unlocker for the Blocksy Pro package.","blocksy-companion")),(0,e.createElement)("p",null,(0,t.__)("In order to take full advantage of all features it has to offer - please install and activate the Blocksy theme also.","blocksy-companion")),(0,e.createElement)("button",{className:"button button-primary",onClick:function(e){e.preventDefault(),l(!0),ctDashboardLocalizations.themeIsInstalled?location=ctDashboardLocalizations.activate:wp.updates.ajax("install-theme",{success:function(){console.log("here we go s"),location=ctDashboardLocalizations.activate},error:function(){console.log("here we go error"),setTimeout((function(){location=ctDashboardLocalizations.activate}))},slug:"blocksy"})}},c?(0,t.__)("Loading...","blocksy-companion"):(0,t.__)("Install and activate the Blocksy theme","blocksy-companion")))};function a(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,o=new Array(t);n<t;n++)o[n]=e[n];return o}var r=function(){var n,o,r=(n=(0,e.useState)(!1),o=2,function(e){if(Array.isArray(e))return e}(n)||function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],o=!0,a=!1,r=void 0;try{for(var c,l=e[Symbol.iterator]();!(o=(c=l.next()).done)&&(n.push(c.value),!t||n.length!==t);o=!0);}catch(e){a=!0,r=e}finally{try{o||null==l.return||l.return()}finally{if(a)throw r}}return n}}(n,o)||function(e,t){if(e){if("string"==typeof e)return a(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?a(e,t):void 0}}(n,o)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}());return r[0],r[1],(0,e.createElement)("div",{className:"ct-theme-required"},(0,e.createElement)("h2",null,(0,e.createElement)("span",null,(0,e.createElement)("svg",{viewBox:"0 0 24 24"},(0,e.createElement)("path",{d:"M12,23.6c-1.4,0-2.6-1-2.8-2.3L8.9,20h6.2l-0.3,1.3C14.6,22.6,13.4,23.6,12,23.6z M24,17.8H0l3.1-2c0.5-0.3,0.9-0.7,1.1-1.3c0.5-1,0.5-2.2,0.5-3.2V7.6c0-4.1,3.2-7.3,7.3-7.3s7.3,3.2,7.3,7.3v3.6c0,1.1,0.1,2.3,0.5,3.2c0.3,0.5,0.6,1,1.1,1.3L24,17.8zM6.1,15.6h11.8c0,0-0.1-0.1-0.1-0.2c-0.7-1.3-0.7-2.9-0.7-4.2V7.6c0-2.8-2.2-5.1-5.1-5.1c-2.8,0-5.1,2.2-5.1,5.1v3.6c0,1.3-0.1,2.9-0.7,4.2C6.1,15.5,6.1,15.6,6.1,15.6z"}))),(0,t.__)("Action Required - Blocksy Theme and Companion version mismatch","blocksy-companion")),(0,e.createElement)("p",null,(0,t.__)("We detected that you are using an outdated version of Blocksy Theme. Please update it to the latest version.","blocksy-companion")),(0,e.createElement)("p",null,(0,t.__)("In order to take full advantage of all features it has to offer - please install and activate the latest versions of both Blocksy theme and Blocksy Companion plugin.","blocksy-companion")),(0,e.createElement)("button",{className:"button button-primary",onClick:function(e){e.preventDefault(),location=ctDashboardLocalizations.run_updates}},(0,t.__)("Update Now","blocksy-companion")))},c=function(){return ctDashboardLocalizations.theme_version_mismatch?(0,e.createElement)(r,null):(0,e.createElement)(o,null)};document.addEventListener("DOMContentLoaded",(function(){document.getElementById("ct-dashboard")&&(0,e.render)((0,e.createElement)(c,null),document.getElementById("ct-dashboard"))}))}();