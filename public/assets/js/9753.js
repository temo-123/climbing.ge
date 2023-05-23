"use strict";(self.webpackChunkclimbing_loc=self.webpackChunkclimbing_loc||[]).push([[9753],{9753:(t,a,o)=>{o.r(a),o.d(a,{default:()=>s});var e=o(4443);const i={mixins:[e.z],props:["back_url"],data:function(){return{tab_num:1,categories:[],change_url_title:null,is_loading:!1,errors:[],editorConfig:{us_short_description_text_editor:e.z.get_small_editor_config(),us_text_editor_config:e.z.get_big_editor_config(),us_info_editor_config:e.z.get_big_editor_config(),ru_short_description_text_editor:e.z.get_small_editor_config(),ru_text_editor_config:e.z.get_big_editor_config(),ru_info_editor_config:e.z.get_big_editor_config(),ka_short_description_text_editor:e.z.get_small_editor_config(),ka_text_editor_config:e.z.get_big_editor_config(),ka_info_editor_config:e.z.get_big_editor_config()},data:[],myModal:!1}},mounted:function(){this.get_product_category_data(),this.get_product_editing_data()},methods:{showModal:function(){this.myModal=!this.myModal},change_url_title_in_global_product:function(){this.change_url_title?this.change_url_title=!1:confirm("Are you sure, you want change URL title? It vhile bad for SEO potimization")&&(this.change_url_title=!0)},get_product_editing_data:function(){var t=this;axios.get("/product/get_product_editing_data/"+this.$route.params.id).then((function(a){t.data=a.data})).catch((function(t){}))},edit_product:function(){var t=this;this.is_loading=!0,axios.post("/edit_product_data/"+this.$route.params.id,{data:this.data,change_url_title:this.change_url_title}).then((function(a){t.go_back(!0)})).catch((function(a){422==a.response.status?t.errors=a.response.data.validation:console.log(a)})).finally((function(){return t.is_loading=!1}))},get_product_category_data:function(){var t=this;axios.get("/product_category/").then((function(a){t.categories=a.data})).catch((function(t){return console.log(t)}))},go_back:function(){0==(arguments.length>0&&void 0!==arguments[0]&&arguments[0])?confirm("Are you sure, you want go back?")&&this.$router.go(-1):this.$router.go(-1)}}};const s=(0,o(1900).Z)(i,(function(){var t=this,a=t._self._c;return a("div",{staticClass:"tabs"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.is_loading,expression:"is_loading"}],staticClass:"row justify-content-center"},[a("div",{staticClass:"col-md-4"},[a("img",{attrs:{src:"../../../../../../public/images/site_img/loading.gif",alt:"loading"}})])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:!t.is_loading,expression:"!is_loading"}],staticClass:"row"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-primary",attrs:{type:"submit"},on:{click:function(a){return t.go_back()}}},[t._v("Beck")])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:!t.is_loading,expression:"!is_loading"}],staticClass:"row"},[a("div",{staticClass:"form-group"},[a("button",{staticClass:"btn btn-primary",attrs:{type:"submit"},on:{click:function(a){return t.edit_product()}}},[t._v("Save updating")])])]),t._v(" "),0!=t.errors.length?a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[t.errors.us_info_validation.title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                English title - "+t._s(t.errors.us_info_validation.title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.us_info_validation.short_description?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                English description - "+t._s(t.errors.us_info_validation.short_description[0])+"\n            ")]):t._e(),t._v(" "),t.errors.us_info_validation.text?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                English text - "+t._s(t.errors.us_info_validation.text[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ka_info_validation.title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Georgian title - "+t._s(t.errors.ka_info_validation.title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ka_info_validation.short_description?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Georgian description - "+t._s(t.errors.ka_info_validation.short_description[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ka_info_validation.text?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Georgian text - "+t._s(t.errors.ka_info_validation.text[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ru_info_validation.title?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Russion title - "+t._s(t.errors.ru_info_validation.title[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ru_info_validation.short_description?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Russiondescription - "+t._s(t.errors.ru_info_validation.short_description[0])+"\n            ")]):t._e(),t._v(" "),t.errors.ru_info_validation.text?a("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n                Russion text - "+t._s(t.errors.ru_info_validation.text[0])+"\n            ")]):t._e()])]):t._e(),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:!t.is_loading,expression:"!is_loading"}],staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"1"},domProps:{value:1,checked:t._q(t.tab_num,1)},on:{change:function(a){t.tab_num=1}}}),t._v(" "),a("label",{attrs:{for:"1"}},[t._v("Global info")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"2"},domProps:{value:2,checked:t._q(t.tab_num,2)},on:{change:function(a){t.tab_num=2}}}),t._v(" "),a("label",{attrs:{for:"2"}},[t._v("English text")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"3"},domProps:{value:3,checked:t._q(t.tab_num,3)},on:{change:function(a){t.tab_num=3}}}),t._v(" "),a("label",{attrs:{for:"3"}},[t._v("Russion text")])]),t._v(" "),a("div",{staticClass:"col"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.tab_num,expression:"tab_num"}],attrs:{type:"radio",id:"4"},domProps:{value:4,checked:t._q(t.tab_num,4)},on:{change:function(a){t.tab_num=4}}}),t._v(" "),a("label",{attrs:{for:"4"}},[t._v("Georgian text")])])])]),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{directives:[{name:"show",rawName:"v-show",value:1==t.tab_num,expression:"tab_num == 1"}],staticClass:"row width_100"},[t._m(0),t._v(" "),a("form",{ref:"myForm",staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",id:"global_form",enctyp:"multipart/form-data"}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Publish ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.global_product.published,expression:"data.global_product.published"}],staticClass:"form-control",attrs:{name:"published"},on:{change:function(a){var o=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data.global_product,"published",a.target.multiple?o:o[0])}}},[a("option",{attrs:{value:"0"}},[t._v("Not public")]),t._v(" "),a("option",{attrs:{value:"1"}},[t._v("Public")])])])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Sale type ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.global_product.sale_type,expression:"data.global_product.sale_type"}],staticClass:"form-control",attrs:{name:"published"},on:{change:function(a){var o=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data.global_product,"sale_type",a.target.multiple?o:o[0])}}},[a("option",{attrs:{value:"custom production"}},[t._v("Custom production")]),t._v(" "),a("option",{attrs:{value:"online order"}},[t._v("Online order")])])])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Mead in Georgia ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.global_product.mead_in_georgia,expression:"data.global_product.mead_in_georgia"}],attrs:{type:"checkbox",id:"scales",name:"scales"},domProps:{checked:Array.isArray(t.data.global_product.mead_in_georgia)?t._i(t.data.global_product.mead_in_georgia,null)>-1:t.data.global_product.mead_in_georgia},on:{change:function(a){var o=t.data.global_product.mead_in_georgia,e=a.target,i=!!e.checked;if(Array.isArray(o)){var s=t._i(o,null);e.checked?s<0&&t.$set(t.data.global_product,"mead_in_georgia",o.concat([null])):s>-1&&t.$set(t.data.global_product,"mead_in_georgia",o.slice(0,s).concat(o.slice(s+1)))}else t.$set(t.data.global_product,"mead_in_georgia",i)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" discount (%) ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.global_product.discount,expression:"data.global_product.discount"}],staticClass:"form-control",attrs:{type:"text",name:"discount"},domProps:{value:t.data.global_product.discount},on:{input:function(a){a.target.composing||t.$set(t.data.global_product,"discount",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" material")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.global_product.material,expression:"data.global_product.material"}],staticClass:"form-control",attrs:{type:"text",name:"material"},domProps:{value:t.data.global_product.material},on:{input:function(a){a.target.composing||t.$set(t.data.global_product,"material",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Category ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.global_product.category_id,expression:"data.global_product.category_id"}],staticClass:"form-control",attrs:{name:"category_id"},on:{change:function(a){var o=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data.global_product,"category_id",a.target.multiple?o:o[0])}}},[a("option",{attrs:{disabled:""}},[t._v("Select category")]),t._v(" "),t._l(t.categories,(function(o){return a("option",{key:o.id,domProps:{value:o.id}},[t._v(" "+t._s(o.us_name))])}))],2)])])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:2==t.tab_num,expression:"tab_num == 2"}],staticClass:"row"},[t._m(1),t._v(" "),a("form",{staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",enctyp:"multipart/form-data"}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.us_product.title,expression:"data.us_product.title"}],staticClass:"form-control",attrs:{type:"text",name:"name"},domProps:{value:t.data.us_product.title},on:{input:function(a){a.target.composing||t.$set(t.data.us_product,"title",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Change URL title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{attrs:{type:"checkbox",id:"scales",name:"scales"},on:{click:t.change_url_title_in_global_product}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Short description ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.editorConfig.us_short_description_text_editor},model:{value:t.data.us_product.short_description,callback:function(a){t.$set(t.data.us_product,"short_description",a)},expression:"data.us_product.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" text ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.editorConfig.us_text_editor_config},model:{value:t.data.us_product.text,callback:function(a){t.$set(t.data.us_product,"text",a)},expression:"data.us_product.text"}})],1)])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:3==t.tab_num,expression:"tab_num == 3"}],staticClass:"row"},[t._m(2),t._v(" "),a("form",{staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",enctyp:"multipart/form-data"}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.ru_product.title,expression:"data.ru_product.title"}],staticClass:"form-control",attrs:{type:"text",name:"title"},domProps:{value:t.data.ru_product.title},on:{input:function(a){a.target.composing||t.$set(t.data.ru_product,"title",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Short description ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.editorConfig.ru_short_description_text_editor},model:{value:t.data.ru_product.short_description,callback:function(a){t.$set(t.data.ru_product,"short_description",a)},expression:"data.ru_product.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" text ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.editorConfig.ru_text_editor_config},model:{value:t.data.ru_product.text,callback:function(a){t.$set(t.data.ru_product,"text",a)},expression:"data.ru_product.text"}})],1)])])]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:4==t.tab_num,expression:"tab_num == 4"}],staticClass:"row"},[t._m(3),t._v(" "),a("form",{staticClass:"width_100",staticStyle:{"margin-top":"5%"},attrs:{name:"contact-form",method:"POST",enctyp:"multipart/form-data"},on:{submit:function(a){return a.preventDefault(),t.add_ka_article.apply(null,arguments)}}},[a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Title ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.ka_product.title,expression:"data.ka_product.title"}],staticClass:"form-control",attrs:{type:"text",name:"value name"},domProps:{value:t.data.ka_product.title},on:{input:function(a){a.target.composing||t.$set(t.data.ka_product,"title",a.target.value)}}})])]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" Short description ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.editorConfig.ka_short_description_text_editor},model:{value:t.data.ka_product.short_description,callback:function(a){t.$set(t.data.ka_product,"short_description",a)},expression:"data.ka_product.short_description"}})],1)]),t._v(" "),a("div",{staticClass:"form-group clearfix"},[a("label",{staticClass:"col-xs-2 control-label",attrs:{for:"name"}},[t._v(" text ")]),t._v(" "),a("div",{staticClass:"col-xs-8"},[a("ckeditor",{attrs:{config:t.editorConfig.ka_text_editor_config},model:{value:t.data.ka_product.text,callback:function(a){t.$set(t.data.ka_product,"text",a)},expression:"data.ka_product.text"}})],1)])])])])])])}),[function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron width_100"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[a("h2",{staticClass:"display-4"},[a("span",[t._v("Region global information")])]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Region global information.")])])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"width_100 jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Product english version")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Article english version for site localisation.")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"width_100 jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Product rusion version")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Article rusion version for site localisation.")])])])},function(){var t=this,a=t._self._c;return a("div",{staticClass:"width_100 jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Product georgian version")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Article georgian version for site localisation.")])])])}],!1,null,null,null).exports}}]);