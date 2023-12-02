jQuery(document).ready(function () {
    var tabcontent = document.getElementsByClassName("auto-post-tabcontent");
    for (i = 1; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    jQuery('div.auto-post-fade').fadeIn(1000).removeClass('hidden');
});

function autoPostChangeStep(id) {
    // Declare all variables
    var i, tablinks;
    jQuery('div.auto-post-fade').fadeToggle('fast', function () {

        // Get all elements with class="auto-post-tabcontent" and hide them
        var tabcontent = document.getElementsByClassName("auto-post-tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        var a = new Image;
        jQuery('div.auto-post-fade').fadeToggle('fast', function () {
            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(id).style.display = "block";
        });
        
    });
    
}

function setLocalStorageItemModal(){
    const inputs = document.querySelectorAll(".input_value")
    inputs.forEach((e,i)=>{
        if(e.checked){
            //console.log(e);
            console.log("localStorage: (",e.name,")", e.value,);
            localStorage.setItem(e.name,e.value);
        } 
        if (e.tagName === "TEXTAREA") {
            console.log("localStorage: (",e.name,")", e.value,);
            localStorage.setItem(e.name, e.value);
        }
    })
}
function finishQuickStart(){
    console.log("agora tem q pegar os itens do localstorage e fazer algo com eles");
    const imagesApiValue = localStorage.getItem('images-api');
    const textApiValue = localStorage.getItem('text-api');
    const midjourneyApiKey = localStorage.getItem('Midjourney API Key');
    const gpt3ApiKey = localStorage.getItem('GPT-3 API Key');
    const experienceValue = localStorage.getItem('experience');
    const preferredTopicsValue = localStorage.getItem('preferred-topics');
    // Send data to the REST endpoint using fetch
    url ="/wp-json/custom/v1/quickstart/";
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            imagesApi: imagesApiValue,
            textApi: textApiValue,
            midjourneyApiKey: midjourneyApiKey,
            gpt3ApiKey: gpt3ApiKey,
            experience: experienceValue,
            preferredTopics: preferredTopicsValue,
        }),
    })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Response from the server
            window.location.href='/wp-admin/admin.php?page=chatgpt_dashboard_page'
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


