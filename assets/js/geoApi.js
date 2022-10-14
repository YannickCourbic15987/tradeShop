const apiUrl = `https://geo.api.gouv.fr/departements`;
const apiUrlRegion = "https://geo.api.gouv.fr/regions";
const select = document.querySelector("#department");
const selectRegion = document.querySelector("#region");
const inputCity = document.querySelector("#add_addresses_PostalCode");
const inputCitys = document.querySelector("#add_addresses_city");
const inputAddress = document.querySelector("#add_addresses_adresse");
const inputNumberOfStreet = document.querySelector(
  "#add_addresses_number_of_street"
);
const inputTypeOfWay = document.querySelector("#add_addresses_typeOfWay");
const inputStreet = document.querySelector("#add_addresses_name_of_street");
if (select) {
  console.log(apiUrl);
  //créer une requête
  let request = new XMLHttpRequest();
  request.open("GET", apiUrl); // paramétre GET / POST , on va pouvoir passer des données via url
  request.responseType = "Json";
  request.send();

  // dés qu'on reçoit une réponse , cette fonction est executée

  request.addEventListener("load", ResponseRequest);

  function ResponseRequest() {
    if (request.readyState === XMLHttpRequest.DONE) {
      if (request.status === 200) {
        let reponse = JSON.parse(request.response); // on stock la réponse

        console.log(reponse, typeof reponse);
        console.log("ok");
        // je fais une boucle pour extraire les données du tableau

        for (let i = 0; i < reponse.length; i++) {
          //je crée mes options

          let option = document.createElement("option");
          select.appendChild(option);
          option.value = `${reponse[i].code} , ${reponse[i].nom}`;
          option.textContent = `${reponse[i].code} , ${reponse[i].nom}`;
        }
      } else {
        alert("Un problème est survenue , merci de revenir plus tard.");
      }
    }
  }

  // j'extrait les données pour avoir la liste des régions

  async function recupRegion() {
    const request = await fetch(apiUrlRegion, {
      method: "GET",
    });
    if (!request.ok) {
      alert("Un problème est survenu.");
    } else {
      let data = await request.json();
      console.log(data);
      for (let i = 0; i < data.length; i++) {
        let options = document.createElement("option");
        selectRegion.appendChild(options);
        options.value = `${data[i].code} , ${data[i].nom}`;
        options.textContent = `${data[i].nom}`;
      }
    }
  }

  function readCodePostal() {
    inputCity.addEventListener("input", (e) => {
      let Postal = e.target.value;

      console.log(`${Postal} ${Postal.length}`);

      if (Postal.length === 5) {
        sendPostalData(Postal);
      } else {
        inputCitys.value = "";
      }
    });
  }
  async function sendPostalData(PostalData) {
    const apiUrlCommune = `https://geo.api.gouv.fr/communes/?codePostal=${PostalData}`;
    console.log(apiUrlCommune, PostalData);

    const request = await fetch(apiUrlCommune, { method: "GET" });
    if (!request.ok) {
      alert("Un problème est survenu");
    } else {
      const data = await request.json();
      console.log(data[0].nom);
      inputCitys.value = data[0].nom;
    }
  }

  function searchCity() {
    inputAddress.addEventListener("input", addressRead);

    function addressRead(e) {
      console.log(e.target.value);

      let address = e.target.value;

      if (address) {
        fetchAddress(address);
      }
    }

    async function fetchAddress(address) {
      const suggest = document.querySelector("#suggest");
      let linkAddress = address.split(" ");
      console.log(linkAddress);
      let JoinAdress = linkAddress.join("%20");
      autoCompleteAdress(linkAddress);

      console.log(JoinAdress);
      const apiUrlCity = `https://api-adresse.data.gouv.fr/search/?q=${JoinAdress}&type=housenumber&autocomplete=0&limit=5`;

      if (JoinAdress.length > 3) {
        const request = await fetch(apiUrlCity, { method: "GET" });

        if (!request.ok) {
          alert("Un problème est survenu ");
        } else {
          suggest.textContent = "";
          const data = await request.json();
          if (data.features) {
            console.log(data);
            suggest.classList.remove("hidden");

            let labelAddress = [];
            for (let i = 0; i < data.features.length; i++) {
              labelAddress.push(data.features[i].properties.label);
            }
            console.log(labelAddress);
            for (let i = 0; i < labelAddress.length; i++) {
              // suggest.style.zIndex = "1";
              const label = document.createElement("p");
              label.className = "suggestTxt";
              suggest.appendChild(label);
              // label.innerHTML ="<button class='btnSuggest'>" + labelAddress[i] + "</button>";
              console.log(labelAddress[i], labelAddress.length);
              const btnSuggest = document.createElement("button");
              label.appendChild(btnSuggest);
              btnSuggest.innerHTML = labelAddress[i];
              btnSuggest.style.border = "none";
              btnSuggest.style.background = "none";
              // btnSuggest.style.zIndex = '1000';

              btnSuggest.addEventListener("mouseenter", () => {
                // console.log('mouse enter');
                label.style.backgroundColor = "#dcdcdc";
              });

              btnSuggest.addEventListener("mouseleave", () => {
                // console.log('mouse leave');
                label.style.backgroundColor = "white";
              });

              btnSuggest.addEventListener("click", (e) => {
                console.log(e.target);
                inputAddress.value = e.target.firstChild.data;
                console.log(e.target.firstChild.data);
                suggest.classList.add("hidden");
                let tabAddress = e.target.firstChild.data.split(" ");
                console.log(tabAddress);
              });
            }
          } else {
            console.log("rien trouver");
          }
        }
      } else if (JoinAdress.length === 1) {
        suggest.classList.add("hidden");
      } else {
        console.log("ne trouve rien");
      }
    }
  }
  const inputDepartment = document.querySelector("#add_addresses_Department");
  const inputRegion = document.querySelector("#add_addresses_Region");
  select.addEventListener("change", () => {
    // console.log(select.options[select.selectedIndex].text);
    inputDepartment.value = select.options[select.selectedIndex].text;
  });

  selectRegion.addEventListener("change", () => {
    // console.log(selectRegion.options[selectRegion.selectedIndex].text);
    inputRegion.value = selectRegion.options[selectRegion.selectedIndex].text;
  });

  function autoCompleteAdress(linkAddress) {
    // console.log(linkAddress);

    typeOfWay = [
      "Allée",
      "Avenue",
      "Boulevard",
      "Carrefour",
      "Chemin",
      "Chaussée",
      "Cité",
      "Corniche",
      "Cours",
      "Domaine",
      "Descente",
      "Ecart",
      "Esplanade",
      "Faubourg",
      "Grande Rue",
      "Faubourg",
      "Grande Rue",
      "Hameau",
      "Halle",
      "Impasse",
      "Lieu-dit",
      "Lotissement",
      "Marché",
      "Montée",
      "Passage",
      "Place",
      "Plaine",
      "Plateau",
      "Promenade",
      "Parvis",
      "Quartier",
      "Quai",
      "Résidence",
      "Ruelle",
      "Rocade",
      "Rond-Point",
      "Route",
      "Rue",
      "Sente-Sentier",
      "Square",
      "Terre-plein",
      "Traverse",
      "Villa",
      "Village",
    ];
    let nb = linkAddress.length - 2;
    linkAddress.splice(nb, 2);

    for (let item = 0; item < linkAddress.length; item++) {
      const match = new RegExp("[0-9]");
      let result = match.test(linkAddress[item]);

      //   console.log(`${item} : ${linkAddress[item]} , ${result}`);

      if (result === true) {
        inputNumberOfStreet.value = linkAddress[item];
      }
    }

    for (let x = 0; x < typeOfWay.length; x++) {
      for (let o = 0; o < linkAddress.length; o++) {
        if (typeOfWay[x] === linkAddress[o]) {
          inputTypeOfWay.value = linkAddress[o];
        }
      }
    }
  }

  searchCity();
  readCodePostal();
  recupRegion();
}
