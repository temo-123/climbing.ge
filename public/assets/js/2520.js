"use strict";(self.webpackChunkclimbing_loc=self.webpackChunkclimbing_loc||[]).push([[2520],{2520:(t,a,e)=>{e.r(a),e.d(a,{default:()=>r});var o=e(6247),i=e(2586);const s={components:{StackModal:e.n(i)(),SlickItem:o.SlickItem,SlickList:o.SlickList},props:["global_data_prop","category_prop","title_prop","description_prop"],data:function(){return{data:this.global_data_prop,region_id:"select_region",mount_id:"select_mount",category:this.category_prop,error:[],regions:[],mount_masive:[]}},watch:{global_data_prop:function(t,a){this.data=this.global_data_prop},category_prop:function(t,a){this.category=this.category_prop,"outdoor"==this.category&&this.get_regions(),"mount_route"==this.category&&this.get_mount_massive_data()}},mounted:function(){"outdoor"==this.category&&this.get_regions(),"mount_route"==this.category&&this.get_mount_massive_data(),this.$emit("global_form_data",this.data)},methods:{get_mount_massive_data:function(){var t=this;axios.get("/mount/mount/").then((function(a){t.mount_masive=a.data})).catch((function(t){return console.log(t)}))},get_regions:function(){var t=this;axios.get("/outdoor/region/").then((function(a){t.regions=a.data})).catch((function(t){return console.log(t)}))}}};const r=(0,e(1900).Z)(s,(function(){var t=this,a=t._self._c;return a("div",{staticClass:"colm-md-12"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-12"},[t._m(0),t._v(" "),a("form",{staticStyle:{"margin-top":"5%"},on:{submit:function(a){return a.preventDefault(),t.edit_article.apply(null,arguments)}}},[a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"name"}},[t._v(" Publish ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.published,expression:"data.published"}],staticClass:"form-control",attrs:{name:"published"},on:{change:function(a){var e=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data,"published",a.target.multiple?e:e[0])}}},[a("option",{attrs:{value:"0"}},[t._v("Not public")]),t._v(" "),a("option",{attrs:{value:"1"}},[t._v("Public")])])])]),t._v(" "),"outdoor"==this.category?a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"region"}},[t._v(" Regions ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.region_id,expression:"data.region_id"}],staticClass:"form-control",attrs:{name:"region"},on:{change:function(a){var e=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data,"region_id",a.target.multiple?e:e[0])}}},[a("option",{attrs:{disabled:""},domProps:{value:"select_region"}},[t._v("Select region")]),t._v(" "),a("option",{staticStyle:{color:"red"},domProps:{value:null}},[t._v("Whithout Region")]),t._v(" "),t._l(t.regions,(function(e){return a("option",{key:e.id,domProps:{value:e.id}},[t._v(t._s(e.us_name))])}))],2)])]):t._e(),t._v(" "),"mount_route"==this.category?a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"name"}},[t._v(" Mountain ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("select",{directives:[{name:"model",rawName:"v-model",value:t.data.mount_id,expression:"data.mount_id"}],staticClass:"form-control",attrs:{name:"mount_id"},on:{change:function(a){var e=Array.prototype.filter.call(a.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.data,"mount_id",a.target.multiple?e:e[0])}}},[a("option",{attrs:{disabled:""},domProps:{value:"select_mount"}},[t._v("Select mount")]),t._v(" "),a("option",{staticStyle:{color:"red"},domProps:{value:null}},[t._v("Whithout Mount")]),t._v(" "),t._l(t.mount_masive,(function(e){return a("option",{key:e.global_mount.id,domProps:{value:e.global_mount.id}},[t._v(t._s(e.global_mount.name))])}))],2)])]):t._e(),t._v(" "),"mount_route"!=this.category?a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"name"}},[t._v(" Map ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.map,expression:"data.map"}],staticClass:"form-control",attrs:{type:"text",name:"map"},domProps:{value:t.data.map},on:{input:function(a){a.target.composing||t.$set(t.data,"map",a.target.value)}}})])]):t._e(),t._v(" "),"outdoor"==this.category||"ice"==this.category?a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"name"}},[t._v(" Weather ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.weather,expression:"data.weather"}],staticClass:"form-control",attrs:{type:"text",name:"weather"},domProps:{value:t.data.weather},on:{input:function(a){a.target.composing||t.$set(t.data,"weather",a.target.value)}}})])]):t._e(),t._v(" "),a("hr"),t._v(" "),"indoor"==this.category?a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"name"}},[t._v(" Minimal price ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.price_from,expression:"data.price_from"}],staticClass:"form-control",attrs:{type:"text",name:"price_from"},domProps:{value:t.data.price_from},on:{input:function(a){a.target.composing||t.$set(t.data,"price_from",a.target.value)}}})])]):t._e(),t._v(" "),"indoor"==this.category?a("div",{staticClass:"form-group clearfix row"},[a("label",{staticClass:"col-md-2 control-label",attrs:{for:"name"}},[t._v(" Working time ")]),t._v(" "),a("div",{staticClass:"col-md-10"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-md-6"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.open_time,expression:"data.open_time"}],staticClass:"form-control",attrs:{type:"time",name:"open_time",placeholder:"Start data/time"},domProps:{value:t.data.open_time},on:{input:function(a){a.target.composing||t.$set(t.data,"open_time",a.target.value)}}})]),t._v(" "),a("div",{staticClass:"col-md-6"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.data.closed_time,expression:"data.closed_time"}],staticClass:"form-control",attrs:{type:"time",name:"closed_time",placeholder:"End data/time"},domProps:{value:t.data.closed_time},on:{input:function(a){a.target.composing||t.$set(t.data,"closed_time",a.target.value)}}})])])])]):t._e()])])])])}),[function(){var t=this,a=t._self._c;return a("div",{staticClass:"jumbotron jumbotron-fluid"},[a("div",{staticClass:"container"},[a("h2",{staticClass:"display-4"},[t._v("Edit article global information")]),t._v(" "),a("p",{staticClass:"lead"},[t._v("Edit article global information.")])])])}],!1,null,null,null).exports}}]);