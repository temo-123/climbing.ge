const LEAD_FR_TO_YDS = {
    "4":    "5.6",
    "5a":   "5.7",  "5a+": "5.7",
    "5b":   "5.8",  "5b+": "5.8",
    "5c":   "5.9",  "5c+": "5.9",
    "6a":   "5.10a",
    "6a+":  "5.10b",
    "6b":   "5.10c",
    "6b+":  "5.10d",
    "6c":   "5.11a",
    "6c+":  "5.11c",
    "7a":   "5.11d",
    "7a+":  "5.12a",
    "7b":   "5.12b",
    "7b+":  "5.12c",
    "7c":   "5.12d",
    "7c+":  "5.13a",
    "8a":   "5.13b",
    "8a+":  "5.13c",
    "8b":   "5.13d",
    "8b+":  "5.14a",
    "8c":   "5.14b",
    "8c+":  "5.14c",
    "9a":   "5.14d",
    "9a+":  "5.15a",
    "9b":   "5.15b",
    "9b+":  "5.15c",
    "9c":   "5.15d",
    "9c+":  "5.16a",
    "Project": "Project",
    "Open Project": "Open Project",
    "Close Project": "Close Project",
};

const LEAD_FR_TO_UIAA = {
    "4":    "IV",
    "5a":   "V+",  "5a+": "V+",
    "5b":   "VI-", "5b+": "VI-",
    "5c":   "VI",  "5c+": "VI",
    "6a":   "VI+",
    "6a+":  "VII-",
    "6b":   "VII",
    "6b+":  "VII+",
    "6c":   "VIII-",
    "6c+":  "VIII-",
    "7a":   "VIII",
    "7a+":  "VIII+",
    "7b":   "IX-",
    "7b+":  "IX-",
    "7c":   "IX",
    "7c+":  "IX+",
    "8a":   "X-",
    "8a+":  "X-",
    "8b":   "X",
    "8b+":  "X+",
    "8c":   "XI-",
    "8c+":  "XI",
    "9a":   "XI+",
    "9a+":  "XII-",
    "9b":   "XII",
    "9b+":  "XII+",
    "9c":   "XIII",
    "Project": "Project",
    "Open Project": "Open Project",
    "Close Project": "Close Project",
};

const BOULDER_V_TO_YDS = {
    "VB":  "4-",  "V0-": "4",  "V0":  "4+", "V0+": "5a",
    "V1":  "5b",  "V2":  "5c", "V3":  "6a", "V4":  "6b",
    "V5":  "6c+", "V6":  "7a", "V7":  "7a+","V8":  "7b",
    "V9":  "7c",  "V10": "7c+","V11": "8a", "V12": "8a+",
    "V13": "8b",  "V14": "8b+","V15": "8c", "V16": "8c+",
    "V17": "9a", 
    "Project": "Project",
    "Open Project": "Open Project",
    "Close Project": "Close Project",
};

const BOULDER_V_TO_UIAA = {
    "VB":  "4-",     "V0-": "VI+",     "V0":  "VII-",    "V0+": "VII",
    "V1":  "VII+",   "V2":  "VIII-",    "V3": "VIII-",   "V4":  "VIII",
    "V5":  "VIII+",    "V6":"VIII+",    "V7":  "IX-",     "V8":  "IX",
    "V9":  "IX+",    "V10": "IX+",     "V11": "X-",      "V12": "X",
    "V13": "X+",     "V14": "XI-",     "V15": "XI",      "V16": "XI+",
    "V17": "XII-",   
    "Project": "Project",
    "Open Project": "Open Project",
    "Close Project": "Close Project",
};

const ALPINE_RUSS_TO_EU = {
    "1A": "F",   "1B": "F+",  "2A": "PD",    "2B": "PD+",
    "3A": "AD",  "3B": "AD+", "4A": "D",     "4B": "D+",
    "5A": "TD",  "5B": "TD+/ED1", "6A": "ED2", "6B": "ED2+",
    "7A": "ED3",
};

function getGradeSetting() {
    return (localStorage.getItem("grade") || "").toLowerCase();
}

export default {
    methods: {
        lead(grade_fr) {
            const setting = getGradeSetting();
            if (setting === "yds") return LEAD_FR_TO_YDS[grade_fr] ?? "?";
            if (setting === "uiaa") return LEAD_FR_TO_UIAA[grade_fr] ?? "?";
            return "Error";
        },

        boulder(grade_fr) {
            const setting = getGradeSetting();
            if (setting === "yds") return BOULDER_V_TO_YDS[grade_fr] ?? "?";
            if (setting === "uiaa") return BOULDER_V_TO_UIAA[grade_fr] ?? "?";
            return "Error";
        },

        alpine(grade_russ) {
            const setting = (localStorage.getItem("EU") || localStorage.getItem("grade") || "").toLowerCase();
            if (setting === "yds" || setting === "eu") return ALPINE_RUSS_TO_EU[grade_russ] ?? "?";
            return "Error";
        },
    }
}
