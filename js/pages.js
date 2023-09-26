const storage = class {
  set = function (key, value) {
      return localStorage.setItem(key, value);
  }
  get = function (key) {
      return localStorage.getItem(key);
  }
  del = function (key) {
      return  localStorage.removeItem(key);
  }
}
const db = new storage();
function loadScript(route){
  let date = new Date()
  // Crear un elemento <script>
  var script = document.createElement('script');
  // Establecer la fuente del archivo JavaScript que deseas cargar
  script.src = route  + '?v=' + date.getTime();
  // Opcionalmente, puedes agregar atributos adicionales al elemento <script>
  script.type = 'text/javascript';
  // Esperar a que el archivo se cargue antes de ejecutar cierto código
  script.onload = function() {
      // Aquí puedes poner el código que deseas ejecutar después de que el archivo se haya cargado
      console.log('Archivo JavaScript cargado correctamente');
  };
  // Añadir el elemento <script> al documento
  setTimeout(() => {
    document.body.appendChild(script);
  }, 200);
}
function renderHtml(html, data,pattern=/{{(.*?)}}/g){
  return html.replace(pattern, (match, key) => {
      const keys = key.trim().split(".");
      let value = data;
      for (const k of keys) {
          value = value[k];
          if (typeof value === "undefined") {
              return match;
          }
      }
      return value;
  });
}
function fullScrips(){
  const scripts = [
    "js/jquery-ui.js",
    "js/superfish.js",
    "js/slick.js",
    "js/jquery.superslides.js",
    "js/jquery.fancybox.js",
    "js/jquery.sticky.js",
    "js/jquery.easing.1.3.js",
    "js/select2.js",
    "js/owl.carousel.js",
    "js/jquery.appear.js",
    "js/yjsg.yjsgroundprogress.js",
    "js/popper.min.js",
    "js/bootstrap.min.js",
    "js/googlemap.js",
    "js/loadEvents.js"
  ];
  scripts.map(k => {
      loadScript(k)
  });
}
async function getGallery(lng){
  const productsBooking = JSON.parse( db.get("products") );
  let tmpGallery = await (new service(`components/galleryBooking`)).html(".html");   
  let tmpSection = await (new service(`components/sectionBooking`)).html(".html");   
  /* Renderizo idioma */
  tmpGallery = renderHtml(tmpGallery, lng);
  tmpSection = renderHtml(tmpSection, lng);
  
  productsBooking.map((k,i) => {
      let stars = [];

      /* Agrego descripcion con límite de carácteres */
      k.descriptionmin = k.description.substring(0, 50) + "...";
      k.descriptionmiddle = k.description.substring(0, 250) + "...";
      /* Agrego dominio a las imagenes y selecciono imagen random */
      k.pictureBase = lng.website.api + k.pictures[ (Math.floor(Math.random() * k.pictures.length)) ];
      k.pictureBase1 = lng.website.api + k.pictures[ (Math.floor(Math.random() * k.pictures.length)) ];

      /* Agrego las estrellas de cada alojamiento */
      for (let index = 0; index < k.stars; index++) {
        stars.push(`<i class="fa fa-star"></i>`);
      }
      /* Inicio Seccion Formato Galeria */
        let renderGallery = renderHtml(tmpGallery, k, /\[\[(.*?)\]\]/g)
        
        renderGallery = renderGallery.replace('__REPLACE_STARS__', stars.join("\n"));
        
        /* Agrego lo que incluye cada alojamiento */
        let includesG = k.includes.map((inc) => {
            return `<div class="room-ic room-ic-general">
                      <i class="fa ${inc.class} " aria-hidden="true"></i>
                      <div class="txt1">${inc.text}</div>
                    </div>`
        }).slice(0,5); /* Devuelvo los primeros 5 items  */
        renderGallery = renderGallery.replace('__REPLACE_INCLUDES__', includesG.join("\n"));
        $(`#bookingGallery`).length > 0 && $(`#bookingGallery`).append(renderGallery);
      /* Fin Seccion Formato Galeria */
      
    /* Inicio Formato Secciones */
      let renderSection = renderHtml(tmpSection, k, /\[\[(.*?)\]\]/g);
      renderSection = renderSection.replace('__REPLACE_STARS__', stars.join("\n"));
      /* Agrego lo que incluye cada alojamiento */
      let includesS = k.includes.map((inc) => {
        return `<img title="${inc.text}"  src="images/${inc.icon}" alt="${inc.text}" class="img-fluid">`;
      })
      renderSection = renderSection.replace('__REPLACE_INCLUDES__', includesS.join("\n"));
      $(`#tabs2-2`).length > 0 && $(`#tabs2-2`).append(renderSection);
    /* Fin Formato Secciones */

  });

  return true;
}
async function getTeam(lng){
if($(`#teamList`).length > 0){
  const endPoint = "endpoint=Booking&action=getAllProfiles";
  const lenguage = {}
  const profiles = await (new service()).get(endPoint , lenguage);
  if(profiles.code == 200){
      const team = await (new service(`components/ourTeam`)).html(".html");   
      const data = profiles.result;
      /* Renderizo perfiles */
      data.map((k,i) => {
        k.picture = lng.website.api + k.picture;
        const renderTeam = renderHtml(team, k, /\[\[(.*?)\]\]/g)
        $(`#teamList`).length > 0 && $(`#teamList`).append(renderTeam);
      });
  }
}
return true;
}
async function getBannerHome(lng){
  if($(`#slides ul.slides-container`).length > 0){
    const endPoint = "endpoint=Booking&action=getAllImages";
    const lenguage = {}
    const images = await (new service()).get(endPoint , lenguage);
    if(images.code == 200){
        const data = images.result;
        /* Renderizo perfiles */
        data.map((k,i) => {
          const img = lng.website.api + k;
          const slide = `<li>
                            <img src="${img}" alt="" class="img">
                        </li>`;
          $(`#slides ul.slides-container`).append(slide);
        });
    }
    return images;
  }
  return false;
}

async function viewDetail(lng){
  /* obtengo referencia de producto */
  const hotels = JSON.parse( db.get('products') );
  const id = window.location.search.split('?')[1];
  let hotel = hotels.filter( k => k.id === id )
  if(hotel){
    hotel = hotel[0];
    let tmpBookingDetail = $(`#root`).html();   
    /* Renderizamos el contenido con la seleccion */
    let renderHTML = renderHtml(tmpBookingDetail, hotel, /\[\[(.*?)\]\]/g);
    
    // /* Agrego lo que incluye cada alojamiento */
    // let pictures = hotel.pictures.map((inc) => {
    //   return `<div class="our-rooms-icon">
    //               <figure><img src="images/${inc.icon}" alt="" class="img-fluid"></figure>
    //               <div class="our-rooms-icon-txt1">${inc.text}</div>
    //               <div class="our-rooms-icon-txt2">${inc.inc}</div>
    //           </div>`
    // });
    let sliderItem = [];
    let sliderItemInner = [];
    for (let index = 0; index < hotel.pictures.length; index++) {
      let elm = lng.website.api + hotel.pictures[index];
      /* Imagen ampliada */
      const htmlItem = `<div class="slider-item">
                          <img src="${elm}" alt="" class="img-fluid">
                      </div>`;
      /* Agreggo navegacion de imagenes mini */
      const htmlItemInner = `<div class="slider-item">
                                <div class="slider-item-inner">
                                    <img src="${elm}" alt="" class="img-fluid">
                                </div>
                            </div>`;
                            
      sliderItem.push(htmlItem);
      sliderItemInner.push(htmlItemInner);
      
    }
    renderHTML = renderHTML.replace('__REPLACE_GALLERY__', sliderItem.join("\n"));
    renderHTML = renderHTML.replace('__REPLACE_GALLERY__', sliderItemInner.join("\n"));
    
    /* Agrego lo que incluye cada alojamiento */
    let includesG = hotel.includes.map((inc) => {
      return `<div class="our-rooms-icon">
                  <figure><img src="images/${inc.icon}" alt="" class="img-fluid"></figure>
                  <div class="our-rooms-icon-txt1">${inc.text}</div>
                  <div class="our-rooms-icon-txt2">${inc.inc}</div>
              </div>`
    });
    renderHTML = renderHTML.replace('__REPLACE_INCLUDES__', includesG.slice(0,4).join("\n"));
    renderHTML = renderHTML.replace('__REPLACE_INCLUDES__', includesG.slice(4,8).join("\n"));

    /* Agrego las estrellas de cada alojamiento */
    let stars = [];
    for (let index = 0; index < hotel.stars; index++) {
      stars.push(`<i class="fa fa-star"></i>`);
    }
    renderHTML = renderHTML.replace('__REPLACE_STARS__', stars.join("\n"));
    /* Renderizo la vista nuevamente */
    $(`#root`).length > 0 && $(`#root`).html(renderHTML);
  }
}