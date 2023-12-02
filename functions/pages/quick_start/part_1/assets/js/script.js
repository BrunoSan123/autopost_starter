
var current_url_path = php_vars.plugin_dir_url + 'functions/pages/quick_start/part_1';


/* ini pre-loading css background images */

var bgImg2 = new Image();
bgImg2.onload = function () {
    document.querySelector('#auto-post-step-2 .auto-post-cfcdigital-dynamic').style.backgroundImage = 'url(' + bgImg.src + ')';
};
bgImg2.src = current_url_path + "/assets/img/macbook.png";


var bgImg3 = new Image();
bgImg3.onload = function () {
    document.querySelector('#auto-post-step-3 .auto-post-cfcdigital-dynamic').style.backgroundImage = 'url(' + bgImg.src + ')';
};
bgImg3.src = current_url_path + "/assets/img/celular.png";


var bgImg4 = new Image();
bgImg4.onload = function () {
    document.querySelector('#auto-post-step-4 .auto-post-cfcdigital-dynamic').style.backgroundImage = 'url(' + bgImg.src + ')';
};
bgImg4.src = current_url_path + "/assets/img/lampadas.png";

/* end pre-loading css background images */
