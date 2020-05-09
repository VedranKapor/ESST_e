var lang = new Lang();
lang.dynamic('en', 'References/jquery-lang-js-master/langpack/en.json');
lang.dynamic('es', 'References/jquery-lang-js-master/langpack/es.json');
lang.dynamic('fr', 'References/jquery-lang-js-master/langpack/fr.json');
lang.init({
	defaultLang: 'en'
});

function changeLang(lng){
    $("#en").removeClass("active");
    $("#es").removeClass("active");
    $("#fr").removeClass("active");
    $.cookie("lang", lng);
    window.lang.change(lng);
    $("#"+lng).addClass("active");
}
