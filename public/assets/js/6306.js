"use strict";(self.webpackChunkclimbing_loc=self.webpackChunkclimbing_loc||[]).push([[6306],{6306:(t,a,i)=>{i.r(a),i.d(a,{default:()=>o});var e=i(4443);const s={mixins:[e.z],props:[],data:function(){return{data:{us_data:{title:"",short_description:"",text:"",route:"",how_get:"",best_time:""},ru_data:{title:"",short_description:"",text:"",route:"",how_get:"",best_time:""},ka_data:{title:"",short_description:"",text:"",route:"",how_get:"",best_time:""},global_data:{demo_image:"",map:"",weather:""}},editor_config:{us_short_description_text_editor:e.z.get_small_editor_config(),us_text_editor_config:e.z.get_big_editor_config(),us_info_editor_config:e.z.get_big_editor_config(),us_how_get_editor_config:e.z.get_big_editor_config(),us_best_time_editor_config:e.z.get_big_editor_config(),ka_short_description_text_editor:e.z.get_small_editor_config(),ka_text_editor_config:e.z.get_big_editor_config(),ka_info_editor_config:e.z.get_big_editor_config(),ka_how_get_editor_config:e.z.get_big_editor_config(),ka_best_time_editor_config:e.z.get_big_editor_config(),ru_short_description_text_editor:e.z.get_small_editor_config(),ru_text_editor_config:e.z.get_big_editor_config(),ru_info_editor_config:e.z.get_big_editor_config(),ru_how_get_editor_config:e.z.get_big_editor_config(),ru_best_time_editor_config:e.z.get_big_editor_config()},is_loading:!1,tab_num:1,errors:[],is_back_action:!1}},mounted:function(){this.get_editing_data()},beforeRouteLeave:function(t,a,i){1==this.is_back_action?window.confirm("Added information will be deleted!!! Are you sure, you want go back?")?(this.is_back_action=!1,i()):i(!1):i()},methods:{edit_mount:function(){var t=this;axios.post("/mount/edit_mount_massive/"+this.$route.params.id,{data:this.data}).then((function(a){t.go_back(!0)})).catch((function(a){422==a.response.status&&(t.errors=a.response.data.validation)}))},get_editing_data:function(){var t=this;axios.get("/mount/get_editing_mount_data/"+this.$route.params.id).then((function(a){t.data=a.data}))},go_back:function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.is_back_action=this.$going.back(this,t)}}};const o=(0,i(1900).Z)(s,(function(){var t=this,a=t._self._c;return a("div",{staticClass:"tabs"},[t.is_loading?a("div",{staticClass:"row justify-content-center"},[a("div",{staticClass:"col-md-4"},[a("img",{attrs:{src:"../../../../../../public/images/site_img/loading.gif",alt:"loading"}})])]):t._e(),t._v(" "),t.is_loading?t._e():a("div",{staticClass:"row"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-primary",attrs:{type:"submit"},on:{click:function(a){return t.go_back()}}},[t._v("Beck")])])]),t._v(" "),t.is_loading?t._e():a("div",{staticClass:"row"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-primary",attrs:{type:"submit"},on:{click:function(a){return t.edit_mount()}}},[t._v("Save")])])]),t._v(" "),t.is_loading?t._e():a("div",{staticClass:"row"},[0!=t.errors.length?a("div",{staticClass:"col-md-12"},[t.errors.global_info_validation.name?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Demo name - "+t._s(t.errors.global_info_validation.name[0])+"\n            ")]):t._e(),t._v(" "),t.errors.global_info_validation.us_title_for_url_title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Us title - "+t._s(t.errors.global_info_validation.us_title_for_url_title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.us_info_validation.title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                English title - "+t._s(t.errors.us_info_validation.title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.us_info_validation.short_description?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                English description - "+t._s(t.errors.us_info_validation.short_description[0])+"\n            ")]):t._e(),t._v(" "),t.errors.us_info_validation.text?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                English text - "+t._s(t.errors.us_info_validation.text[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ka_info_validation.title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Georgian title - "+t._s(t.errors.ka_info_validation.title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ka_info_validation.short_description?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Georgian description - "+t._s(t.errors.ka_info_validation.short_description[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ka_info_validation.text?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Georgian text - "+t._s(t.errors.ka_info_validation.text[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ru_info_validation.title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Russion title - "+t._s(t.errors.ru_info_validation.title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ru_info_validation.short_description?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Russiondescription - "+t._s(t.errors.ru_info_validation.short_description[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ru_info_validation.text?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Russion text - "+t._s(t.errors.ru_info_validation.text[0])+"\n            ")]):t._e()]):t._e()]),t._v(" "),t.is_loading?t._e():a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"1"},domProps:{value:1,checked:t._q(t.tab_num,1)},on:{change:function(a){t.tab_num=1}}}),t._v(" "),a("label",{attrs:{for:"1"}},[t._v("Global info")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"2"},domProps:{value:2,checked:t._q(t.tab_num,2)},on:{change:function(a){t.tab_num=2}}}),t._v(" "),a("label",{attrs:{for:"2"}},[t._v("English text")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"4"},domProps:{value:4,checked:t._q(t.tab_num,4)},on:{change:function(a){t.tab_num=4}}}),t._v(" "),a("label",{attrs:{for:"4"}},[t._v("Georgian text")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"3"},domProps:{value:3,checked:t._q(t.tab_num,3)},on:{change:function(a){t.tab_num=3}}}),t._v(" "),a("label",{attrs:{for:"3"}},[t._v("Russion text")])])])]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{directives:[{name:"show",rawName:"v-show",value:1==t.tab_num,expression:"tab_num == 1"}],staticClass:"row width_100"},[t._m(0),t._v(" "),a("form",[a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Demo name ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.global_data.name,expression:"data.global_data.name"}],staticClass:"form-control",attrs:{type:"text",name:"demo name"},domProps:{value:t.data.global_data.name},on:{input:function(a){a.target.composing||t.$set(t.data.global_data,"name",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Map ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.global_data.map,expression:"data.global_data.map"}],staticClass:"form-control",attrs:{type:"text",name:"map"},domProps:{value:t.data.global_data.map},on:{input:function(a){a.target.composing||t.$set(t.data.global_data,"map",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Weather ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.global_data.weather,expression:"data.global_data.weather"}],staticClass:"form-control",attrs:{type:"text",name:"weather"},domProps:{value:t.data.global_data.weather},on:{input:function(a){a.target.composing||t.$set(t.data.global_data,"weather",a.target.value)}}})])])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:2==t.tab_num,expression:"tab_num == 2"}],staticClass:"row"},[t._m(1),t._v(" "),a("form",[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title english ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.us_data.title,expression:"data.us_data.title"}],staticClass:"form-control",attrs:{type:"text",name:"name"},domProps:{value:t.data.us_data.title},on:{input:function(a){a.target.composing||t.$set(t.data.us_data,"title",a.target.value)}}})])]),t._v(" "),a("hr"),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" English text ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.us_text_editor_config},model:{value:t.data.us_data.text,callback:function(a){t.$set(t.data.us_data,"text",a)},expression:"data.us_data.text"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" English description ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.us_short_description_text_editor},model:{value:t.data.us_data.short_description,callback:function(a){t.$set(t.data.us_data,"short_description",a)},expression:"data.us_data.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" English how get hear ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.us_how_get_editor_config},model:{value:t.data.us_data.how_get,callback:function(a){t.$set(t.data.us_data,"how_get",a)},expression:"data.us_data.how_get"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" English best time ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.us_best_time_editor_config},model:{value:t.data.us_data.best_time,callback:function(a){t.$set(t.data.us_data,"best_time",a)},expression:"data.us_data.best_time"}})],1)])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:3==t.tab_num,expression:"tab_num == 3"}],staticClass:"row"},[t._m(2),t._v(" "),a("form",[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title rusian ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.ru_data.title,expression:"data.ru_data.title"}],staticClass:"form-control",attrs:{type:"text",name:"name_ru"},domProps:{value:t.data.ru_data.title},on:{input:function(a){a.target.composing||t.$set(t.data.ru_data,"title",a.target.value)}}})])]),t._v(" "),a("hr"),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Rusian text ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ru_text_editor_config},model:{value:t.data.ru_data.text,callback:function(a){t.$set(t.data.ru_data,"text",a)},expression:"data.ru_data.text"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Rusian description ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ru_short_description_text_editor},model:{value:t.data.ru_data.short_description,callback:function(a){t.$set(t.data.ru_data,"short_description",a)},expression:"data.ru_data.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Rusian how get hear ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ru_how_get_editor_config},model:{value:t.data.ru_data.how_get,callback:function(a){t.$set(t.data.ru_data,"how_get",a)},expression:"data.ru_data.how_get"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Rusian best time ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ru_best_time_editor_config},model:{value:t.data.ru_data.best_time,callback:function(a){t.$set(t.data.ru_data,"best_time",a)},expression:"data.ru_data.best_time"}})],1)])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:4==t.tab_num,expression:"tab_num == 4"}],staticClass:"row"},[t._m(3),t._v(" "),a("form",[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title georgian ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.ka_data.title,expression:"data.ka_data.title"}],staticClass:"form-control",attrs:{type:"text",name:"name_ka"},domProps:{value:t.data.ka_data.title},on:{input:function(a){a.target.composing||t.$set(t.data.ka_data,"title",a.target.value)}}})])]),t._v(" "),a("hr"),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Georgian text ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ka_text_editor_config},model:{value:t.data.ka_data.text,callback:function(a){t.$set(t.data.ka_data,"text",a)},expression:"data.ka_data.text"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Georgian description ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ka_short_description_text_editor},model:{value:t.data.ka_data.short_description,callback:function(a){t.$set(t.data.ka_data,"short_description",a)},expression:"data.ka_data.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Georgian how get hear ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ka_how_get_editor_config},model:{value:t.data.ka_data.how_get,callback:function(a){t.$set(t.data.ka_data,"how_get",a)},expression:"data.ka_data.how_get"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Georgian best time ")]),t._v(" "),a("div",{staticClass:"col-xs-10"},[a("ckeditor",{attrs:{config:t.editor_config.ka_best_time_editor_config},model:{value:t.data.ka_data.best_time,callback:function(a){t.$set(t.data.ka_data,"best_time",a)},expression:"data.ka_data.best_time"}})],1)])])])])])])}),[function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron width_100"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("h2",{staticClass:"display-4"},[a("span",[t._v("Mount masive global information")])]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Mount masive global information.")])])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron width_100"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[a("span",[t._v("Mount masive English information")])]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Mount masive English information.")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron width_100"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[a("span",[t._v("Mount masive Georgian information")])]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Mount masive Georgian information.")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron width_100"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[a("span",[t._v("Mount masive Russion information")])]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Mount masive Russion information.")])])])}],!1,null,null,null).exports}}]);