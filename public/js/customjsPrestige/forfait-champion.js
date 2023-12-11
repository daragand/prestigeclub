/**
 * Ce fichier permet d'afficher ou de cacher le div contenant les informations sur le forfait champion. Si le forfait est choisi, on permet à l'utilisateur de définir les deux photos qu'il souhaite télécharger.
 */
//Récupération de la div où sont affichées les photos
const divChampion = document.getElementById('championChoix');

//ajout d'une classe pour sélectionner tous les input de forfaits
const inputTriggers = document.querySelectorAll('.trigger-champion');


//par défaut, les images ne sont pas affichées
divChampion.classList.add('d-none');

//ajout d'un écouteur sur les input de forfaits. Si le forfait champion est choisi, on affiche les images. Sinon, on les cache.
inputTriggers.forEach((inputTrigger) => {
  inputTrigger.addEventListener('change', () => {
    if (inputTriggers[[0]].checked === true || inputTriggers[[2]].checked === true) {
      
      divChampion.classList.add('d-none');
    } else {
        divChampion.classList.remove('d-none');
    }
  });
});



// pour la gestion des photos choisies par l'utilisateur pour le forfait champion

const checkboxLimit = 2;//nombre de photos que l'utilisateur peut choisir 
const checkboxes = document.querySelectorAll('.championPh');

console.log('les différentes images',checkboxes);

//ajout d'un écouteur sur les checkbox. Si l'utilisateur a choisi plus de photos que le nombre autorisé, on décoche la dernière photo choisie.
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('change', () => {
    const checkedCount = document.querySelectorAll('input[type="checkbox"][name="chamionPh"]:checked').length;
    if (checkedCount > checkboxLimit) {
      checkbox.checked = false;
    }
  });
});


// En cas de clic sur le bouton de validation du formulaire, on vérifie que l'utilisateur a bien choisi le nombre de photos autorisé. Si ce n'est pas le cas, on affiche un message d'erreur.

const form = document.getElementById('formCart');

//ajout d'un écouteur sur le bouton de validation du formulaire
form.addEventListener('submit', (e) => {
  const checkedCount = document.querySelectorAll('input[type="checkbox"][name="chamionPh"]:checked').length;
  if (inputTriggers[[1]].checked === true && checkedCount !== checkboxLimit) {
    e.preventDefault();
    alert('Vous devez choisir deux photos pour le forfait Champion');
  }
});
