<template>
        <div class="form-group clearfix row">
          <label for="name" class='col-md-2 control-label'> Grade </label>
          <div class="col-md-5">
            <select class="form-control"  v-model="data.category" required v-if="category == 'outdoor'">
              <option value="" disabled>Please select route type</option>
              <option value="sport climbing">Sport Climbing</option>
              <option value="bouldering">Bouldering</option>
              <option value="tred">Tred Climbing</option>
              <option value="top">Top Rope</option>
            </select>

            <select class="form-control"  v-model="data.category" required v-else-if="category == 'ice'">
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
        props: {
            category_prop: {
                type: String,
            },
        },
        data: function () {
            return {
              sport_route_grade: grade.lead(),
              boulder_route_grade: grade.boulder(),

              ice_climbing_route_grade: grade.ice(),
              mix_climbing_route_grade: grade.mix_climbing(),
              dry_tooling_route_grade: grade.dry_tooling(),

              category: this.category_prop,

              data: {
                category: '',
                grade: '',
                or_grade: '',
              }
            }
        },
        watch: {
            category_prop: function(newVal, oldVal) { 
              this.category = this.category_prop
            },
            data: {
              handler(newVal) {
                this.$emit('update:data', newVal);
              },
              deep: true
            }
        },
        components: {
          // 
        },
        mounted() {
          // 
        },
        methods: {
          // 
        }
    }
</script>