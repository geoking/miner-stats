function fiatUsdSwitch() {
    var x = document.getElementById("fiatDiv");
    var y = document.getElementById("usdDiv");
    var b = document.getElementById("fiatUsdButton");
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
        b.innerText = "$";
    } else {
        x.style.display = "none";
        y.style.display = "block";
        b.innerText = "£";
    }
}

window.onload = document.getElementById("fiatUsdButton").style.display = "block";