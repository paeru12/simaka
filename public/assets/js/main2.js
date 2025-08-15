var a = document.getElementById("gam");

function readUrl(input) {
    if (input.files) {
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        reader.onload = (e) => {
            a.src = e.target.result;
        };
    }
}
const fileInput = document.querySelector('input[type="file"]');
const errorMessage = document.querySelector("#invalid-feedback");

function hapusGambar() {
    const imgElement = document.getElementById("gam");
    const blankSrc = "/assets/img/blank.jpg";
    imgElement.src = blankSrc;
    fileInput.value = ""; 
    fileInput.classList.remove("is-invalid");
    fileInput.classList.remove("is-valid");
}

fileInput.addEventListener("change", function () {
    const file = fileInput.files[0];

    const allowedExtensions = /(\.jpg|\.png|\.webp|\.jfif|\.jpeg)$/i;

    if (!allowedExtensions.test(file.name.toLowerCase())) {
        errorMessage.textContent =
            "Yang anda upload bukan gambar";
        fileInput.classList.add("is-invalid");
    } else {
        errorMessage.textContent = "";
        fileInput.classList.remove("is-invalid");
        fileInput.classList.add("is-valid");
    }
});