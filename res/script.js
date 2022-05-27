$(document).ready(function(){
  //Leia lingid registreerimise ja logimise vahel valimiseks ja nende lahtrid
  const tabs = document.querySelectorAll("[data-tab-target]")
  const tabContents = document.querySelectorAll("[data-tab-content]")

  //Kui sessionstarage-is on activetab siis määra see (peale värsekndamist)
  if (sessionStorage.getItem("activetab") !== null) {
    //Eemalda active class kõigilt lahtritelt ja linkidelt
    tabContents.forEach(content => {
      content.classList.remove("active")
    });
    tabs.forEach(tab => {
      tab.classList.remove("active")
    });

    //Võta activetab ja lisa active class elementidele
    //current - lingi id
    //target - registreerimis/logimis lahtrid
    const current = document.getElementById(sessionStorage.getItem("activetab"))
    const target = document.querySelector(current.dataset.tabTarget)
    current.classList.add("active")
    target.classList.add("active")
  };

  //Lisa kuulaja linkidele, mis vahetavad registreerimise ja logimise vahel
  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      //target - registreerimis/logimis lahtrid
      const target = document.querySelector(tab.dataset.tabTarget)

      //Eemalda active class kõigilt lahtritelt ja linkidelt
      tabContents.forEach(content => {
        content.classList.remove("active")
      });
      tabs.forEach(tab => {
        tab.classList.remove("active")
      });

      tab.classList.add("active")
      target.classList.add("active")
      //Lisa sessionstoragisse activetab, et peale värskendamist jääks sama tab
      sessionStorage.setItem("activetab", tab.id)
    });
  });


  //Ei saada vormi uuesti kui leht värskendatakse
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }

});    