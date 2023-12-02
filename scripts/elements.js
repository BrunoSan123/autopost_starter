const page_auto_post =document.body.classList.contains("toplevel_page_chatgpt_plugin")
const pg_a_p = document.body.classList.contains(
    "auto-post_page_chatgpt_projects_page"
  );

const seetings_page=document.body.classList.contains("auto-post_page_chatgpt_settings_page")


if(pg_a_p || seetings_page){													//SETTINGS
    const language_select=document.getElementById("textlanguage");
    const settings_language=document.getElementById("generated_text_language")
    console.log(language_select); 


    const languageCountryMapping = [
        { code: "af", name: "Afrikaans" },
        { code: "sq", name: "Albanian" },
        { code: "am", name: "Amharic" },
        { code: "ar", name: "Arabic" },
        { code: "as", name: "Assamese" },
        { code: "ay", name: "Aymara" },
        { code: "az", name: "Azerbaijani" },
        { code: "be", name: "Belarusian" },
        { code: "bg", name: "Bulgarian" },
        { code: "bho", name: "Bhojpuri" },
        { code: "bm", name: "Bambara" },
        { code: "bn", name: "Bengali" },
        { code: "bs", name: "Bosnian" },
        { code: "ca", name: "Catalan" },
        { code: "ceb", name: "Cebuano" },
        { code: "ckb", name: "Central Kurdish" },
        { code: "co", name: "Corsican" },
        { code: "cs", name: "Czech" },
        { code: "cy", name: "Welsh" },
        { code: "da", name: "Danish" },
        { code: "de", name: "German" },
        { code: "doi", name: "Dogri" },
        { code: "dv", name: "Divehi" },
        { code: "ee", name: "Ewe" },
        { code: "el", name: "Greek" },
        { code: "en", name: "English" },
        { code: "eo", name: "Esperanto" },
        { code: "es", name: "Spanish" },
        { code: "et", name: "Estonian" },
        { code: "eu", name: "Basque" },
        { code: "fa", name: "Persian" },
        { code: "fi", name: "Finnish" },
        { code: "fr", name: "French" },
        { code: "fy", name: "Western Frisian" },
        { code: "ga", name: "Irish" },
        { code: "gd", name: "Scottish Gaelic" },
        { code: "gl", name: "Galician" },
        { code: "gn", name: "Guaraní" },
        { code: "gom", name: "Goan Konkani" },
        { code: "gu", name: "Gujarati" },
        { code: "ha", name: "Hausa" },
        { code: "haw", name: "Hawaiian" },
        { code: "he", name: "Hebrew" },
        { code: "hi", name: "Hindi" },
        { code: "hmn", name: "Hmong" },
        { code: "hr", name: "Croatian" },
        { code: "ht", name: "Haitian Creole" },
        { code: "hu", name: "Hungarian" },
        { code: "hy", name: "Armenian" },
        { code: "id", name: "Indonesian" },
        { code: "ig", name: "Igbo" },
        { code: "ilo", name: "Iloko" },
        { code: "is", name: "Icelandic" },
        { code: "it", name: "Italian" },
        { code: "iw", name: "Hebrew" },
        { code: "ja", name: "Japanese" },
        { code: "jv", name: "Javanese" },
        { code: "jw", name: "Javanese" },
        { code: "ka", name: "Georgian" },
        { code: "kk", name: "Kazakh" },
        { code: "km", name: "Khmer" },
        { code: "kn", name: "Kannada" },
        { code: "ko", name: "Korean" },
        { code: "kri", name: "Krio" },
        { code: "ku", name: "Kurdish" },
        { code: "ky", name: "Kyrgyz" },
        { code: "la", name: "Latin" },
        { code: "lb", name: "Luxembourgish" },
        { code: "lg", name: "Ganda" },
        { code: "ln", name: "Lingala" },
        { code: "lo", name: "Lao" },
        { code: "lt", name: "Lithuanian" },
        { code: "lus", name: "Mizo" },
        { code: "lv", name: "Latvian" },
        { code: "mai", name: "Maithili" },
        { code: "mg", name: "Malagasy" },
        { code: "mi", name: "Maori" },
        { code: "mk", name: "Macedonian" },
        { code: "ml", name: "Malayalam" },
        { code: "mn", name: "Mongolian" },
        { code: "mni-Mtei", name: "Manipuri" },
        { code: "mr", name: "Marathi" },
        { code: "ms", name: "Malay" },
        { code: "mt", name: "Maltese" },
        { code: "my", name: "Burmese" },
        { code: "ne", name: "Nepali" },
        { code: "nl", name: "Dutch" },
        { code: "no", name: "Norwegian" },
        { code: "nso", name: "Northern Sotho" },
        { code: "ny", name: "Chichewa" },
        { code: "om", name: "Oromo" },
        { code: "or", name: "Odia" },
        { code: "pa", name: "Punjabi" },
        { code: "pl", name: "Polish" },
        { code: "ps", name: "Pashto" },
        { code: "pt", name: "Portuguese" },
        { code: "qu", name: "Quechua" },
        { code: "ro", name: "Romanian" },
        { code: "ru", name: "Russian" },
        { code: "rw", name: "Kinyarwanda" },
        { code: "sa", name: "Sanskrit" },
        { code: "sd", name: "Sindhi" },
        { code: "si", name: "Sinhala" },
        { code: "sk", name: "Slovak" },
        { code: "sl", name: "Slovenian" },
        { code: "sm", name: "Samoan" },
        { code: "sn", name: "Shona" },
        { code: "so", name: "Somali" },
        { code: "sq", name: "Albanian" },
        { code: "sr", name: "Serbian" },
        { code: "st", name: "Southern Sotho" },
        { code: "su", name: "Sundanese" },
        { code: "sv", name: "Swedish" },
        { code: "sw", name: "Swahili" },
        { code: "ta", name: "Tamil" },
        { code: "te", name: "Telugu" },
        { code: "tg", name: "Tajik" },
        { code: "th", name: "Thai" },
        { code: "ti", name: "Tigrinya" },
        { code: "tk", name: "Turkmen" },
        { code: "tl", name: "Tagalog" },
        { code: "tr", name: "Turkish" },
        { code: "ts", name: "Tsonga" },
        { code: "tt", name: "Tatar" },
        { code: "ug", name: "Uighur" },
        { code: "uk", name: "Ukrainian" },
        { code: "ur", name: "Urdu" },
        { code: "uz", name: "Uzbek" },
        { code: "vi", name: "Vietnamese" },
        { code: "xh", name: "Xhosa" },
        { code: "yi", name: "Yiddish" },
        { code: "yo", name: "Yoruba" },
        { code: "zh", name: "Chinese" },
        { code: "zh-CN", name: "Chinese (Simplified)" },
        { code: "zh-TW", name: "Chinese (Traditional)" },
        { code: "zu", name: "Zulu" }
    ];

    languageCountryMapping.forEach((e,i)=>{
        const option_child=`<option value="${e.code}">${e.name}</option>`
        if(language_select){
          language_select.innerHTML+=option_child;
        }

        if(settings_language){
          settings_language.innerHTML+=option_child;
        }
        
        
    })
    
    


}











    