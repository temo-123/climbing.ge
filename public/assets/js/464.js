"use strict";(self.webpackChunkclimbing_loc=self.webpackChunkclimbing_loc||[]).push([[464],{464:(t,a,i)=>{i.r(a),i.d(a,{default:()=>o});var e=i(4443);const s={mixins:[e.z],props:["back_url"],data:function(){return{tab_num:1,service_images:[],editorConfig:"",us_short_description_text_editor:e.z.get_small_editor_config(),us_text_editor_config:e.z.get_big_editor_config(),us_info_editor_config:e.z.get_big_editor_config(),ru_short_description_text_editor:e.z.get_small_editor_config(),ru_text_editor_config:e.z.get_big_editor_config(),ru_info_editor_config:e.z.get_big_editor_config(),ka_short_description_text_editor:e.z.get_small_editor_config(),ka_text_editor_config:e.z.get_big_editor_config(),ka_info_editor_config:e.z.get_big_editor_config(),data:{global_data:{us_title_for_url_title:"",published:0},us_data:{title:"",short_description:"",text:""},ka_data:{title:"",short_description:"",text:""},ru_data:{title:"",short_description:"",text:""}},myModal:!1}},mounted:function(){},methods:{onFileChange:function(t,a){var i=t.target.files[0],e=a-1;this.service_images[e].image=i},add_service_new_image_value:function(){var t=this.service_images.length+1;this.service_images.push({id:t,image:""})},del_service_image:function(t){this.removeObjectWithId(this.service_images,t)},removeObjectWithId:function(t,a){var i=t.findIndex((function(t){return t.id===a}));return t.splice(i,1),t},add_service:function(){var t=this;this.data.global_data.us_title_for_url_title=this.data.us_data.title;var a=new FormData,i=0;this.service_images.forEach((function(t){a.append("service_images["+i+"]",t.image),i++})),i=0,a.append("data",JSON.stringify(this.data)),axios.post("../api/service/add_service",a).then((function(a){confirm("Do you want send notification about editing sector?")?t.sand_notification():t.go_back(!0)})).catch((function(t){return console.log(t)}))},sand_notification:function(){var t=this;this.is_mail_sending_procesing=!0,axios.post("../../../api/user/notifications/send_service_adding_notification").then((function(a){t.go_back(!0)})).catch((function(t){console.log(t)})).finally((function(){return t.is_mail_sending_procesing=!1}))},go_back:function(){0==(arguments.length>0&&void 0!==arguments[0]&&arguments[0])?confirm("Are you sure, you want go back?")&&this.$router.go(-1):this.$router.go(-1)}}};const o=(0,i(1900).Z)(s,(function(){var t=this,a=t._self._c;return a("div",{staticClass:"tabs"},[a("div",{staticClass:"row"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-primary",attrs:{type:"submit"},on:{click:function(a){return t.go_back()}}},[t._v("Beck")])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-primary",attrs:{type:"submit"},on:{click:function(a){return t.add_service()}}},[t._v("Save")])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"1"},domProps:{value:1,checked:t._q(t.tab_num,1)},on:{change:function(a){t.tab_num=1}}}),t._v(" "),a("label",{attrs:{for:"1"}},[t._v("Global info")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"2"},domProps:{value:2,checked:t._q(t.tab_num,2)},on:{change:function(a){t.tab_num=2}}}),t._v(" "),a("label",{attrs:{for:"2"}},[t._v("English text")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"3"},domProps:{value:3,checked:t._q(t.tab_num,3)},on:{change:function(a){t.tab_num=3}}}),t._v(" "),a("label",{attrs:{for:"3"}},[t._v("Georgian text")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"4"},domProps:{value:4,checked:t._q(t.tab_num,4)},on:{change:function(a){t.tab_num=4}}}),t._v(" "),a("label",{attrs:{for:"4"}},[t._v("Russion text")])])])]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{directives:[{name:"show",rawName:"v-show",value:1==t.tab_num,expression:"tab_num == 1"}],staticClass:"row width_100"},[t._m(0),t._v(" "),a("form",{ref:"myForm",staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",id:"global_form",enctyp:"multipart/form-data"}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Publish ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.global_data.published,expression:"data.global_data.published"}],staticClass:"form-control",attrs:{name:"published"},on:{change:function(a){var i=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data.global_data,"published",a.target.multiple?i:i[0])}}},[a("option",{attrs:{value:"0"}},[t._v("Not public")]),t._v(" "),a("option",{attrs:{value:"1"}},[t._v("Public")])])])])]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"form-groupe"},[t.service_images.length<8?a("button",{staticClass:"btn btn-primary float-left",on:{click:function(a){return t.add_service_new_image_value()}}},[t._v("Add new service image")]):t._e()])])]),t._v(" "),a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("table",{staticClass:"table table-hover",attrs:{id:"dev-table"}},[t._m(1),t._v(" "),a("tbody",t._l(t.service_images,(function(i){return a("tr",{key:i.id},[a("td",[a("form",{ref:"myForm",refInFor:!0},[a("input",{attrs:{type:"file",name:"image",id:"image"},on:{change:function(a){return t.onFileChange(a,i.id)}}})])]),t._v(" "),a("td",[t._v("|")]),t._v(" "),a("td",[a("button",{staticClass:"btn btn-danger",on:{click:function(a){return t.del_service_image(i.id)}}},[t._v("Delete")])])])})),0)])])])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:2==t.tab_num,expression:"tab_num == 2"}],staticClass:"row"},[t._m(2),t._v(" "),a("form",{staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",enctyp:"multipart/form-data"}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.us_data.title,expression:"data.us_data.title"}],staticClass:"form-control",attrs:{type:"text",name:"name"},domProps:{value:t.data.us_data.title},on:{input:function(a){a.target.composing||t.$set(t.data.us_data,"title",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Short description ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.us_short_description_text_editor},model:{value:t.data.us_data.short_description,callback:function(a){t.$set(t.data.us_data,"short_description",a)},expression:"data.us_data.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" text ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.us_text_editor_config},model:{value:t.data.us_data.text,callback:function(a){t.$set(t.data.us_data,"text",a)},expression:"data.us_data.text"}})],1)])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:3==t.tab_num,expression:"tab_num == 3"}],staticClass:"row"},[t._m(3),t._v(" "),a("form",{staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",enctyp:"multipart/form-data"}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.ru_data.title,expression:"data.ru_data.title"}],staticClass:"form-control",attrs:{type:"text",name:"title"},domProps:{value:t.data.ru_data.title},on:{input:function(a){a.target.composing||t.$set(t.data.ru_data,"title",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Short description ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.ru_short_description_text_editor},model:{value:t.data.ru_data.short_description,callback:function(a){t.$set(t.data.ru_data,"short_description",a)},expression:"data.ru_data.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" text ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.ru_text_editor_config},model:{value:t.data.ru_data.text,callback:function(a){t.$set(t.data.ru_data,"text",a)},expression:"data.ru_data.text"}})],1)])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:4==t.tab_num,expression:"tab_num == 4"}],staticClass:"row"},[t._m(4),t._v(" "),a("form",{staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",enctyp:"multipart/form-data"},on:{submit:function(a){return a.preventDefault(),t.add_ka_article.apply(null,arguments)}}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.ka_data.title,expression:"data.ka_data.title"}],staticClass:"form-control",attrs:{type:"text",name:"value name"},domProps:{value:t.data.ka_data.title},on:{input:function(a){a.target.composing||t.$set(t.data.ka_data,"title",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Short description ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.ka_short_description_text_editor},model:{value:t.data.ka_data.short_description,callback:function(a){t.$set(t.data.ka_data,"short_description",a)},expression:"data.ka_data.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" text ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.ka_text_editor_config},model:{value:t.data.ka_data.text,callback:function(a){t.$set(t.data.ka_data,"text",a)},expression:"data.ka_data.text"}})],1)])])])])])])}),[function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron width_100"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("h2",{staticClass:"display-4"},[a("span",[t._v("Service global information")])]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Service global information.")])])])])},function(){var t=this,a=t._self._c;return a("thead",[a("tr",[a("th",[t._v("Image")]),t._v(" "),a("th",[t._v("|")]),t._v(" "),a("th",[t._v("Delite")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"width_100 jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Service english version")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Service english version for site localisation.")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"width_100 jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Service rusion version")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Service rusion version for site localisation.")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"width_100 jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Service georgian version")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Service georgian version for site localisation.")])])])}],!1,null,null,null).exports}}]);