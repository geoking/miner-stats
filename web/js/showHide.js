function showHide() {
    var x = document.getElementById("showDiv");
    var y = document.getElementById("hideDiv");
    var b = document.getElementById("showHideButton");
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
        b.innerText = "Show average rates";
    } else {
        x.style.display = "none";
        y.style.display = "block";
        b.innerText = "Show overall stats";
    }
}

window.onload = document.getElementById("showHideButton").style.display = "block";