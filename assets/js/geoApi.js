const codePostal = document.getElementById("add_addresses_PostalCode");
codePostal.addEventListener("input", (e) => {
  console.log(e.target.value);
});
const apiUrl = `https://geo.api.gouv.fr/communes?codePostal`;
//créer une requête
let request = new XMLHttpRequest();
request.open("", ""); // paramétre GET / POST
