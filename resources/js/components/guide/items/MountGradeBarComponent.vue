<template>
    <div class="mgb-root" v-if="gradeIndex >= 0">

        <div class="mgb-top">
            <span class="mgb-title">{{ $t('guide.article.mount_route.grade') }}</span>
            <span class="mgb-badge" :style="{ background: markerColor }">{{ grade }}</span>
        </div>

        <div class="mgb-bar-wrap">
            <div class="mgb-bar-bg"></div>
            <div class="mgb-bar-fill" :style="{ width: markerPercent + '%' }"></div>
            <div class="mgb-thumb" :style="{ left: markerPercent + '%', background: markerColor }"></div>
        </div>

        <div class="mgb-bottom">
            <span class="mgb-easy">🟢 {{ $t('guide.article.mount_route.easy') }}</span>
            <span class="mgb-hard">🔴 {{ $t('guide.article.mount_route.hard') }}</span>
        </div>

    </div>
</template>

<script>
const FRENCH = [
    'F', 'PD-', 'PD', 'PD+', 'AD-', 'AD', 'AD+',
    'D-', 'D', 'D+', 'TD-', 'TD', 'TD+',
    'ED1', 'ED2', 'ED3', 'ED4', 'ABO',
]

const RUSSIAN = [
    '1А', '1Б', '2А', '2Б', '3А', '3Б',
    '4А', '4Б', '5А', '5Б', '6А', '6Б',
]

export default {
    name: 'MountGradeBarComponent',
    props: {
        grade: { type: String, default: null },
    },
    computed: {
        displayGrades() {
            if (FRENCH.includes(this.grade)) return FRENCH
            if (RUSSIAN.includes(this.grade)) return RUSSIAN
            return FRENCH
        },
        gradeIndex() {
            return this.displayGrades.indexOf(this.grade)
        },
        markerPercent() {
            const n = this.displayGrades.length - 1
            return Math.round((this.gradeIndex / n) * 100)
        },
        markerColor() {
            const t = this.gradeIndex / (this.displayGrades.length - 1)
            if (t < 0.4) return '#2ecc71'
            if (t < 0.65) return '#f39c12'
            return '#e74c3c'
        },
    },
}
</script>

<style scoped>
.mgb-root {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 14px 18px 12px;
    margin: 16px 0;
    user-select: none;
    /* box-shadow: 0 1px 4px rgba(0,0,0,0.07); */
}

.mgb-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.mgb-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #868e96;
}

.mgb-badge {
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    padding: 3px 13px;
    border-radius: 20px;
    letter-spacing: 0.05em;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.mgb-bar-wrap {
    position: relative;
    height: 10px;
    margin: 0 6px;
}

.mgb-bar-bg {
    position: absolute;
    inset: 0;
    border-radius: 5px;
    background: linear-gradient(to right, #2ecc71 0%, #f39c12 55%, #e74c3c 100%);
    opacity: 0.2;
}

.mgb-bar-fill {
    position: absolute;
    top: 0; bottom: 0; left: 0;
    border-radius: 5px;
    background: linear-gradient(to right, #2ecc71 0%, #f39c12 55%, #e74c3c 100%);
    transition: width 0.5s cubic-bezier(.4,0,.2,1);
}

.mgb-thumb {
    position: absolute;
    top: 50%;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.22);
    transition: left 0.5s cubic-bezier(.4,0,.2,1), background 0.3s;
    z-index: 1;
}

.mgb-bottom {
    display: flex;
    justify-content: space-between;
    margin-top: 9px;
    font-size: 11px;
    font-weight: 500;
    color: #868e96;
}
</style>
