
const apiUrl = `https://geo.api.gouv.fr/departements`;
const apiUrlRegion = "https://geo.api.gouv.fr/regions";
const select = document.querySelector('#department');
const selectRegion = document.querySelector('#region');
const inputCity = document.querySelector('#add_addresses_PostalCode');
const inputCitys = document.querySelector('#add_addresses_city');
const inputAddress = document.querySelector('#add_addresses_adresse');
console.log(apiUrl);
//créer une requête
let request = new XMLHttpRequest();
request.open("GET", apiUrl); // paramétre GET / POST , on va pouvoir passer des données via url 
request.responseType = 'Json';
request.send();

// dés qu'on reçoit une réponse , cette fonction est executée

request.addEventListener('load' , ResponseRequest);

function ResponseRequest(){
  if(request.readyState === XMLHttpRequest.DONE){
    if(request.status === 200 ){
      let reponse = JSON.parse(request.response); // on stock la réponse
       
      console.log(reponse, typeof(reponse));
      console.log('ok');
        // je fais une boucle pour extraire les données du tableau 

        for (let i = 0; i < reponse.length; i++) {
            //je crée mes options 

            let option = document.createElement('option');
            select.appendChild(option);
            option.value = `${reponse[i].code} , ${reponse[i].nom}`;
            option.textContent = `${reponse[i].code} , ${reponse[i].nom}` ;
          
        }
    }
    else{
      alert('Un problème est survenue , merci de revenir plus tard.')
    }
  }
}

// j'extrait les données pour avoir la liste des régions 

async function recupRegion(){
const request = await fetch(apiUrlRegion , {
  method: 'GET'
});
 if(!request.ok){
  alert('Un problème est survenu.')
 } else{
  let data = await request.json();
  console.log(data);
  for (let i = 0; i < data.length; i++) {
    let options = document.createElement('option');
    selectRegion.appendChild(options);
    options.value = `${data[i].code} , ${data[i].nom}`;
    options.textContent = `${data[i].nom}` ;
  }
};
}

function readCodePostal(){
  inputCity.addEventListener('input' , (e) => {
    let Postal = e.target.value;
    
    console.log(`${Postal} ${Postal.length}`);

    if(Postal.length === 5 ){
       sendPostalData(Postal);
    }else{
      inputCitys.value = "";
    }
  })
}
async function sendPostalData(PostalData){
  const apiUrlCommune = `https://geo.api.gouv.fr/communes/?codePostal=${PostalData}`;
  console.log(apiUrlCommune , PostalData);

  const request = await fetch(
    apiUrlCommune , 
    {method : 'GET'}
    )
    if(!request.ok){
      alert('Un problème est survenu');
    }
    else{
      const data = await request.json();
      console.log(data[0].nom);
      inputCitys.value = data[0].nom;
      
    }
}

function searchCity(){
  
  inputAddress.addEventListener('input' , addressRead);
  
  function addressRead(e){
    console.log(e.target.value);
    
    let address = e.target.value;
    if(address){
      
      fetchAddress(address)
    }
    
  }
  
  async function fetchAddress(address){
    const suggest = document.querySelector('#suggest');
  let linkAddress = address.split(' ');
  let JoinAdress =  linkAddress.join("%20");

  console.log(JoinAdress);
  const apiUrlCity = `https://api-adresse.data.gouv.fr/search/?q=${JoinAdress}&type=housenumber&autocomplete=1&limit=11`;
if(JoinAdress.length > 3){

  const request = await fetch(
    apiUrlCity ,
    {method: 'GET'}
  );

  if(!request.ok){
    alert('Un problème est survenu ')
  }
  else{
    const data = await request.json();

    
    if(data.features  ){
      console.log(data.features);
      suggest.classList.remove('d-none')

        let labelAddress = [];
       for (let i = 0; i < data.features.length; i++) {
     labelAddress.push(data.features[i].properties.label);
        
       }
       console.log(labelAddress);
       for (let i = 0; i < labelAddress.length; i++) {
        const label = document.createElement('p');
        suggest.appendChild(label);
     
           label.innerHTML = labelAddress[i];
         

    console.log(labelAddress[i] , labelAddress.length);
        
       }
    }
    else{
      console.log('rien trouver');
  
    }

  }
} else{
   console.log('ne trouve rien');
}

}

}


searchCity();
readCodePostal();
recupRegion();

