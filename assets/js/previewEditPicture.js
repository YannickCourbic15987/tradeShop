/************Edit IMAGE */
const profilEdit = document.querySelector("#profil_edit_pictureProfil");
const labelFile = document.querySelector(".label-file");
const oldpictureProfil = document.querySelector("#oldpictureProfil");

if (profilEdit) {
  profilEdit.addEventListener("change", previewEditFile);

  function previewEditFile() {
    const regex_file = /\.(jpe?g|png|gif)$/i;
    if (this.files.length === 0 || !regex_file.test(this.files[0].name)) {
      return;
    }
    console.log("ce fichier est ok");
    const files = this.files[0];
    const files_reader = new FileReader();
    files_reader.readAsDataURL(files);
    files_reader.addEventListener("load", (event) => {
      displayImage(event, files);
      labelFile.style.display = "none";
      oldpictureProfil.style.display = "none";
    });
  }
  function displayImage(event, files) {
    const figureElement = document.createElement("figure");
    figureElement.id = "imageSelected";
    const pictureProfil = document.createElement("img");
    pictureProfil.id = "pictureProfil";
    pictureProfil.src = event.target.result;
    const figcaptionElement = document.createElement("figcaption");
    figcaptionElement.textContent = `fichier selectionné : ${files.name}`;
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
        profilEdit.value = "";
        oldpictureProfil.style.display = "";
        event.target.parentElement.remove();
      }
    });
  }
}
