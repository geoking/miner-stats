function showHide() {
    var x = document.getElementById("overallStatsDiv");
    var y = document.getElementById("averageStatsDiv");
    var z = document.getElementById("currentStatsDiv");
    var b = document.getElementById("showHideButton");
    if (b.innerText === "Show overall stats") {
        x.style.display = "block";
        y.style.display = "none";
        z.style.display = "none";
        b.innerText = "Show average rates";
    } else if (b.innerText === "Show average rates") {
        x.style.display = "none";
        y.style.display = "block";
        z.style.display = "none";
        b.innerText = "Show current rates";
    } else if (b.innerText === "Show current rates") {
        x.style.display = "none";
        y.style.display = "none";
        z.style.display = "block";
        b.innerText = "Show overall stats";
    }
}

function fiatUsdSwitch() {
    var x = document.getElementById("fiatDiv");
    var y = document.getElementById("usdDiv");
    var b = document.getElementById("fiatUsdButton");
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
        b.innerHTML = '£<span style="font-size: 10px;">/$';
    } else {
        x.style.display = "none";
        y.style.display = "block";
        b.innerHTML = '$<span style="font-size: 10px;">/£';
    }
}

window.onload = document.getElementById("showHideButton").style.display = "block";
window.onload = document.getElementById("fiatUsdButton").style.display = "block";