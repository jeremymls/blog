const thumbnail = document.querySelector(".thumbnailOverview");
const imgSize = document.getElementById("imgSize");

document.querySelector(".picture").onchange = function () {
    imgSize.innerHTML = "Taille du fichier: " + formatBytes(this.files[0].size);
    if (this.files[0].size > 512000) {
        alert("Le fichier est trop gros");
        this.value = "";
        thumbnail.src = "/assets/img/profile.png";
    }
    var reader = new FileReader();
    reader.onload = function (e) {
        // get loaded data and render thumbnail.
        thumbnail.src = e.target.result;
    };
    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
}

function formatBytes(size, decimals = 2) {
    if (size === 0) return '0 bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(size) / Math.log(k));
    return parseFloat((size / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}