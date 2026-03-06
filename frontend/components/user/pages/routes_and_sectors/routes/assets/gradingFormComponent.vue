<template>
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Grade </label>
          <div class="col-md-5">
            <select class="form-control"  v-model="data.category" required v-if="category_type == 'outdoor'">
              <option value="" disabled>Please select route type</option>
              <option value="sport climbing">Sport Climbing</option>
              <option value="bouldering">Bouldering</option>
              <option value="tred">Tred Climbing</option>
              <option value="top">Top Rope</option>
            </select>

            <select class="form-control"  v-model="data.category" required v-else-if="category_type == 'ice'">
              <option value="" disabled>Please select route type</option>
              <option value="ice climbing">Ice Climbing</option>
              <option value="dry tooling">Dry Tooling</option> 
              <option value="mix climbing">Mix Climbing</option>
            </select>
          </div>

          <div class="col-md-5" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'sport climbing' || data.category == 'top'|| data.category == 'tred'" v-model="data.or_grade">
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="sport in sport_route_grade" :key="sport" v-bind:value="sport" :selected="true" >{{ sport }}</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-5" v-if="data.category != '' && data.category == 'bouldering'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'bouldering'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="boulder in boulder_route_grade" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'bouldering'" v-model="data.or_grade">
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="boulder in boulder_route_grade" :key="boulder" v-bind:value="boulder" :selected="true" >{{ boulder }}</option>
                </select>
              </div>
            </div>
          </div>
          
          <div class="col-md-5" v-if="data.category != '' && data.category == 'mix climbing'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'mix climbing'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="ice_climbing in ice_climbing_route_grade" :key="ice_climbing" v-bind:value="ice_climbing" :selected="true" >{{ ice_climbing }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'mix climbing'" v-model="data.or_grade">
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="ice_climbing in ice_climbing_route_grade" :key="ice_climbing" v-bind:value="ice_climbing" :selected="true" >{{ ice_climbing }}</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-5" v-if="data.category != '' && data.category == 'ice climbing'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'ice climbing'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="ice_climbing in ice_climbing_route_grade" :key="ice_climbing" v-bind:value="ice_climbing" :selected="true" >{{ ice_climbing }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'ice climbing'" v-model="data.or_grade">
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="ice_climbing in ice_climbing_route_grade" :key="ice_climbing" v-bind:value="ice_climbing" :selected="true" >{{ ice_climbing }}</option>
                </select>
              </div>
            </div>
          </div>
        
          <div class="col-md-5" v-if="data.category != '' && data.category == 'dry tooling'">
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'dry tooling'" v-model="data.grade" required>
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="dry_toolung in dry_tooling_route_grade" :key="dry_toolung" v-bind:value="dry_toolung" :selected="true" >{{ dry_toolung }}</option>
                </select>
              </div>
              <div class="col-md-6">
                <select class="form-control" v-if="data.category != '' && data.category == 'dry tooling'" v-model="data.or_grade">
                  <option value=""> No grade </option>
                  <option value="Project">Project</option>
                  <option v-for="dry_toolung in dry_tooling_route_grade" :key="dry_toolung" v-bind:value="dry_toolung" :selected="true" >{{ dry_toolung }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
</template>

<script>
    import { grade } from '../../../../../../mixins/grade_mixin.js'

    export default {
        mixins: [
            grade
        ],
        components: {
            // draggable_category_component
        },
        props: {
            category_prop: {
                type: String,
                default: 'outdoor'
            },
            category_type_prop: {
                type: String,
                default: 'outdoor'
            },
            grade_prop: {
                type: String,
                default: ''
            },
            or_grade_prop: {
                type: String,
                default: ''
            },
        },
        data: function () {
            return {
              sport_route_grade: grade.lead(),
              boulder_route_grade: grade.boulder(),

              ice_climbing_route_grade: grade.ice(),
              mix_climbing_route_grade: grade.mix_climbing(),
              dry_tooling_route_grade: grade.dry_tooling(),

              category_type: this.category_type_prop,
              category: this.category_prop,
              
              show_draggable: true, // Enable draggable by default

              data: {
                category: this.category_prop,
                grade: this.grade_prop,
                or_grade: this.or_grade_prop,
              }
            }
        },
        watch: {
            category_prop: function(newVal) { 
              this.category = newVal;
              this.data.category = newVal;
            },
            category_type_prop: function(newVal) {
              this.category_type = newVal;
            },
            grade_prop: function(newVal) {
                // Always update, even if newVal is empty string or different from current
                this.data.grade = newVal !== undefined ? newVal : '';
            },
            or_grade_prop: function(newVal) {
                // Always update, even if newVal is empty string or different from current
                this.data.or_grade = newVal !== undefined ? newVal : '';
            },
            data: {
              handler(newVal) {
                this.$emit('update:data', newVal);
              },
              deep: true
            }
        },
        mounted() {
            // Initialize category_type from prop if provided, otherwise default to 'outdoor'
            if (this.category_type_prop !== undefined && this.category_type_prop !== null && this.category_type_prop !== '') {
                this.category_type = this.category_type_prop;
            }
            
            // Initialize category from prop
            if (this.category_prop !== undefined && this.category_prop !== null && this.category_prop !== '') {
                this.category = this.category_prop;
                this.data.category = this.category_prop;
            }
            
            // Initialize grade values from props if provided
            if (this.grade_prop !== undefined && this.grade_prop !== null) {
                this.data.grade = this.grade_prop;
            }
            if (this.or_grade_prop !== undefined && this.or_grade_prop !== null) {
                this.data.or_grade = this.or_grade_prop;
            }
        },
        methods: {
          onCategorySelected(categoryValue) {
            console.log('Category selected:', categoryValue);
            this.data.category = categoryValue;
          },
          // toggleDraggable() {
          //   this.show_draggable = !this.show_draggable;
          // }
        }
    }
</script>
