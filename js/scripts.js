
$(function () {
    "use strict";
    
    const config = db.get("config") ?? 'en';
    /* Cargamos idioma */
    (new service(`config/${config}`)).get('/config.json').then(async (lng) => {
        let path = window.location.pathname.split("/").pop().split('.')[0];
        if(!path){
            path = 'index';
        }
        return {lng, path}
    })
    /* Cargamos productos */
    .then(async ({lng, path}) => {
        const endPoint = "endpoint=Booking&action=getAllBooking";
        const lenguage = { lng : config }
        const products = await (new service()).get(endPoint , lenguage);
        if(products.code == 200){
            db.set("products", JSON.stringify(products.result))
        }
        return {lng, path}
    })
    /* Cargamos la vista */
    .then(async ({lng, path}) => {
        const file = (lng.website.viewsAllowed.includes(path) ? path : "404");
        const html = await (new service(`views/${file}`)).html(".html");   
        const renderHTML = renderHtml(html, lng)
        $("#root").html(renderHTML);
        return {lng, path}
    })
    /* Cargamos Formulario de reservacion */
    .then(async ({lng, path}) => {
        if(lng.website.bookingFormPage.includes(path)){
            const FormBooking = await (new service("components/formBooking")).html(".html");
            const renderFormBooking = renderHtml(FormBooking, lng)
            $("#containerFormBooking").html(renderFormBooking);
        }
        return {lng, path}
    })
    /* Cargamos la galeria para las reservaciones */
    .then(async ({lng, path}) => {
        const productsBooking = JSON.parse( db.get("products") );
        if(lng.website.bookingPage.includes(path) && productsBooking){
            await getGallery(lng);
        }
        return {lng, path}
    })
    /* Cargamos | clientes | equipo */
    .then(async ({lng, path}) => {
        if($("#what-client-say").length > 0){
            const clients = await (new service("components/clientSay")).html(".html");
            const renderClients = renderHtml(clients, lng)
            $("#what-client-say").html(renderClients);
        }
        if($("#our-team").length > 0){
            await getTeam(lng);
        }

        return {lng, path}
    })
    /* Cargamos el navbar */
    .then(async ({lng, path}) => {
        const navbar = await (new service("components/navbar")).html(".html");
        const renderNavbar = renderHtml(navbar, lng)
        $("#navbar").html(renderNavbar);
        return {lng, path}
    })
    /* Valido el idioma activo en el navbar */
    .then(async ({lng, path}) => {
        const img = lng.countries.list[lng.countries.selected].flag;
        const imglabel = lng.countries.list[lng.countries.selected].label;
        const imgHTML = `<img src="${img}" alt="" class="img-fluid img-flag">${imglabel}`
        $("#dropdownLanguage").html(imgHTML)
        return {lng, path}
    })
    /* Configuramos la pestaña activa del menú */
    .then(async ({lng, path}) => {
        if(/index/i.test(path) || !path){
            $(`#navbar`).addClass("top-wrapper-dark")
            $($(`a[href='./']`)[0]).addClass("active")
        }else{
            const keyword = window.location.pathname.split("/").pop().split('.')[0];
            const regex = new RegExp(`^${keyword}(\\.html)?$`, "i");
            const element = $("a").filter(function() {
                return regex.test($(this).attr("href"));
            });
            $(element[0]).addClass("active");
        }
        return {lng, path}
    })
    // /* Cargamos el footer */
    .then(async ({lng, path}) => {
        const navbar = await (new service("components/footer")).html(".html");
        const renderNavbar = renderHtml(navbar, lng)
        $("#footer").html(renderNavbar);
        return {lng, path}
    })
    /* Valido Home Page */
    .then(async ({lng, path}) => {
        if(lng.website.homePage == path){
            /* Listo el banner */
            await getBannerHome(lng);
            /* Seccion About */
            const about = await (new service(`views/about`)).html(".html");   
            const renderAbout = renderHtml(about, lng)
            $("#about").html(renderAbout);
            $("#best-places, .breadcrumbs1_wrapper, .page-numbers-wrapper, #our-team, .what-client-say-about").remove();
            /* Seccion Rooms */
            await getGallery(lng);
        }
        return {lng, path}
    })
    /* Valido vista detalle */
    .then(async ({lng, path}) => {
        if(lng.website.detailPage == path){
            await viewDetail(lng);
        }
        return {lng, path}
    })
    /* Cargamos el script de eventos y quitamos el loader */
    .then(({lng, path}) => {
        loadEvents()
        /* Mostramos la página */
        jQuery( "#loader" ).delay( 2000 ).fadeOut( 300 );
        return {lng, path}
    })
});
