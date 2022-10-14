$(function() {

   $(document).on('click','#account',function (){;
       $("#topbar-menu").toggleClass("show");
       $("#topbar-menu").toggleClass("hide");
       $(this).find('#ico-account').toggleClass('ion-chevron-down');
       $(this).find('#ico-account').toggleClass('ion-chevron-up');
   })
   $(document).on('click','.submenu',function (){
       $(this).find("#sub-content").toggleClass("show");
       $(this).find("#sub-content").toggleClass("hide");
       $(this).find('#icon').toggleClass('ion-chevron-right');
       $(this).find('#icon').toggleClass('ion-chevron-up');
   })
    
});

const lang =  navigator.language;
let date = new Date();
let dayNumber = date.getDate();
let month = date.getMonth();
let dayName = date.toLocaleString(lang,{weekday: 'long'});
let monthName = date.toLocaleString(lang,{month: 'long'});
let year = date.getFullYear()

document.getElementById('monthName').innerHTML = monthName.toUpperCase();
document.getElementById('dayName').innerHTML = dayName.toUpperCase();
document.getElementById('dayNumber').innerHTML = dayNumber;
document.getElementById('year').innerHTML = year;
