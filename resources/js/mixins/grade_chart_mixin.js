export const grade_chart = {
    lead(grade_fr) {
        var grad = "";
        if (localStorage.getItem("grade") == "yds" || localStorage.getItem("grade") == "YDS") {
            if (grade_fr == "4") grad = "5.6";
            else if (grade_fr == "5a" || grade_fr == "5a+") grad = "5.7";
            else if (grade_fr == "5b" || grade_fr == "5b+") grad = "5.8";
            else if (grade_fr == "5c" || grade_fr == "5c+") grad = "5.9";
            else if (grade_fr == "6a") grad = "5.10a";
            else if (grade_fr == "6a+") grad = "5.10b";
            else if (grade_fr == "6b") grad = "5.10c";
            else if (grade_fr == "6b+") grad = "5.10d";
            else if (grade_fr == "6c") grad = "5.11a/5.11b";
            else if (grade_fr == "6c+") grad = "5.11c";
            else if (grade_fr == "7a") grad = "5.11d";
            else if (grade_fr == "7a+") grad = "5.12a";
            else if (grade_fr == "7b") grad = "5.12b";
            else if (grade_fr == "7b+") grad = "5.12c";
            else if (grade_fr == "7c") grad = "5.12d";
            else if (grade_fr == "7c+") grad = "5.13a";
            else if (grade_fr == "8a") grad = "5.13b";
            else if (grade_fr == "8a+") grad = "5.13c";
            else if (grade_fr == "8b") grad = "5.13d";
            else if (grade_fr == "8b+") grad = "5.14a";
            else if (grade_fr == "8c") grad = "5.14b";
            else if (grade_fr == "8c+") grad = "5.14c";
            else if (grade_fr == "9a") grad = "5.14d";
            else if (grade_fr == "9a+") grad = "5.15a";
            else if (grade_fr == "9b") grad = "5.15b";
            else if (grade_fr == "9b+") grad = "5.15c";
            else if (grade_fr == "9c") grad = "5.15d";
            else if (grade_fr == "9c+") grad = "5.16a";
            else if (grade_fr == "Project") grad = "Project";
            else grad = "?";
        } else if (localStorage.getItem("grade") == "UIAA" || localStorage.getItem("grade") == "uiaa") {
            // console.log(localStorage.getItem("grade"))
            if (grade_fr == "4") grad = "IV";
            else if (grade_fr == "5a" || grade_fr == "5a+") grad = "V+";
            else if (grade_fr == "5b" || grade_fr == "5b+") grad = "VI-";
            else if (grade_fr == "5c" || grade_fr == "5c+") grad = "VI";
            else if (grade_fr == "6a") grad = "VI+";
            else if (grade_fr == "6a+") grad = "VII-";
            else if (grade_fr == "6b") grad = "VII";
            else if (grade_fr == "6b+") grad = "VII+";
            else if (grade_fr == "6c") grad = "VII+/VIII-";
            else if (grade_fr == "6c+") grad = "VIII-";
            else if (grade_fr == "7a") grad = "VIII";
            else if (grade_fr == "7a+") grad = "VIII+";
            else if (grade_fr == "7b") grad = "VIII+/IX-";
            else if (grade_fr == "7b+") grad = "IX-";
            else if (grade_fr == "7c") grad = "IX";
            else if (grade_fr == "7c+") grad = "IX+";
            else if (grade_fr == "8a") grad = "IX+/X-";
            else if (grade_fr == "8a+") grad = "X-";
            else if (grade_fr == "8b") grad = "X";
            else if (grade_fr == "8b+") grad = "X+";
            else if (grade_fr == "8c") grad = "XI-";
            else if (grade_fr == "8c+") grad = "XI";
            else if (grade_fr == "9a") grad = "XI+";
            else if (grade_fr == "9a+") grad = "XII-";
            else if (grade_fr == "9b") grad = "XII";
            else if (grade_fr == "9b+") grad = "XII+";
            else if (grade_fr == "9c") grad = "XIII";
            else if (grade_fr == "Project") grad = "Project";
            else grad = "?";
        } else {
            grad = "Error";
        }
        return grad;
    },
    boulder(grade_fr) {
        var grade = "";
        if (localStorage.getItem("grade") == "yds" || localStorage.getItem("grade") == "YDS") {
            if (grade_fr == "VB") grade = "4-";
            else if (grade_fr == "V0-") grade = "4";
            else if (grade_fr == "V0") grade = "4+";
            else if (grade_fr == "V0+") grade = "5a";
            else if (grade_fr == "V1") grade = "5b";
            else if (grade_fr == "V2") grade = "5c";
            else if (grade_fr == "V3") grade = "6a";
            else if (grade_fr == "V4") grade = "6b";
            else if (grade_fr == "V5") grade = "6c+";
            else if (grade_fr == "V6") grade = "7a";
            else if (grade_fr == "V7") grade = "7a+";
            else if (grade_fr == "V8") grade = "7b";
            else if (grade_fr == "V9") grade = "7c";
            else if (grade_fr == "V10") grade = "7c+";
            else if (grade_fr == "V11") grade = "8a";
            else if (grade_fr == "V12") grade = "8a+";
            else if (grade_fr == "V13") grade = "8b";
            else if (grade_fr == "V14") grade = "8b+";
            else if (grade_fr == "V15") grade = "8c";
            else if (grade_fr == "V16") grade = "8c+";
            else if (grade_fr == "V17") grade = "9a";
            else if (grade_fr == "Project") grad = "Project";
            else grade = "?";
        } else if (localStorage.getItem("grade") == "UIAA" || localStorage.getItem("grade") == "uiaa") {
            if (grade_fr == "VB") grade = "4-";
            else if (grade_fr == "V0-") grade = "VI+";
            else if (grade_fr == "V0") grade = "VII-";
            else if (grade_fr == "V0+") grade = "VII";
            else if (grade_fr == "V1") grade = "VII+";
            else if (grade_fr == "V2") grade = "VII+/VII-";
            else if (grade_fr == "V3") grade = "VIII-";
            else if (grade_fr == "V4") grade = "VIII";
            else if (grade_fr == "V5") grade = "VIII/VIII+";
            else if (grade_fr == "V6") grade = "VIII+";
            else if (grade_fr == "V7") grade = "IX-";
            else if (grade_fr == "V8") grade = "IX";
            else if (grade_fr == "V9") grade = "IX/IX+";
            else if (grade_fr == "V10") grade = "IX+";
            else if (grade_fr == "V11") grade = "X-";
            else if (grade_fr == "V12") grade = "X";
            else if (grade_fr == "V13") grade = "X+";
            else if (grade_fr == "V14") grade = "XI-";
            else if (grade_fr == "V15") grade = "XI";
            else if (grade_fr == "V16") grade = "XI+";
            else if (grade_fr == "V17") grade = "XII-";
            else if (grade_fr == "Project") grad = "Project";
            else grade = "?";
        } else {
            grad = "Error";
        }
        return grade;
    }
}