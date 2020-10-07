YUI.add("intl",function(e,t){var n={},r="yuiRootLang",i="yuiActiveLang",s=[];e.mix(e.namespace("Intl"),{_mod:function(e){return n[e]||(n[e]={}),n[e]},setLang:function(e,t){var n=this._mod(e),s=n[i],o=!!n[t];return o&&t!==s&&(n[i]=t,this.fire("intl:langChange",{module:e,prevVal:s,newVal:t===r?"":t})),o},getLang:function(e){var t=this._mod(e)[i];return t===r?"":t},add:function(e,t,n){t=t||r,this._mod(e)[t]=n,this.setLang(e,t)},get:function(t,n,r){var s=this._mod(t),o;return r=r||s[i],o=s[r]||{},n?o[n]:e.merge(o)},getAvailableLangs:function(t){var n=e.Env._loader,r=n&&n.moduleInfo[t],i=r&&r.lang;return i?i.concat():s}}),e.augment(e.Intl,e.EventTarget),e.Intl.publish("intl:langChange",{emitFacade:!0})},"3.17.2",{requires:["intl-base","event-custom"]});YUI.add("autocomplete-base",function(e,t){function T(){}var n=e.Escape,r=e.Lang,i=e.Array,s=e.Object,o=r.isFunction,u=r.isString,a=r.trim,f=e.Attribute.INVALID_VALUE,l="_functionValidator",c="_sourceSuccess",h="allowBrowserAutocomplete",p="inputNode",d="query",v="queryDelimiter",m="requestTemplate",g="results",y="resultListLocator",b="value",w="valueChange",E="clear",S=d,x=g;T.prototype={initializer:function(){e.before(this._bindUIACBase,this,"bindUI"),e.before(this._syncUIACBase,this,"syncUI"),this.publish(E,{defaultFn:this._defClearFn}),this.publish(S,{defaultFn:this._defQueryFn}),this.publish(x,{defaultFn:this._defResultsFn})},destructor:function(){this._acBaseEvents&&this._acBaseEvents.detach(),delete this._acBaseEvents,delete this._cache,delete this._inputNode,delete this._rawSource},clearCache:function(){return this._cache&&(this._cache={}),this},sendRequest:function(t,n){var r,i=this.get("source");return t||t===""?this._set(d,t):t=this.get(d)||"",i&&(n||(n=this.get(m)),r=n?n.call(this,t):t,i.sendRequest({query:t,request:r,callback:{success:e.bind(this._onResponse,this,t)}})),this},_bindUIACBase:function(){var t=this.get(p),n=t&&t.tokenInput;n&&(t=n.get(p),this._set("tokenInput",n));if(!t){e.error("No inputNode specified.");return}this._inputNode=t,this._acBaseEvents=new e.EventHandle([t.on(w,this._onInputValueChange,this),t.on("blur",this._onInputBlur,this),this.after(h+"Change",this._syncBrowserAutocomplete),this.after("sourceTypeChange",this._afterSourceTypeChange),this.after(w,this._afterValueChange)])},_syncUIACBase:function(){this._syncBrowserAutocomplete(),this.set(b,this.get(p).get(b))},_createArraySource:function(e){var t=this;return{type:"array",sendRequest:function(n){t[c](e.concat(),n)}}},_createFunctionSource:function(e){var t=this;return{type:"function",sendRequest:function(n){function i(e){t[c](e||[],n)}var r;(r=e(n.query,i))&&i(r)}}},_createObjectSource:function(e){var t=this;return{type:"object",sendRequest:function(n){var r=n.query;t[c](s.owns(e,r)?e[r]:[],n)}}},_functionValidator:function(e){return e===null||o(e)},_getObjectValue:function(e,t){if(!e)return;for(var n=0,r=t.length;e&&n<r;n++)e=e[t[n]];return e},_parseResponse:function(e,t,r){var i={data:r,query:e,results:[]},s=this.get(y),o=[],u=t&&t.results,a,f,l,c,h,p,d,v,m,g,b;u&&s&&(u=s.call(this,u));if(u&&u.length){a=this.get("resultFilters"),b=this.get("resultTextLocator");for(p=0,d=u.length;p<d;++p)m=u[p],g=b?b.call(this,m):m.toString(),o.push({display:n.html(g),raw:m,text:g});for(p=0,d=a.length;p<d;++p){o=a[p].call(this,e,o.concat());if(!o)return;if(!o.length)break}if(o.length){l=this.get("resultFormatter"),h=this.get("resultHighlighter"),v=this.get("maxResults"),v&&v>0&&o.length>v&&(o.length=v);if(h){c=h.call(this,e,o.concat());if(!c)return;for(p=0,d=c.length;p<d;++p)m=o[p],m.highlighted=c[p],m.display=m.highlighted}if(l){f=l.call(this,e,o.concat());if(!f)return;for(p=0,d=f.length;p<d;++p)o[p].display=f[p]}}}i.results=o,this.fire(x,i)},_parseValue:function(e){var t=this.get(v);return t&&(e=e.split(t),e=e[e.length-1]),r.trimLeft(e)},_setEnableCache:function(e){this._cache=e?{}:null},_setLocator:function(e){if(this[l](e))return e;var t=this;return e=e.toString().split("."),function(n){return n&&t._getObjectValue(n,e)}},_setRequestTemplate:function(e){return this[l](e)?e:(e=e.toString(),function(t){return r.sub(e,{query:encodeURIComponent(t)})})},_setResultFilters:function(t){var n,s;return t===null?[]:(n=e.AutoCompleteFilters,s=function(e){return o(e)?e:u(e)&&n&&o(n[e])?n[e]:!1},r.isArray(t)?(t=i.map(t,s),i.every(t,function(e){return!!e})?t:f):(t=s(t),t?[t]:f))},_setResultHighlighter:function(t){var n;return this[l](t)?t:(n=e.AutoCompleteHighlighters,u(t)&&n&&o(n[t])?n[t]:f)},_setSource:function(t){var n=this.get("sourceType")||r.type(t),i;return t&&o(t.sendRequest)||t===null||n==="datasource"?(this._rawSource=t,t):(i=T.SOURCE_TYPES[n])?(this._rawSource=t,r.isString(i)?this[i](t):i(t)):(e.error("Unsupported source type '"+n+"'. Maybe autocomplete-sources isn't loaded?"),f)},_sourceSuccess:function(e,t){t.callback.success({data:e,response:{results:e}})},_syncBrowserAutocomplete:function(){var e=this.get(p);e.get("nodeName").toLowerCase()==="input"&&e.setAttribute("autocomplete",this.get(h)?"on":"off")},_updateValue:function(e){var t=this.get(v),n,s,o;e=r.trimLeft(e),t&&(n=a(t),o=i.map(a(this.get(b)).split(t),a),s=o.length,s>1&&(o[s-1]=e,e=o.join(n+" ")),e=e+n+" "),this.set(b,e)},_afterSourceTypeChange:function(e){this._rawSource&&this.set("source",this._rawSource)},_afterValueChange:function(e){var t=e.newVal,n=this,r=e.src===T.UI_SRC,i,s,o,u;r||n._inputNode.set(b,t),o=n.get("minQueryLength"),u=n._parseValue(t)||"",o>=0&&u.length>=o?r?(i=n.get("queryDelay"),s=function(){n.fire(S,{inputValue:t,query:u,src:e.src})},i?(clearTimeout(n._delay),n._delay=setTimeout(s,i)):s()):n._set(d,u):(clearTimeout(n._delay),n.fire(E,{prevVal:e.prevVal?n._parseValue(e.prevVal):null,src:e.src}))},_onInputBlur:function(e){var t=this.get(v),n,i,s;if(t&&!this.get("allowTrailingDelimiter")){t=r.trimRight(t),s=i=this._inputNode.get(b);if(t)while((i=r.trimRight(i))&&(n=i.length-t.length)&&i.lastIndexOf(t)===n)i=i.substring(0,n);else i=r.trimRight(i);i!==s&&this.set(b,i)}},_onInputValueChange:function(e){var t=e.newVal;t!==this.get(b)&&this.set(b,t,{src:T.UI_SRC})},_onResponse:function(e,t){e===(this.get(d)||"")&&this._parseResponse(e||"",t.response,t.data)},_defClearFn:function(){this._set(d,null),this._set(g,[])},_defQueryFn:function(e){this.sendRequest(e.query)},_defResultsFn:function(e){this._set(g,e[g])}},T.ATTRS={allowBrowserAutocomplete:{value:!1},allowTrailingDelimiter:{value:!1},enableCache:{lazyAdd:!1,setter:"_setEnableCache",value:!0},inputNode:{setter:e.one,writeOnce:"initOnly"},maxResults:{value:0},minQueryLength:{value:1},query:{readOnly:!0,value:null},queryDelay:{value:100},queryDelimiter:{value:null},requestTemplate:{setter:"_setRequestTemplate",value:null},resultFilters:{setter:"_setResultFilters",value:[]},resultFormatter:{validator:l,value:null},resultHighlighter:{setter:"_setResultHighlighter",value:null},resultListLocator:{setter:"_setLocator",value:null},results:{readOnly:!0,value:[]},resultTextLocator:{setter:"_setLocator",value:null},source:{setter:"_setSource",value:null},sourceType:{value:null},tokenInput:{readOnly:!0},value:{value:""}},T._buildCfg={aggregates:["SOURCE_TYPES"],statics:["UI_SRC"]},T.SOURCE_TYPES={array:"_createArraySource","function":"_createFunctionSource",object:"_createObjectSource"},T.UI_SRC=e.Widget&&e.Widget.UI_SRC||"ui",e.AutoCompleteBase=T},"3.17.2",{optional:["autocomplete-sources"],requires:["array-extras","base-build","escape","event-valuechange","node-base"]});YUI.add("autocomplete-sources",function(e,t){var n=e.AutoCompleteBase,r=e.Lang,i="_sourceSuccess",s="maxResults",o="requestTemplate",u="resultListLocator";e.mix(n.prototype,{_YQL_SOURCE_REGEX:/^(?:select|set|use)\s+/i,_beforeCreateObjectSource:function(t){return t instanceof e.Node&&t.get("nodeName").toLowerCase()==="select"?this._createSelectSource(t):e.JSONPRequest&&t instanceof e.JSONPRequest?this._createJSONPSource(t):this._createObjectSource(t)},_createIOSource:function(t){function a(n){var o=n.request;if(r._cache&&o in r._cache){r[i](r._cache[o],n);return}s&&s.isInProgress()&&s.abort(),s=e.io(r._getXHRUrl(t,n),{on:{success:function(t,s){var u;try{u=e.JSON.parse(s.responseText)}catch(a){e.error("JSON parse error",a)}u&&(r._cache&&(r._cache[o]=u),r[i](u,n))}}})}var n={type:"io"},r=this,s,o,u;return n.sendRequest=function(t){o=t;if(u)return;u=!0,e.use("io-base","json-parse",function(){n.sendRequest=a,a(o)})},n},_createJSONPSource:function(t){function u(e){var n=e.request,s=e.query;if(r._cache&&n in r._cache){r[i](r._cache[n],e);return}t._config.on.success=function(t){r._cache&&(r._cache[n]=t),r[i](t,e)},t.send(s)}var n={type:"jsonp"},r=this,s,o;return n.sendRequest=function(i){s=i;if(o)return;o=!0,e.use("jsonp",function(){t instanceof e.JSONPRequest||(t=new e.JSONPRequest(t,{format:e.bind(r._jsonpFormatter,r)})),n.sendRequest=u,u(s)})},n},_createSelectSource:function(e){var t=this;return{type:"select",sendRequest:function(n){var r=[];e.get("options").each(function(e){r.push({html:e.get("innerHTML"),index:e.get("index"),node:e,selected:e.get("selected"),text:e.get("text"),value:e.get("value")})}),t[i](r,n)}}},_createStringSource:function(e){return this._YQL_SOURCE_REGEX.test(e)?this._createYQLSource(e):e.indexOf("{callback}")!==-1?this._createJSONPSource(e):this._createIOSource(e)},_createYQLSource:function(t){function c(o){var u=o.query,a=n.get("yqlEnv"),f=n.get(s),c,h,p;p=r.sub(t,{maxResults:f>0?f:1e3,request:o.request,query:u});if(n._cache&&p in n._cache){n[i](n._cache[p],o);return}c=function(e){n._cache&&(n._cache[p]=e),n[i](e,o)},h={proto:n.get("yqlProtocol")},l?(l._callback=c,l._opts=h,l._params.q=p,a&&(l._params.env=a)):l=new e.YQLRequest(p,{on:{success:c},allowCache:!1},a?{env:a}:null,h),l.send()}var n=this,o={type:"yql"},a,f,l;return n.get(u)||n.set(u,n._defaultYQLLocator),o.sendRequest=function(t){a=t,f||(f=!0,e.use("yql",function(){o.sendRequest=c,c(a)}))},o},_defaultYQLLocator:function(t){var n=t&&t.query&&t.query.results,i;return n&&r.isObject(n)?(i=e.Object.values(n)||[],n=i.length===1?i[0]:i,r.isArray(n)||(n=[n])):n=[],n},_getXHRUrl:function(e,t){var n=this.get(s);return t.query!==t.request&&(e+=t.request),r.sub(e,{maxResults:n>0?n:1e3,query:encodeURIComponent(t.query)})},_jsonpFormatter:function(e,t,n){var i=this.get(s),u=this.get(o);return u&&(e+=u(n)),r.sub(e,{callback:t,maxResults:i>0?i:1e3,query:encodeURIComponent(n)})}}),e.mix(n.ATTRS,{yqlEnv:{value:null},yqlProtocol:{value:"http"}}),e.mix(n.SOURCE_TYPES,{io:"_createIOSource",jsonp:"_createJSONPSource",object:"_beforeCreateObjectSource",select:"_createSelectSource",string:"_createStringSource",yql:"_createYQLSource"},!0)},"3.17.2",{optional:["io-base","json-parse","jsonp","yql"],requires:["autocomplete-base"]});YUI.add("lang/autocomplete-list_en",function(e){e.Intl.add("autocomplete-list","en",{item_selected:"{item} selected.",items_available:"Suggestions are available. Use up and down arrows to select."})},"3.17.2");YUI.add("shim-plugin",function(e,t){function n(e){this.init(e)}n.CLASS_NAME="yui-node-shim",n.TEMPLATE='<iframe class="'+n.CLASS_NAME+'" frameborder="0" title="Node Stacking Shim"'+'src="javascript:false" tabindex="-1" role="presentation"'+'style="position:absolute; z-index:-1;"></iframe>',n.prototype={init:function(e){this._host=e.host,this.initEvents(),this.insert(),this.sync()},initEvents:function(){this._resizeHandle=this._host.on("resize",this.sync,this)},getShim:function(){return this._shim||(this._shim=e.Node.create(n.TEMPLATE,this._host.get("ownerDocument")))},insert:function(){var e=this._host;this._shim=e.insertBefore(this.getShim(),e.get("firstChild"))},sync:function(){var e=this._shim,t=this._host;e&&e.setAttrs({width:t.getStyle("width"),height:t.getStyle("height")})},destroy:function(){var e=this._shim;e&&e.remove(!0),this._resizeHandle.detach()}},n.NAME="Shim",n.NS="shim",e.namespace("Plugin"),e.Plugin.Shim=n},"3.17.2",{requires:["node-style","node-pluginhost"]});YUI.add("autocomplete-list",function(e,t){var n=e.Lang,r=e.Node,i=e.Array,s=e.UA.ie&&e.UA.ie<7,o=9,u="_CLASS_ITEM",a="_CLASS_ITEM_ACTIVE",f="_CLASS_ITEM_HOVER",l="_SELECTOR_ITEM",c="activeItem",h="alwaysShowList",p="circular",d="hoveredItem",v="id",m="item",g="list",y="result",b="results",w="visible",E="width",S="select",x=e.Base.create("autocompleteList",e.Widget,[e.AutoCompleteBase,e.WidgetPosition,e.WidgetPositionAlign],{ARIA_TEMPLATE:"<div/>",ITEM_TEMPLATE:"<li/>",LIST_TEMPLATE:"<ul/>",UI_EVENTS:function(){var t=e.merge(e.Node.DOM_EVENTS);return delete t.valuechange,delete t.valueChange,t}(),initializer:function(){var t=this.get("inputNode");if(!t){e.error("No inputNode specified.");return}this._inputNode=t,this._listEvents=[],this.DEF_PARENT_NODE=t.get("parentNode"),this[u]=this.getClassName(m),this[a]=this.getClassName(m,"active"),this[f]=this.getClassName(m,"hover"),this[l]="."+this[u],this.publish(S,{defaultFn:this._defSelectFn})},destructor:function(){while(this._listEvents.length)this._listEvents.pop().detach();this._ariaNode&&this._ariaNode.remove().destroy(!0)},bindUI:function(){this._bindInput(),this._bindList()},renderUI:function(){var t=this._createAriaNode(),n=this.get("boundingBox"),r=this.get("contentBox"),i=this._inputNode,o=this._createListNode(),u=i.get("parentNode");i.addClass(this.getClassName("input")).setAttrs({"aria-autocomplete":g,"aria-expanded":!1,"aria-owns":o.get("id")}),u.append(t),s&&n.plug(e.Plugin.Shim),this._ariaNode=t,this._boundingBox=n,this._contentBox=r,this._listNode=o,this._parentNode=u},syncUI:function(){this._syncResults(),this._syncVisibility()},hide:function(){return this.get(h)?this:this.set(w,!1)},selectItem:function(e,t){if(e){if(!e.hasClass(this[u]))return this}else{e=this.get(c);if(!e)return this}return this.fire(S,{itemNode:e,originEvent:t||null,result:e.getData(y)}),this},_activateNextItem:function(){var e=this.get(c),t;return e?t=e.next(this[l])||(this.get(p)?null:e):t=this._getFirstItemNode(),this.set(c,t),this},_activatePrevItem:function(){var e=this.get(c),t=e?e.previous(this[l]):this.get(p)&&this._getLastItemNode();return this.set(c,t||null),this},_add:function(t){var r=[];return i.each(n.isArray(t)?t:[t],function(e){r.push(this._createItemNode(e).setData(y,e))},this),r=e.all(r),this._listNode.append(r.toFrag()),r},_ariaSay:function(e,t){var r=this.get("strings."+e);this._ariaNode.set("text",t?n.sub(r,t):r)},_bindInput:function(){var e=this._inputNode,t,n,r;this.get("align")===null&&(r=this.get("tokenInput"),t=r&&r.get("boundingBox")||e,this.set("align",{node:t,points:["tl","bl"]}),!this.get(E)&&(n=t.get("offsetWidth"))&&this.set(E,n)),this._listEvents=this._listEvents.concat([e.after("blur",this._afterListInputBlur,this),e.after("focus",this._afterListInputFocus,this)])},_bindList:function(){this._listEvents=this._listEvents.concat([e.one("doc").after("click",this._afterDocClick,this),e.one("win").after("windowresize",this._syncPosition,this),this.after({mouseover:this._afterMouseOver,mouseout:this._afterMouseOut,activeItemChange:this._afterActiveItemChange,alwaysShowListChange:this._afterAlwaysShowListChange,hoveredItemChange:this._afterHoveredItemChange,resultsChange:this._afterResultsChange,visibleChange:this._afterVisibleChange}),this._listNode.delegate("click",this._onItemClick,this[l],this)])},_clear:function(){this.set(c,null),this._set(d,null),this._listNode.get("children").remove(!0)},_createAriaNode:function(){var e=r.create(this.ARIA_TEMPLATE);return e.addClass(this.getClassName("aria")).setAttrs({"aria-live":"polite",role:"status"})},_createItemNode:function(t){var n=r.create(this.ITEM_TEMPLATE);return n.addClass(this[u]).setAttrs({id:e.stamp(n),role:"option"}).setAttribute("data-text",t.text).append(t.display)},_createListNode:function(){var t=this.get("listNode")||r.create(this.LIST_TEMPLATE);return t.addClass(this.getClassName(g)).setAttrs({id:e.stamp(t),role:"listbox"}),this._set("listNode",t),this.get("contentBox").append(t),t},_getFirstItemNode:function(){return this._listNode.one(this[l])},_getLastItemNode:function(){return this._listNode.one(this[l]+":last-child")},_syncPosition:function(){this._syncUIPosAlign(),this._syncShim()},_syncResults:function(e){e||(e=this.get(b)),this._clear(),e.length&&(this._add(e),this._ariaSay("items_available")),this._syncPosition(),this.get("activateFirstItem")&&!this.get(c)&&this.set(c,this._getFirstItemNode())},_syncShim:s?function(){var e=this._boundingBox.shim;e&&e.sync()}:function(){},_syncVisibility:function(t){this.get(h)&&(t=!0,this.set(w,t)),typeof t=="undefined"&&(t=this.get(w)),this._inputNode.set("aria-expanded",t),this._boundingBox.set("aria-hidden",!t),t?this._syncPosition():(this.set(c,null),this._set(d,null),this._boundingBox.get("offsetWidth")),e.UA.ie===7&&e.one("body").addClass("yui3-ie7-sucks").removeClass("yui3-ie7-sucks")},_afterActiveItemChange:function(t){var n=this._inputNode,r=t.newVal,i=t.prevVal,s;i&&i._node&&i.removeClass(this[a]),r?(r.addClass(this[a]),n.set("aria-activedescendant",r.get(v))):n.removeAttribute("aria-activedescendant"),this.get("scrollIntoView")&&(s=r||n,(!s.inRegion(e.DOM.viewportRegion(),!0)||!s.inRegion(this._contentBox,!0))&&s.scrollIntoView())},_afterAlwaysShowListChange:function(e){this.set(w,e.newVal||this.get(b).length>0)},_afterDocClick:function(e){var t=this._boundingBox,n=e.target;n!==this._inputNode&&n!==t&&!n.ancestor("#"+t.get("id"),!0)&&this.hide()},_afterHoveredItemChange:function(e){var t=e.newVal,n=e.prevVal;n&&n.removeClass(this[f]),t&&t.addClass(this[f])},_afterListInputBlur:function(){this._listInputFocused=!1,this.get(w)&&!this._mouseOverList&&(this._lastInputKey!==o||!this.get("tabSelect")||!this.get(c))&&this.hide()},_afterListInputFocus:function(){this._listInputFocused=!0},_afterMouseOver:function(e){var t=e.domEvent.target.ancestor(this[l],!0);this._mouseOverList=!0,t&&this._set(d,t)},_afterMouseOut:function(){this._mouseOverList=!1,this._set(d,null)},_afterResultsChange:function(e){this._syncResults(e.newVal),this.get(h)||this.set(w,!!e.newVal.length)},_afterVisibleChange:function(e){this._syncVisibility(!!e.newVal)},_onItemClick:function(e){var t=e.currentTarget;this.set(c,t),this.selectItem(t,e)},_defSelectFn:function(e){var t=e.result.text;this._inputNode.focus(),this._updateValue(t),this._ariaSay("item_selected",{item:t}),this.hide()}},{ATTRS:{activateFirstItem:{value:!1},activeItem:{setter:e.one,value:null},alwaysShowList:{value:!1},circular:{value:!0},hoveredItem:{readOnly:!0,value:null},listNode:{writeOnce:"initOnly",value:null},scrollIntoView:{value:!1},strings:{valueFn:function(){return e.Intl.get("autocomplete-list")}},tabSelect:{value:!0},visible:{value:!1}},CSS_PREFIX:e.ClassNameManager.getClassName("aclist")});e.AutoCompleteList=x,e.AutoComplete=x},"3.17.2",{lang:["en","es","hu","it"],requires:["autocomplete-base","event-resize","node-screen","selector-css3","shim-plugin","widget","widget-position","widget-position-align"],skinnable:!0});YUI.add("autocomplete-list-keys",function(e,t){function u(){e.before(this._bindKeys,this,"bindUI"),this._initKeys()}var n=40,r=13,i=27,s=9,o=38;u.prototype={_initKeys:function(){var e={},t={};e[n]=this._keyDown,t[r]=this._keyEnter,t[i]=this._keyEsc,t[s]=this._keyTab,t[o]=this._keyUp,this._keys=e,this._keysVisible=t},destructor:function(){this._unbindKeys()},_bindKeys:function(){this._keyEvents=this._inputNode.on("keydown",this._onInputKey,this)},_unbindKeys:function(){this._keyEvents&&this._keyEvents.detach(),this._keyEvents=null},_keyDown:function(){this.get("visible")?this._activateNextItem():this.show()},_keyEnter:function(e){var t=this.get("activeItem");if(!t)return!1;this.selectItem(t,e)},_keyEsc:function(){this.hide()},_keyTab:function(e){var t;if(this.get("tabSelect")){t=this.get("activeItem");if(t)return this.selectItem(t,e),!0}return!1},_keyUp:function(){this._activatePrevItem()},_onInputKey:function(e){var t,n=e.keyCode;this._lastInputKey=n,this.get("results").length&&(t=this._keys[n],!t&&this.get("visible")&&(t=this._keysVisible[n]),t&&t.call(this,e)!==!1&&e.preventDefault())}},e.Base.mix(e.AutoCompleteList,[u])},"3.17.2",{requires:["autocomplete-list","base-build"]});YUI.add("autocomplete-plugin",function(e,t){function r(e){e.inputNode=e.host,!e.render&&e.render!==!1&&(e.render=!0),r.superclass.constructor.apply(this,arguments)}var n=e.Plugin;e.extend(r,e.AutoCompleteList,{},{NAME:"autocompleteListPlugin",NS:"ac",CSS_PREFIX:e.ClassNameManager.getClassName("aclist")}),n.AutoComplete=r,n.AutoCompleteList=r},"3.17.2",{requires:["autocomplete-list","node-pluginhost"]});YUI.add("text-data-wordbreak",function(e,t){e.namespace("Text.Data").WordBreak={aletter:"[A-Za-z\u00aa\u00b5\u00ba\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u02c1\u02c6-\u02d1\u02e0-\u02e4\u02ec\u02ee\u0370-\u0374\u0376\u0377\u037a-\u037d\u0386\u0388-\u038a\u038c\u038e-\u03a1\u03a3-\u03f5\u03f7-\u0481\u048a-\u0527\u0531-\u0556\u0559\u0561-\u0587\u05d0-\u05ea\u05f0-\u05f3\u0620-\u064a\u066e\u066f\u0671-\u06d3\u06d5\u06e5\u06e6\u06ee\u06ef\u06fa-\u06fc\u06ff\u0710\u0712-\u072f\u074d-\u07a5\u07b1\u07ca-\u07ea\u07f4\u07f5\u07fa\u0800-\u0815\u081a\u0824\u0828\u0840-\u0858\u0904-\u0939\u093d\u0950\u0958-\u0961\u0971-\u0977\u0979-\u097f\u0985-\u098c\u098f\u0990\u0993-\u09a8\u09aa-\u09b0\u09b2\u09b6-\u09b9\u09bd\u09ce\u09dc\u09dd\u09df-\u09e1\u09f0\u09f1\u0a05-\u0a0a\u0a0f\u0a10\u0a13-\u0a28\u0a2a-\u0a30\u0a32\u0a33\u0a35\u0a36\u0a38\u0a39\u0a59-\u0a5c\u0a5e\u0a72-\u0a74\u0a85-\u0a8d\u0a8f-\u0a91\u0a93-\u0aa8\u0aaa-\u0ab0\u0ab2\u0ab3\u0ab5-\u0ab9\u0abd\u0ad0\u0ae0\u0ae1\u0b05-\u0b0c\u0b0f\u0b10\u0b13-\u0b28\u0b2a-\u0b30\u0b32\u0b33\u0b35-\u0b39\u0b3d\u0b5c\u0b5d\u0b5f-\u0b61\u0b71\u0b83\u0b85-\u0b8a\u0b8e-\u0b90\u0b92-\u0b95\u0b99\u0b9a\u0b9c\u0b9e\u0b9f\u0ba3\u0ba4\u0ba8-\u0baa\u0bae-\u0bb9\u0bd0\u0c05-\u0c0c\u0c0e-\u0c10\u0c12-\u0c28\u0c2a-\u0c33\u0c35-\u0c39\u0c3d\u0c58\u0c59\u0c60\u0c61\u0c85-\u0c8c\u0c8e-\u0c90\u0c92-\u0ca8\u0caa-\u0cb3\u0cb5-\u0cb9\u0cbd\u0cde\u0ce0\u0ce1\u0cf1\u0cf2\u0d05-\u0d0c\u0d0e-\u0d10\u0d12-\u0d3a\u0d3d\u0d4e\u0d60\u0d61\u0d7a-\u0d7f\u0d85-\u0d96\u0d9a-\u0db1\u0db3-\u0dbb\u0dbd\u0dc0-\u0dc6\u0f00\u0f40-\u0f47\u0f49-\u0f6c\u0f88-\u0f8c\u10a0-\u10c5\u10d0-\u10fa\u10fc\u1100-\u1248\u124a-\u124d\u1250-\u1256\u1258\u125a-\u125d\u1260-\u1288\u128a-\u128d\u1290-\u12b0\u12b2-\u12b5\u12b8-\u12be\u12c0\u12c2-\u12c5\u12c8-\u12d6\u12d8-\u1310\u1312-\u1315\u1318-\u135a\u1380-\u138f\u13a0-\u13f4\u1401-\u166c\u166f-\u167f\u1681-\u169a\u16a0-\u16ea\u16ee-\u16f0\u1700-\u170c\u170e-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176c\u176e-\u1770\u1820-\u1877\u1880-\u18a8\u18aa\u18b0-\u18f5\u1900-\u191c\u1a00-\u1a16\u1b05-\u1b33\u1b45-\u1b4b\u1b83-\u1ba0\u1bae\u1baf\u1bc0-\u1be5\u1c00-\u1c23\u1c4d-\u1c4f\u1c5a-\u1c7d\u1ce9-\u1cec\u1cee-\u1cf1\u1d00-\u1dbf\u1e00-\u1f15\u1f18-\u1f1d\u1f20-\u1f45\u1f48-\u1f4d\u1f50-\u1f57\u1f59\u1f5b\u1f5d\u1f5f-\u1f7d\u1f80-\u1fb4\u1fb6-\u1fbc\u1fbe\u1fc2-\u1fc4\u1fc6-\u1fcc\u1fd0-\u1fd3\u1fd6-\u1fdb\u1fe0-\u1fec\u1ff2-\u1ff4\u1ff6-\u1ffc\u2071\u207f\u2090-\u209c\u2102\u2107\u210a-\u2113\u2115\u2119-\u211d\u2124\u2126\u2128\u212a-\u212d\u212f-\u2139\u213c-\u213f\u2145-\u2149\u214e\u2160-\u2188\u24b6-\u24e9\u2c00-\u2c2e\u2c30-\u2c5e\u2c60-\u2ce4\u2ceb-\u2cee\u2d00-\u2d25\u2d30-\u2d65\u2d6f\u2d80-\u2d96\u2da0-\u2da6\u2da8-\u2dae\u2db0-\u2db6\u2db8-\u2dbe\u2dc0-\u2dc6\u2dc8-\u2dce\u2dd0-\u2dd6\u2dd8-\u2dde\u2e2f\u3005\u303b\u303c\u3105-\u312d\u3131-\u318e\u31a0-\u31ba\ua000-\ua48c\ua4d0-\ua4fd\ua500-\ua60c\ua610-\ua61f\ua62a\ua62b\ua640-\ua66e\ua67f-\ua697\ua6a0-\ua6ef\ua717-\ua71f\ua722-\ua788\ua78b-\ua78e\ua790\ua791\ua7a0-\ua7a9\ua7fa-\ua801\ua803-\ua805\ua807-\ua80a\ua80c-\ua822\ua840-\ua873\ua882-\ua8b3\ua8f2-\ua8f7\ua8fb\ua90a-\ua925\ua930-\ua946\ua960-\ua97c\ua984-\ua9b2\ua9cf\uaa00-\uaa28\uaa40-\uaa42\uaa44-\uaa4b\uab01-\uab06\uab09-\uab0e\uab11-\uab16\uab20-\uab26\uab28-\uab2e\uabc0-\uabe2\uac00-\ud7a3\ud7b0-\ud7c6\ud7cb-\ud7fb\ufb00-\ufb06\ufb13-\ufb17\ufb1d\ufb1f-\ufb28\ufb2a-\ufb36\ufb38-\ufb3c\ufb3e\ufb40\ufb41\ufb43\ufb44\ufb46-\ufbb1\ufbd3-\ufd3d\ufd50-\ufd8f\ufd92-\ufdc7\ufdf0-\ufdfb\ufe70-\ufe74\ufe76-\ufefc\uff21-\uff3a\uff41-\uff5a\uffa0-\uffbe\uffc2-\uffc7\uffca-\uffcf\uffd2-\uffd7\uffda-\uffdc]",midnumlet:"['\\.\u2018\u2019\u2024\ufe52\uff07\uff0e]",midletter:"[:\u00b7\u00b7\u05f4\u2027\ufe13\ufe55\uff1a]",midnum:"[,;;\u0589\u060c\u060d\u066c\u07f8\u2044\ufe10\ufe14\ufe50\ufe54\uff0c\uff1b]",numeric:"[0-9\u0660-\u0669\u066b\u06f0-\u06f9\u07c0-\u07c9\u0966-\u096f\u09e6-\u09ef\u0a66-\u0a6f\u0ae6-\u0aef\u0b66-\u0b6f\u0be6-\u0bef\u0c66-\u0c6f\u0ce6-\u0cef\u0d66-\u0d6f\u0e50-\u0e59\u0ed0-\u0ed9\u0f20-\u0f29\u1040-\u1049\u1090-\u1099\u17e0-\u17e9\u1810-\u1819\u1946-\u194f\u19d0-\u19d9\u1a80-\u1a89\u1a90-\u1a99\u1b50-\u1b59\u1bb0-\u1bb9\u1c40-\u1c49\u1c50-\u1c59\ua620-\ua629\ua8d0-\ua8d9\ua900-\ua909\ua9d0-\ua9d9\uaa50-\uaa59\uabf0-\uabf9]",cr:"\\r",lf:"\\n",newline:"[\f\u0085\u2028\u2029]",extend:"[\u0300-\u036f\u0483-\u0489\u0591-\u05bd\u05bf\u05c1\u05c2\u05c4\u05c5\u05c7\u0610-\u061a\u064b-\u065f\u0670\u06d6-\u06dc\u06df-\u06e4\u06e7\u06e8\u06ea-\u06ed\u0711\u0730-\u074a\u07a6-\u07b0\u07eb-\u07f3\u0816-\u0819\u081b-\u0823\u0825-\u0827\u0829-\u082d\u0859-\u085b\u0900-\u0903\u093a-\u093c\u093e-\u094f\u0951-\u0957\u0962\u0963\u0981-\u0983\u09bc\u09be-\u09c4\u09c7\u09c8\u09cb-\u09cd\u09d7\u09e2\u09e3\u0a01-\u0a03\u0a3c\u0a3e-\u0a42\u0a47\u0a48\u0a4b-\u0a4d\u0a51\u0a70\u0a71\u0a75\u0a81-\u0a83\u0abc\u0abe-\u0ac5\u0ac7-\u0ac9\u0acb-\u0acd\u0ae2\u0ae3\u0b01-\u0b03\u0b3c\u0b3e-\u0b44\u0b47\u0b48\u0b4b-\u0b4d\u0b56\u0b57\u0b62\u0b63\u0b82\u0bbe-\u0bc2\u0bc6-\u0bc8\u0bca-\u0bcd\u0bd7\u0c01-\u0c03\u0c3e-\u0c44\u0c46-\u0c48\u0c4a-\u0c4d\u0c55\u0c56\u0c62\u0c63\u0c82\u0c83\u0cbc\u0cbe-\u0cc4\u0cc6-\u0cc8\u0cca-\u0ccd\u0cd5\u0cd6\u0ce2\u0ce3\u0d02\u0d03\u0d3e-\u0d44\u0d46-\u0d48\u0d4a-\u0d4d\u0d57\u0d62\u0d63\u0d82\u0d83\u0dca\u0dcf-\u0dd4\u0dd6\u0dd8-\u0ddf\u0df2\u0df3\u0e31\u0e34-\u0e3a\u0e47-\u0e4e\u0eb1\u0eb4-\u0eb9\u0ebb\u0ebc\u0ec8-\u0ecd\u0f18\u0f19\u0f35\u0f37\u0f39\u0f3e\u0f3f\u0f71-\u0f84\u0f86\u0f87\u0f8d-\u0f97\u0f99-\u0fbc\u0fc6\u102b-\u103e\u1056-\u1059\u105e-\u1060\u1062-\u1064\u1067-\u106d\u1071-\u1074\u1082-\u108d\u108f\u109a-\u109d\u135d-\u135f\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17b6-\u17d3\u17dd\u180b-\u180d\u18a9\u1920-\u192b\u1930-\u193b\u19b0-\u19c0\u19c8\u19c9\u1a17-\u1a1b\u1a55-\u1a5e\u1a60-\u1a7c\u1a7f\u1b00-\u1b04\u1b34-\u1b44\u1b6b-\u1b73\u1b80-\u1b82\u1ba1-\u1baa\u1be6-\u1bf3\u1c24-\u1c37\u1cd0-\u1cd2\u1cd4-\u1ce8\u1ced\u1cf2\u1dc0-\u1de6\u1dfc-\u1dff\u200c\u200d\u20d0-\u20f0\u2cef-\u2cf1\u2d7f\u2de0-\u2dff\u302a-\u302f\u3099\u309a\ua66f-\ua672\ua67c\ua67d\ua6f0\ua6f1\ua802\ua806\ua80b\ua823-\ua827\ua880\ua881\ua8b4-\ua8c4\ua8e0-\ua8f1\ua926-\ua92d\ua947-\ua953\ua980-\ua983\ua9b3-\ua9c0\uaa29-\uaa36\uaa43\uaa4c\uaa4d\uaa7b\uaab0\uaab2-\uaab4\uaab7\uaab8\uaabe\uaabf\uaac1\uabe3-\uabea\uabec\uabed\ufb1e\ufe00-\ufe0f\ufe20-\ufe26\uff9e\uff9f]",format:"[\u00ad\u0600-\u0603\u06dd\u070f\u17b4\u17b5\u200e\u200f\u202a-\u202e\u2060-\u2064\u206a-\u206f\ufeff\ufff9-\ufffb]",katakana:"[\u3031-\u3035\u309b\u309c\u30a0-\u30fa\u30fc-\u30ff\u31f0-\u31ff\u32d0-\u32fe\u3300-\u3357\uff66-\uff9d]",extendnumlet:"[_\u203f\u2040\u2054\ufe33\ufe34\ufe4d-\ufe4f\uff3f]",punctuation:"[!-#%-*,-\\/:;?@\\[-\\]_{}\u00a1\u00ab\u00b7\u00bb\u00bf;\u00b7\u055a-\u055f\u0589\u058a\u05be\u05c0\u05c3\u05c6\u05f3\u05f4\u0609\u060a\u060c\u060d\u061b\u061e\u061f\u066a-\u066d\u06d4\u0700-\u070d\u07f7-\u07f9\u0830-\u083e\u085e\u0964\u0965\u0970\u0df4\u0e4f\u0e5a\u0e5b\u0f04-\u0f12\u0f3a-\u0f3d\u0f85\u0fd0-\u0fd4\u0fd9\u0fda\u104a-\u104f\u10fb\u1361-\u1368\u1400\u166d\u166e\u169b\u169c\u16eb-\u16ed\u1735\u1736\u17d4-\u17d6\u17d8-\u17da\u1800-\u180a\u1944\u1945\u1a1e\u1a1f\u1aa0-\u1aa6\u1aa8-\u1aad\u1b5a-\u1b60\u1bfc-\u1bff\u1c3b-\u1c3f\u1c7e\u1c7f\u1cd3\u2010-\u2027\u2030-\u2043\u2045-\u2051\u2053-\u205e\u207d\u207e\u208d\u208e\u3008\u3009\u2768-\u2775\u27c5\u27c6\u27e6-\u27ef\u2983-\u2998\u29d8-\u29db\u29fc\u29fd\u2cf9-\u2cfc\u2cfe\u2cff\u2d70\u2e00-\u2e2e\u2e30\u2e31\u3001-\u3003\u3008-\u3011\u3014-\u301f\u3030\u303d\u30a0\u30fb\ua4fe\ua4ff\ua60d-\ua60f\ua673\ua67e\ua6f2-\ua6f7\ua874-\ua877\ua8ce\ua8cf\ua8f8-\ua8fa\ua92e\ua92f\ua95f\ua9c1-\ua9cd\ua9de\ua9df\uaa5c-\uaa5f\uaade\uaadf\uabeb\ufd3e\ufd3f\ufe10-\ufe19\ufe30-\ufe52\ufe54-\ufe61\ufe63\ufe68\ufe6a\ufe6b\uff01-\uff03\uff05-\uff0a\uff0c-\uff0f\uff1a\uff1b\uff1f\uff20\uff3b-\uff3d\uff3f\uff5b\uff5d\uff5f-\uff65]"}},"3.17.2",{requires:["yui-base"]});YUI.add("text-wordbreak",function(e,t){var n=e.Text,r=n.Data.WordBreak,i=0,s=1,o=2,u=3,a=4,f=5,l=6,c=7,h=8,p=9,d=10,v=11,m=12,g=[new RegExp(r.aletter),new RegExp(r.midnumlet),new RegExp(r.midletter),new RegExp(r.midnum),new RegExp(r.numeric),new RegExp(r.cr),new RegExp(r.lf),new RegExp(r.newline),new RegExp(r.extend),new RegExp(r.format),new RegExp(r.katakana),new RegExp(r.extendnumlet)],y="",b=new RegExp("^"+r.punctuation+"$"),w=/\s/,E={getWords:function(e,t){var n=0,r=E._classify(e),i=r.length,s=[],o=[],u,a,f;t||(t={}),t.ignoreCase&&(e=e.toLowerCase()),a=t.includePunctuation,f=t.includeWhitespace;for(;n<i;++n)u=e.charAt(n),s.push(u),E._isWordBoundary(r,n)&&(s=s.join(y),s&&(f||!w.test(s))&&(a||!b.test(s))&&o.push(s),s=[]);return o},getUniqueWords:function(t,n){return e.Array.unique(E.getWords(t,n))},isWordBoundary:function(e,t){return E._isWordBoundary(E._classify(e),t)},_classify:function(e){var t,n=[],r=0,i,s,o=e.length,u=g.length,a;for(;r<o;++r){t=e.charAt(r),a=m;for(i=0;i<u;++i){s=g[i];if(s&&s.test(t)){a=i;break}}n.push(a)}return n},_isWordBoundary:function(e,t){var n,r=e[t],m=e[t+1],g;return t<0||t>e.length-1&&t!==0?!1:r===i&&m===i?!1:(g=e[t+2],r!==i||m!==o&&m!==s||g!==i?(n=e[t-1],r!==o&&r!==s||m!==i||n!==i?r!==a&&r!==i||m!==a&&m!==i?r!==u&&r!==s||m!==a||n!==a?r!==a||m!==u&&m!==s||g!==a?r===h||r===p||n===h||n===p||m===h||m===p?!1:r===f&&m===l?!1:r===c||r===f||r===l?!0:m===c||m===f||m===l?!0:r===d&&m===d?!1:m!==v||r!==i&&r!==a&&r!==d&&r!==v?r!==v||m!==i&&m!==a&&m!==d?!0:!1:!1:!1:!1:!1:!1):!1)}};n.WordBreak=E},"3.17.2",{requires:["array-extras","text-data-wordbreak"]});YUI.add("autocomplete-filters",function(e,t){var n=e.Array,r=e.Object,i=e.Text.WordBreak,s=e.mix(e.namespace("AutoCompleteFilters"),{charMatch:function(e,t,r){if(!e)return t;var i=n.unique((r?e:e.toLowerCase()).split(""));return n.filter(t,function(e){return e=e.text,r||(e=e.toLowerCase()),n.every(i,function(t){return e.indexOf(t)!==-1})})},charMatchCase:function(e,t){return s.charMatch(e,t,!0)},phraseMatch:function(e,t,r){return e?(r||(e=e.toLowerCase()),n.filter(t,function(t){return(r?t.text:t.text.toLowerCase()).indexOf(e)!==-1})):t},phraseMatchCase:function(e,t){return s.phraseMatch(e,t,!0)},startsWith:function(e,t,r){return e?(r||(e=e.toLowerCase()),n.filter(t,function(t){return(r?t.text:t.text.toLowerCase()).indexOf(e)===0})):t},startsWithCase:function(e,t){return s.startsWith(e,t,!0)},subWordMatch:function(e,t,r){if(!e)return t;var s=i.getUniqueWords(e,{ignoreCase:!r});return n.filter(t,function(e){var t=r?e.text:e.text.toLowerCase();return n.every(s,function(e){return t.indexOf(e)!==-1})})},subWordMatchCase:function(e,t){return s.subWordMatch(e,t,!0)},wordMatch:function(e,t,s){if(!e)return t;var o={ignoreCase:!s},u=i.getUniqueWords(e,o);return n.filter(t,function(e){var t=n.hash(i.getUniqueWords(e.text,o));return n.every(u,function(e){return r.owns(t,e)})})},wordMatchCase:function(e,t){return s.wordMatch(e,t,!0)}})},"3.17.2",{requires:["array-extras","text-wordbreak"]});YUI.add("highlight-base",function(e,t){var n=e.Array,r=e.Escape,i=e.Text.WordBreak,s=e.Lang.isArray,o={},u="(&[^;\\s]*)?",a={_REGEX:u+"(%needles)",_REPLACER:function(e,t,n){return t&&!/\s/.test(n)?e:a._TEMPLATE.replace(/\{s\}/g,n)},_START_REGEX:"^"+u+"(%needles)",_TEMPLATE:'<b class="'+e.ClassNameManager.getClassName("highlight")+'">{s}</b>',all:function(e,t,n){var i=[],u,f,l,c,h,p;n||(n=o),u=n.escapeHTML!==!1,h=n.startsWith?a._START_REGEX:a._REGEX,p=n.replacer||a._REPLACER,t=s(t)?t:[t];for(f=0,l=t.length;f<l;++f)c=t[f],c&&i.push(r.regex(u?r.html(c):c));return u&&(e=r.html(e)),i.length?e.replace(new RegExp(h.replace("%needles",i.join("|")),n.caseSensitive?"g":"gi"),p):e},allCase:function(t,n,r){return a.all(t,n,e.merge(r||o,{caseSensitive:!0}))},start:function(t,n,r){return a.all(t,n,e.merge(r||o,{startsWith:!0}))},startCase:function(e,t){return a.start(e,t,{caseSensitive:!0})},words:function(e,t,u){var f,l,c=a._TEMPLATE,h;return u||(u=o),f=!!u.caseSensitive,t=n.hash(s(t)?t:i.getUniqueWords(t,{ignoreCase:!f})),l=u.mapper||function(e,t){return t.hasOwnProperty(f?e:e.toLowerCase())?c.replace(/\{s\}/g,r.html(e)):r.html(e)},h=i.getWords(e,{includePunctuation:!0,includeWhitespace:!0}),n.map(h,function(e){return l(e,t)}).join("")},wordsCase:function(e,t){return a.words(e,t,{caseSensitive:!0})}};e.Highlight=a},"3.17.2",{requires:["array-extras","classnamemanager","escape","text-wordbreak"]});YUI.add("autocomplete-highlighters",function(e,t){var n=e.Array,r=e.Highlight,i=e.mix(e.namespace("AutoCompleteHighlighters"),{charMatch:function(e,t,i){var s=n.unique((i?e:e.toLowerCase()).split(""));return n.map(t,function(e){return r.all(e.text,s,{caseSensitive:i})})},charMatchCase:function(e,t){return i.charMatch(e,t,!0)},phraseMatch:function(e,t,i){return n.map(t,function(t){return r.all(t.text,[e],{caseSensitive:i})})},phraseMatchCase:function(e,t){return i.phraseMatch(e,t,!0)},startsWith:function(e,t,i){return n.map(t,function(t){return r.all(t.text,[e],{caseSensitive:i,startsWith:!0})})},startsWithCase:function(e,t){return i.startsWith(e,t,!0)},subWordMatch:function(t,i,s){var o=e.Text.WordBreak.getUniqueWords(t,{ignoreCase:!s});return n.map(i,function(e){return r.all(e.text,o,{caseSensitive:s})})},subWordMatchCase:function(e,t){return i.subWordMatch(e,t,!0)},wordMatch:function(e,t,i){return n.map(t,function(t){return r.words(t.text,e,{caseSensitive:i})})},wordMatchCase:function(e,t){return i.wordMatch(e,t,!0)}})},"3.17.2",{requires:["array-extras","highlight-base"]});YUI.add("moodle-mod_notebook-tagselector",function(e,t){M.mod_notebook=M.mod_notebook||{},M.mod_notebook.tagselector={init:function(t,n){var r=e.one("form #"+t);if(n&&typeof n=="object"){var i=[];for(var s in n)i.push(n[s]);n=i}r&&(r.plug(e.Plugin.AutoComplete,{minQueryLength:0,queryDelay:100,queryDelimiter:",",allowTrailingDelimiter:!0,source:n,width:"auto",scrollIntoView:!0,circular:!1,resultTextLocator:"tag",resultHighlighter:"startsWith",resultFilters:["startsWith",function(t,n){function u(e,t){return e.raw.tag<t.raw.tag?-1:e.raw.tag>t.raw.tag?1:0}var i="",s=r.get("value").lastIndexOf(",");s>0&&(i=r.get("value").substring(0,s).split(/\s*,\s*/)),i=e.Array.hash(i);var o=e.Array.filter(n,function(e){return!i.hasOwnProperty(e.text)});return o.sort(u)}],resultFormatter:function(t,n){return e.Array.map(n,function(e){var t='<div class="tagselector_result"><span class="tagselector_result_title">'+e.highlighted+"</span>";return e.raw.label&&(t+=' <span class="tagselector_result_info tagselector_result_info_label">'+e.raw.label+"</span>"),t+=' <span class="tagselector_result_info">'+M.util.get_string("numposts","notebook",e.raw.count)+"</span>"+"</div>",t})}}),r.on("focus",function(){r.ac.sendRequest("")}),r.ac.after("select",function(t){e.UA.chrome&&window.scrollBy(0,parseInt(r.getStyle("height"))+200),setTimeout(function(){r.ac.sendRequest(""),r.ac.show()},1)}))}}},"@VERSION@",{requires:["base","node","autocomplete","autocomplete-filters","autocomplete-highlighters"]});