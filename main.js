var rows = document.querySelectorAll(".row")
console.log(rows);


for (var i = 0; i < rows.length; i++) {
    rows[i].addEventListener("click", function(e) {
        document.getElementById("send").click()
    })
    console.log(rows[i]);
}