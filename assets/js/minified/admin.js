"use strict";function _typeof(obj){"@babel/helpers - typeof";return _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(obj){return typeof obj}:function(obj){return obj&&"function"==typeof Symbol&&obj.constructor===Symbol&&obj!==Symbol.prototype?"symbol":typeof obj},_typeof(obj)}function ownKeys(object,enumerableOnly){var keys=Object.keys(object);if(Object.getOwnPropertySymbols){var symbols=Object.getOwnPropertySymbols(object);enumerableOnly&&(symbols=symbols.filter(function(sym){return Object.getOwnPropertyDescriptor(object,sym).enumerable})),keys.push.apply(keys,symbols)}return keys}function _objectSpread(target){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?ownKeys(Object(source),!0).forEach(function(key){_defineProperty(target,key,source[key])}):Object.getOwnPropertyDescriptors?Object.defineProperties(target,Object.getOwnPropertyDescriptors(source)):ownKeys(Object(source)).forEach(function(key){Object.defineProperty(target,key,Object.getOwnPropertyDescriptor(source,key))})}return target}function _defineProperty(obj,key,value){key=_toPropertyKey(key);if(key in obj){Object.defineProperty(obj,key,{value:value,enumerable:true,configurable:true,writable:true})}else{obj[key]=value}return obj}function _toPropertyKey(arg){var key=_toPrimitive(arg,"string");return _typeof(key)==="symbol"?key:String(key)}function _toPrimitive(input,hint){if(_typeof(input)!=="object"||input===null)return input;var prim=input[Symbol.toPrimitive];if(prim!==undefined){var res=prim.call(input,hint||"default");if(_typeof(res)!=="object")return res;throw new TypeError("@@toPrimitive must return a primitive value.")}return(hint==="string"?String:Number)(input)}(function(wp,$){"use strict";if(!wp){return}var __=wp.i18n.__;var admin={cache:function cache(){this.els={};this.vars={};this.els.$startDate=$("[name=\"woo_store_vacation_options[start_date]\"]");this.els.$endDate=$("[name=\"woo_store_vacation_options[end_date]\"]");this.vars.dayInSeconds=24*60*60*1000},init:function init(){this.cache();this.datePickers()},datePickers:function datePickers(){this.addDeleteButtonDatepicker();var commonDatepickerOptions={changeMonth:true,changeYear:true,showButtonPanel:true,buttonImageOnly:true,numberOfMonths:1,showOn:"focus",dateFormat:"yy-mm-dd",beforeShowDay:function beforeShowDay(date){var startDate=admin.els.$startDate.datepicker("getDate");var endDate=admin.els.$endDate.datepicker("getDate");if(startDate&&endDate&&date>=startDate&&date<=endDate){return[true,"highlight",""]}return[true,"",""]}};this.els.$startDate.datepicker(_objectSpread(_objectSpread({},commonDatepickerOptions),{},{beforeShow:function beforeShow(){var maxDate=admin.els.$endDate.val();if(maxDate){$(this).datepicker("option","maxDate",new Date(Date.parse(maxDate)-admin.vars.dayInSeconds))}},onClose:function onClose(selectedDate){var minDate=new Date(Date.parse(selectedDate)+admin.vars.dayInSeconds);admin.els.$endDate.datepicker("option","minDate",minDate)}}));this.els.$endDate.datepicker(_objectSpread(_objectSpread({},commonDatepickerOptions),{},{minDate:"+1D",beforeShow:function beforeShow(){var minDate=admin.els.$startDate.val();if(minDate){$(this).datepicker("option","minDate",new Date(Date.parse(minDate)+admin.vars.dayInSeconds))}},onClose:function onClose(selectedDate){var maxDate=new Date(Date.parse(selectedDate)-admin.vars.dayInSeconds);admin.els.$startDate.datepicker("option","maxDate",maxDate);var today=new Date;today.setHours(0,0,0,0);var endDate=new Date(selectedDate);if(endDate&&endDate<today){$(this).css("border","1px solid red");return}$(this).css("border","")}}));var oldGoToToday=$.datepicker._gotoToday;$.datepicker._gotoToday=function(id){oldGoToToday.call(this,id);this._selectDate(id)}},addDeleteButtonDatepicker:function addDeleteButtonDatepicker(){var oldFn=$.datepicker._updateDatepicker;$.datepicker._updateDatepicker=function(inst){oldFn.call(this,inst);var buttonPane=$(this).datepicker("widget").find(".ui-datepicker-buttonpane");$("<button type=\"button\" class=\"ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all\">\n\t\t\t\t\t\t".concat(__("Delete","woo-store-vacation"),"\n\t\t\t\t\t</button>")).appendTo(buttonPane).on("click",function(){$.datepicker._clearDate(inst.input)})}}};admin.init()})(window.wp,jQuery);