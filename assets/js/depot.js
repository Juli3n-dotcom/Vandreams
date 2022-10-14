 //traitement photo
 function handleFiles(file, preview) {
    preview.innerHTML = "";
    let imageType = /^image\//;

    if (!imageType.test(file.type)) {
        return null;
    }

    let img = document.createElement("img");
    img.classList.add("image_preview");
    img.file = file;
    preview.appendChild(img);
    img.style.width = '50%';
    img.style.maxWidth = '130px';
    img.style.heigt = 'auto';
    img.style.maxHeight = '150px';
    img.style.display = 'block';
    img.style.marginLeft = 'auto';
    img.style.marginRight = 'auto';
    img.style.marginBottom = '1rem';
    let reader = new FileReader();
    reader.onload = (function (aImg) {
        return function (e) {
            aImg.src = e.target.result;
        };
    })(img);
    reader.readAsDataURL(file);
}
for (let i = 0; i < 3; i++) {

    let inputFileElt = document.getElementById('photo' + (i + 1));
    let etiquetteImageElt = document.getElementById('etiquette_image' + (i + 1));
    let previewElt = document.getElementById('preview' + (i + 1));
   

    inputFileElt.addEventListener('change', function () {
        handleFiles(inputFileElt.files[0], previewElt);
        let nom_image = 'ok';
        etiquetteImageElt.textContent = nom_image;
    });

}   


  var btn_depot = document.getElementById('btn_depot');
  var next1 = document.getElementById('next1');
  var depot1 = document.getElementById('depot_1');
  var btn1 = document.getElementById('infos_btn')
  var next2 = document.getElementById('next2');
  var prev1 = document.getElementById('prev1');
  var depot2 = document.getElementById('depot_2');
  var depot3 = document.getElementById('depot_3');
  var next3 = document.getElementById('next3');
  var prev2 = document.getElementById('prev2');
  var depot4 = document.getElementById('depot_4');
  var next4 = document.getElementById('next4');
  var prev3 = document.getElementById('prev3');

  //page1
  next1.addEventListener('click', function(){
     depot_1.style.left ="-400px";
     depot_2.style.left ="50px";
     btn_depot.style.width ="150px";

  })
//page 2
  prev1.addEventListener('click', function(){
   depot_1.style.left ="50px";
   depot_2.style.left ="450px";
   btn_depot.style.width ="75px";
  })

  next2.addEventListener('click', function(){
   depot_2.style.left ="-400px";
   // depot_2.style.left ="50px";
   depot_3.style.left="50px";
   btn_depot.style.width ="225px";

})
//page 3
prev2.addEventListener('click', function(){
   depot_2.style.left ="50px";
   depot_3.style.left ="450px";
   btn_depot.style.width ="150px";
  })
next3.addEventListener('click', function(){
   depot_3.style.left ="-400px";
   depot_4.style.left="50px";
   btn_depot.style.width ="300px";
})

//page 4

prev3.addEventListener('click', function(){
   depot_3.style.left ="50px";
   depot_4.style.left ="450px";
   btn_depot.style.width ="225px";
  })

  next4.addEventListener('click', function(){
   depot_4.style.left ="-400px";
   depot_5.style.left="50px";
   btn_depot.style.width ="350px";
})