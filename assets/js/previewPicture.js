//***************************************Upload IMAGE ******************* */
// const file_upload_input = document.querySelector("#file_upload_input");
const file_upload_input = document.querySelector("#profil_pictureProfil");
const labelFile = document.querySelector(".label-file");
file_upload_input.addEventListener("change", previewFile);

function previewFile() {
  const file_extension_regex = /\.(jpe?g|png|gif)$/i;

  if (
    this.files.length === 0 ||
    !file_extension_regex.test(this.files[0].name)
  ) {
    return;
  }
  console.log("ce fichier est ok");
  const file = this.files[0];

  const file_reader = new FileReader();

  file_reader.readAsDataURL(file);

  file_reader.addEventListener("load", (event) => {
    displayImage(event, file);
    // labelFile.style.color = "crimson"
    labelFile.style.display = "none";
  });
}

function displayImage(event, file) {
  const figureElement = document.createElement("figure");
  figureElement.id = "imageSelected";
  const pictureProfil = document.createElement("img");
  pictureProfil.id = "pictureProfil";
  pictureProfil.src = event.target.result;
  const figcaptionElement = document.createElement("figcaption");
  figcaptionElement.textContent = `fichier selectionné : ${file.name}`;
  const deletePicture = document.createElement("button");
  deletePicture.id = "imageDeleteBtn";
  deletePicture.className = "btn btn-close btn-danger ";
  //   deletePicture.innerHTML = '\u{1F5D1}';
  deletePicture.title = "   Supprimer cet élèment";

  figureElement.appendChild(pictureProfil);
  figureElement.appendChild(deletePicture);
  figureElement.appendChild(figcaptionElement);
  document.body.querySelector("#main").appendChild(figureElement);

  deletePicture.addEventListener("click", (event) => {
    if (confirm(" Êtes-vous de supprimer ? ")) {
      labelFile.style.display = "";
      file_upload_input.value = "";
      event.target.parentElement.remove();
    }
  });
}

/************Edit IMAGE */
const profil_edit_pictureProfil = document.querySelector(
  "#profil_edit_pictureProfil"
);
const oldpictureProfil = document.querySelector("#oldpictureProfil");
// const labelFile = document.querySelector(".label-file");

profil_edit_pictureProfil.addEventListener("change", prewiewEditFile());

function previewEditFile() {
  oldpictureProfil.remove();
}
