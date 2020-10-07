YUI.add("moodle-core-formchangechecker",function(e,t){var n="core-formchangechecker",r=function(){r.superclass.constructor.apply(this,arguments)};e.extend(r,e.Base,{initialvaluelisteners:[],initializer:function(){var t="form#"+this.get("formid"),n=e.one(t);if(!n)return;e.on(M.core.event.EDITOR_CONTENT_RESTORED,M.core_formchangechecker.reset_form_dirty_state,this),n.delegate("change",M.core_formchangechecker.set_form_changed,"input",this),n.delegate("change",M.core_formchangechecker.set_form_changed,"textarea",this),n.delegate("change",M.core_formchangechecker.set_form_changed,"select",this),this.initialvaluelisteners.push(n.delegate("focus",this.store_initial_value,"input",this)),this.initialvaluelisteners.push(n.delegate("focus",this.store_initial_value,"textarea",this)),this.initialvaluelisteners.push(n.delegate("focus",this.store_initial_value,"select",this)),e.one(t).on("submit",M.core_formchangechecker.set_form_submitted,this),window.onbeforeunload=M.core_formchangechecker.report_form_dirty_state},store_initial_value:function(e){var t;if(e.target.hasClass("ignoredirty")||e.target.ancestor(".ignoredirty"))return;if(M.core_formchangechecker.get_form_dirty_state()){while(this.initialvaluelisteners.length)t=this.initialvaluelisteners.shift(),t.detach();return}M.core_formchangechecker.stateinformation.focused_element={element:e.target,initial_value:e.target.get("value")}}},{NAME:n,ATTRS:{formid:{value:""}}}),M.core_formchangechecker=M.core_formchangechecker||{},M.core_formchangechecker.instances=M.core_formchangechecker.instances||[],M.core_formchangechecker.init=function(e){var t=new r(e);return M.core_formchangechecker.instances.push(t),t},M.core_formchangechecker.stateinformation=[],M.core_formchangechecker.set_form_changed=function(e){if(e&&e.target&&(e.target.hasClass("ignoredirty")||e.target.ancestor(".ignoredirty")))return;M.core_formchangechecker.stateinformation.formchanged=1,delete M.core_formchangechecker.stateinformation.focused_element},M.core_formchangechecker.set_form_submitted=function(){M.core_formchangechecker.stateinformation.formsubmitted=1},M.core_formchangechecker.get_form_dirty_state=function(){var e=M.core_formchangechecker.stateinformation,t;if(e.formsubmitted)return 0;if(e.formchanged)return 1;if(e.focused_element&&e.focused_element.element.get("value")!==e.focused_element.initial_value)return 1;if(typeof window.tinyMCE!="undefined")for(t in window.tinyMCE.editors)if(window.tinyMCE.editors[t].isDirty())return 1;return 0},M.core_formchangechecker.reset_form_dirty_state=function(){M.core_formchangechecker.stateinformation.formsubmitted=!1,M.core_formchangechecker.stateinformation.formchanged=!1},M.core_formchangechecker.report_form_dirty_state=function(e){if(!M.core_formchangechecker.get_form_dirty_state())return;var t=M.util.get_string("changesmadereallygoaway","moodle");if(M.cfg.behatsiterunning)return;return e&&(e.returnValue=t),t}},"@VERSION@",{requires:["base","event-focus","moodle-core-event"]});