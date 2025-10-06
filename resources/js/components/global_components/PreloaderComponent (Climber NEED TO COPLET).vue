<template>
  <transition name="fade">
    <div v-if="visible" id="page-preloader" class="preloader">
      <div class="loader">

        <svg width="25%" height="100%" viewBox="0 0 150 800" class="grips-svg">
          <circle cx="105" cy="25" r="8" fill="#ff0000"></circle>
          <circle cx="4" cy="50" r="8" fill="#ff0000"></circle>
          <circle cx="140" cy="80" r="8" fill="#ff0000"></circle>
          <circle cx="45" cy="75" r="8" fill="#00ff00"></circle>
          <circle cx="10" cy="125" r="8" fill="#00ff7f"></circle>
          <circle cx="5" cy="175" r="8" fill="#0000ff"></circle>
          <circle cx="85" cy="175" r="8" fill="#0000ff"></circle>
          <circle cx="120" cy="200" r="8" fill="#ffff00"></circle>
          <circle cx="70" cy="225" r="8" fill="#daa520"></circle>
          <circle cx="60" cy="250" r="8" fill="#ff00ff"></circle>
          <circle cx="50" cy="275" r="8" fill="#ff00ff"></circle>
          <circle cx="90" cy="300" r="8" fill="#00ffff"></circle>
          <circle cx="110" cy="325" r="8" fill="#dc143c"></circle>
          <circle cx="35" cy="350" r="8" fill="#ffff00"></circle>
          <circle cx="75" cy="375" r="8" fill="#ffff00"></circle>
          <circle cx="90" cy="400" r="8" fill="#800080"></circle>
          <circle cx="45" cy="425" r="8" fill="#32cd32"></circle>
          <circle cx="95" cy="475" r="8" fill="#00ffff"></circle>
          <circle cx="40" cy="500" r="8" fill="#00ff7f"></circle>
          <circle cx="95" cy="530" r="8" fill="#ffa500"></circle>
          <circle cx="75" cy="550" r="8" fill="#daa520"></circle>
          <circle cx="115" cy="575" r="8" fill="#ffa500"></circle>
          <circle cx="60" cy="600" r="8" fill="#dc143c"></circle>
          <circle cx="100" cy="625" r="8" fill="#ffd700"></circle>
          <circle cx="65" cy="675" r="8" fill="#800080"></circle>
          <circle cx="90" cy="700" r="8" fill="#1e90ff"></circle>
          <circle cx="55" cy="740" r="8" fill="#ff0000"></circle>
        </svg>


        <svg width="20%" height="100%" viewBox="0 0 150 800" class="climber-svg" :style="`transform: translate(${climberX}px, ${climberY}px) rotate(${bodyRotate}deg);`">
          <g class="climber">
            <circle cx="75" cy="40" r="10" fill="#ffa500"></circle>
            <rect x="69" y="50" width="12" height="30" fill="#0000ff"></rect>
            <rect x="69" y="50" width="12" height="25" fill="#fff" stroke="#000" stroke-width="1"></rect>
            <rect x="69" y="50" width="12" height="8" fill="#654321"></rect>
            <g :transform="`rotate(${leftHandRotate} 69.5 80)`">
              <rect :transform="`rotate(${leftShoulderRotate} 81.5 98)`" x="66" y="80" width="7" height="18" fill="#000080"></rect>
              <rect :transform="`rotate(${leftElbowRotate} 69.5 98)`" x="66" y="98" width="7" height="18" fill="#000080"></rect>
            </g>
            <g :transform="`rotate(${rightHandRotate} 81.5 80)`">
              <rect :transform="`rotate(${rightShoulderRotate} 81.5 98)`" x="78" y="80" width="7" height="18" fill="#000080"></rect>
              <rect :transform="`rotate(${rightElbowRotate} 81.5 98)`" x="78" y="98" width="7" height="18" fill="#000080"></rect>
            </g>
            <g :transform="`rotate(${leftFeetRotate} 84 55)`">
              <rect :transform="`rotate(${leftKneeRotate} 84 70)`" x="81" y="55" width="6" height="15" fill="#ffa500"></rect>
              <rect :transform="`rotate(${leftShinRotate} 84 70)`" x="81" y="67.5" width="6" height="15" fill="#ffa500"></rect>
            </g>
            <g :transform="`rotate(${rightFeetRotate} 66 55)`">
              <rect :transform="`rotate(${rightKneeRotate} 66 70)`" x="63" y="55" width="6" height="15" fill="#ffa500"></rect>
              <rect :transform="`rotate(${rightShinRotate} 66 70)`" x="63" y="70" width="6" height="15" fill="#ffa500"></rect>
            </g>
          </g>
        </svg>
      
        </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Preloader',
  data() {
    return {
      visible: true,
      climberX: 0,
      climberY: 600,
      bodyRotate: 0,
      leftShoulderRotate: 0,
      rightShoulderRotate: 0,
      leftElbowRotate: 0,
      rightElbowRotate: 0,
      leftHipRotate: 0,
      rightHipRotate: 0,
      leftKneeRotate: 0,
      rightKneeRotate: 0,
      leftHandRotate: 0,
      rightHandRotate: 0,
      leftFeetRotate: 0,
      rightFeetRotate: 0,
      leftShinRotate: 0,
      rightShinRotate: 0
    }
  },
  mounted() {
    this.animateClimb();
    // setTimeout(() => {
      // this.visible = false;
    // }, 5000);
  },
  methods: {
    animateClimb() {
      // New animation sequence matching hold positions
      const steps = [
        // Start at bottom
        { time: 0, climberX: 0, climberY: 600, bodyRotate: 0, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0, leftHandRotate: 0, rightHandRotate: 0, leftFeetRotate: 0, rightFeetRotate: 0, leftShinRotate: 0, rightShinRotate: 0 },
        // Raise left arm, position on hold x=60
        // { time: 200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 60 - 69.5 },
        // // Bend right leg at ankle, position on hold x=100
        // { time: 400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 100 - 66 },
        // // Raise right arm, position on hold x=65
        // { time: 600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 65 - 81.5 },
        // // Bend left leg at ankle, position on hold x=90
        // { time: 800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 90 - 84 },
        // // Lift to y=550, position for next left hand on x=75
        // { time: 1000, climberY: 550, climberX: 75 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=75
        // { time: 1200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 75 - 69.5 },
        // // Bend right leg, on hold x=115
        // { time: 1400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 115 - 66 },
        // // Raise right arm, on hold x=60
        // { time: 1600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 60 - 81.5 },
        // // Bend left leg, on hold x=100
        // { time: 1800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 100 - 84 },
        // // Lift to y=500, position for next left hand on x=40
        // { time: 2000, climberY: 500, climberX: 40 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=40
        // { time: 2200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 40 - 69.5 },
        // // Bend right leg, on hold x=95
        // { time: 2400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 95 - 66 },
        // // Raise right arm, on hold x=75
        // { time: 2600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 75 - 81.5 },
        // // Bend left leg, on hold x=115
        // { time: 2800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 115 - 84 },
        // // Lift to y=450, position for next left hand on x=75
        // { time: 3000, climberY: 450, climberX: 75 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=75
        // { time: 3200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 75 - 69.5 },
        // // Bend right leg, on hold x=90
        // { time: 3400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 90 - 66 },
        // // Raise right arm, on hold x=45
        // { time: 3600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 45 - 81.5 },
        // // Bend left leg, on hold x=95
        // { time: 3800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 95 - 84 },
        // // Lift to y=400, position for next left hand on x=90
        // { time: 4000, climberY: 400, climberX: 90 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=90
        // { time: 4200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 90 - 69.5 },
        // // Bend right leg, on hold x=45
        // { time: 4400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 45 - 66 },
        // // Raise right arm, on hold x=75
        // { time: 4600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 75 - 81.5 },
        // // Bend left leg, on hold x=90
        // { time: 4800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 90 - 84 },
        // // Lift to y=350, position for next left hand on x=35
        // { time: 5000, climberY: 350, climberX: 35 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=35
        // { time: 5200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 35 - 69.5 },
        // // Bend right leg, on hold x=75
        // { time: 5400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 75 - 66 },
        // // Raise right arm, on hold x=90
        // { time: 5600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 90 - 81.5 },
        // // Bend left leg, on hold x=45
        // { time: 5800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 45 - 84 },
        // // Lift to y=300, position for next left hand on x=90
        // { time: 6000, climberY: 300, climberX: 90 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=90
        // { time: 6200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 90 - 69.5 },
        // // Bend right leg, on hold x=50
        // { time: 6400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 50 - 66 },
        // // Raise right arm, on hold x=60
        // { time: 6600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 60 - 81.5 },
        // // Bend left leg, on hold x=70
        // { time: 6800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 70 - 84 },
        // // Lift to y=250, position for next left hand on x=70
        // { time: 7000, climberY: 250, climberX: 70 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=70
        // { time: 7200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 70 - 69.5 },
        // // Bend right leg, on hold x=60
        // { time: 7400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 60 - 66 },
        // // Raise right arm, on hold x=85
        // { time: 7600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 85 - 81.5 },
        // // Bend left leg, on hold x=120
        // { time: 7800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 120 - 84 },
        // // Lift to y=200, position for next left hand on x=120
        // { time: 8000, climberY: 200, climberX: 120 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=120
        // { time: 8200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 120 - 69.5 },
        // // Bend right leg, on hold x=70
        // { time: 8400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 70 - 66 },
        // // Raise right arm, on hold x=10
        // { time: 8600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 10 - 81.5 },
        // // Bend left leg, on hold x=5
        // { time: 8800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 5 - 84 },
        // // Lift to y=150, position for next left hand on x=10
        // { time: 9000, climberY: 150, climberX: 10 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=10
        // { time: 9200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 10 - 69.5 },
        // // Bend right leg, on hold x=5
        // { time: 9400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 5 - 66 },
        // // Raise right arm, on hold x=45
        // { time: 9600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 45 - 81.5 },
        // // Bend left leg, on hold x=140
        // { time: 9800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 140 - 84 },
        // // Lift to y=100, position for next left hand on x=45
        // { time: 10000, climberY: 100, climberX: 45 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=45
        // { time: 10200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 45 - 69.5 },
        // // Bend right leg, on hold x=140
        // { time: 10400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 140 - 66 },
        // // Raise right arm, on hold x=105
        // { time: 10600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 105 - 81.5 },
        // // Bend left leg, on hold x=4
        // { time: 10800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 4 - 84 },
        // // Lift to y=50, position for next left hand on x=105
        // { time: 11000, climberY: 50, climberX: 105 - 69.5, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 },
        // // Raise left arm, on hold x=105
        // { time: 11200, leftShoulderRotate: 80, leftElbowRotate: -80, climberX: 105 - 69.5 },
        // // Bend right leg, on hold x=4
        // { time: 11400, rightHipRotate: 30, rightKneeRotate: -30, climberX: 4 - 66 },
        // // Raise right arm, on hold x=140
        // { time: 11600, rightShoulderRotate: 80, rightElbowRotate: -80, climberX: 140 - 81.5 },
        // // Bend left leg, on hold x=45
        // { time: 11800, leftHipRotate: 30, leftKneeRotate: -30, climberX: 45 - 84 },
        // // Lift to y=0
        // { time: 12000, climberY: 0, leftShoulderRotate: 0, leftElbowRotate: 0, rightShoulderRotate: 0, rightElbowRotate: 0, leftHipRotate: 0, rightHipRotate: 0, leftKneeRotate: 0, rightKneeRotate: 0 }
      ];

      steps.forEach(step => {
        setTimeout(() => {
          Object.keys(step).forEach(key => {
            if (key !== 'time') {
              this[key] = step[key];
            }
          });
        }, step.time);
      });
    }
  }
}
</script>

<style>
.preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #afafaf8c;
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
}

.loader {
  position: relative;
  width: 100vw;
  height: 100vh;
}

.grips-svg {
  position: absolute;
  /* //top: 0; */
  bottom: 0;
  left: 35%;
}

.climber-svg {
  position: absolute;
  top: 0;
  left: 40%;
  transition: transform 0.3s ease;
}

.climber {
  transform-box: fill-box;
  transform-origin: center bottom;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
