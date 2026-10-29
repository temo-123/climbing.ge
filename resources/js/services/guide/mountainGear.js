// Mountaineering grade → difficulty level (1–6) and the default gear list for
// it. Used by MountainSafety (grade in plain words) and, on routes whose
// "What You Need" text has no list of its own, by the gear checklist there.
import { FRENCH_ALPINE_GRADES, RUSSIAN_ALPINE_GRADES, getBothMountGrades } from '../../mixins/grade_chart_mixin.js'

// what everyone carries, then what each difficulty level adds
const BASE_GEAR = ['helmet', 'boots', 'layers', 'headlamp', 'first_aid', 'navigation', 'water_food', 'sun', 'phone'];
const LEVEL_GEAR = {
    1: ['crampons_axe'],
    2: ['crampons_axe', 'harness', 'rope', 'slings'],
    3: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws'],
    4: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws', 'rack', 'bivy'],
    5: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws', 'rack', 'bivy'],
    6: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws', 'rack', 'bivy'],
};
// French alpine grade → Russian category number (1–6)
const FRENCH_LEVEL = { F: 1, PD: 2, AD: 3, D: 4, TD: 5, ED: 6, ABO: 6 };

export function mountain_level(grade) {
    if (!grade) return null;
    const both = getBothMountGrades(grade);
    if (both.russian && RUSSIAN_ALPINE_GRADES.includes(both.russian)) return Number(both.russian.charAt(0));
    if (both.french && FRENCH_ALPINE_GRADES.includes(both.french)) {
        const base = both.french.replace(/[-+]|\d/g, '');
        return FRENCH_LEVEL[base] || null;
    }
    return null;
}

// i18n keys under guide.mountain.gear.*
export function default_gear(level) {
    return BASE_GEAR.concat(LEVEL_GEAR[level] || LEVEL_GEAR[1]);
}
