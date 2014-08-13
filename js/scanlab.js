/* Country codes */
var country_codes=[{name:"Anonymous Proxy",alpha2:"A1"},{name:"Satellite Provider",alpha2:"A2"},{name:"Other Country",alpha2:"O1"},{name:"Andorra",alpha2:"AD"},{name:"United Arab Emirates",alpha2:"AE"},{name:"Afghanistan",alpha2:"AF"},{name:"Antigua and Barbuda",alpha2:"AG"},{name:"Anguilla",alpha2:"AI"},{name:"Albania",alpha2:"AL"},{name:"Armenia",alpha2:"AM"},{name:"Angola",alpha2:"AO"},{name:"Asia/Pacific Region",alpha2:"AP"},{name:"Antarctica",alpha2:"AQ"},{name:"Argentina",alpha2:"AR"},{name:"American Samoa",alpha2:"AS"},{name:"Austria",alpha2:"AT"},{name:"Australia",alpha2:"AU"},{name:"Aruba",alpha2:"AW"},{name:"Aland Islands",alpha2:"AX"},{name:"Azerbaijan",alpha2:"AZ"},{name:"Bosnia and Herzegovina",alpha2:"BA"},{name:"Barbados",alpha2:"BB"},{name:"Bangladesh",alpha2:"BD"},{name:"Belgium",alpha2:"BE"},{name:"Burkina Faso",alpha2:"BF"},{name:"Bulgaria",alpha2:"BG"},{name:"Bahrain",alpha2:"BH"},{name:"Burundi",alpha2:"BI"},{name:"Benin",alpha2:"BJ"},{name:"Saint Bartelemey",alpha2:"BL"},{name:"Bermuda",alpha2:"BM"},{name:"Brunei Darussalam",alpha2:"BN"},{name:"Bolivia",alpha2:"BO"},{name:"Bonaire, Saint Eustatius and Saba",alpha2:"BQ"},{name:"Brazil",alpha2:"BR"},{name:"Bahamas",alpha2:"BS"},{name:"Bhutan",alpha2:"BT"},{name:"Bouvet Island",alpha2:"BV"},{name:"Botswana",alpha2:"BW"},{name:"Belarus",alpha2:"BY"},{name:"Belize",alpha2:"BZ"},{name:"Canada",alpha2:"CA"},{name:"Cocos (Keeling) Islands",alpha2:"CC"},{name:"Congo, The Democratic Republic of the",alpha2:"CD"},{name:"Central African Republic",alpha2:"CF"},{name:"Congo",alpha2:"CG"},{name:"Switzerland",alpha2:"CH"},{name:"Cote d'Ivoire",alpha2:"CI"},{name:"Cook Islands",alpha2:"CK"},{name:"Chile",alpha2:"CL"},{name:"Cameroon",alpha2:"CM"},{name:"China",alpha2:"CN"},{name:"Colombia",alpha2:"CO"},{name:"Costa Rica",alpha2:"CR"},{name:"Cuba",alpha2:"CU"},{name:"Cape Verde",alpha2:"CV"},{name:"Curacao",alpha2:"CW"},{name:"Christmas Island",alpha2:"CX"},{name:"Cyprus",alpha2:"CY"},{name:"Czech Republic",alpha2:"CZ"},{name:"Germany",alpha2:"DE"},{name:"Djibouti",alpha2:"DJ"},{name:"Denmark",alpha2:"DK"},{name:"Dominica",alpha2:"DM"},{name:"Dominican Republic",alpha2:"DO"},{name:"Algeria",alpha2:"DZ"},{name:"Ecuador",alpha2:"EC"},{name:"Estonia",alpha2:"EE"},{name:"Egypt",alpha2:"EG"},{name:"Western Sahara",alpha2:"EH"},{name:"Eritrea",alpha2:"ER"},{name:"Spain",alpha2:"ES"},{name:"Ethiopia",alpha2:"ET"},{name:"Europe",alpha2:"EU"},{name:"Finland",alpha2:"FI"},{name:"Fiji",alpha2:"FJ"},{name:"Falkland Islands (Malvinas)",alpha2:"FK"},{name:"Micronesia, Federated States of",alpha2:"FM"},{name:"Faroe Islands",alpha2:"FO"},{name:"France",alpha2:"FR"},{name:"Gabon",alpha2:"GA"},{name:"United Kingdom",alpha2:"GB"},{name:"Grenada",alpha2:"GD"},{name:"Georgia",alpha2:"GE"},{name:"French Guiana",alpha2:"GF"},{name:"Guernsey",alpha2:"GG"},{name:"Ghana",alpha2:"GH"},{name:"Gibraltar",alpha2:"GI"},{name:"Greenland",alpha2:"GL"},{name:"Gambia",alpha2:"GM"},{name:"Guinea",alpha2:"GN"},{name:"Guadeloupe",alpha2:"GP"},{name:"Equatorial Guinea",alpha2:"GQ"},{name:"Greece",alpha2:"GR"},{name:"South Georgia and the South Sandwich Islands",alpha2:"GS"},{name:"Guatemala",alpha2:"GT"},{name:"Guam",alpha2:"GU"},{name:"Guinea-Bissau",alpha2:"GW"},{name:"Guyana",alpha2:"GY"},{name:"Hong Kong",alpha2:"HK"},{name:"Heard Island and McDonald Islands",alpha2:"HM"},{name:"Honduras",alpha2:"HN"},{name:"Croatia",alpha2:"HR"},{name:"Haiti",alpha2:"HT"},{name:"Hungary",alpha2:"HU"},{name:"Indonesia",alpha2:"ID"},{name:"Ireland",alpha2:"IE"},{name:"Israel",alpha2:"IL"},{name:"Isle of Man",alpha2:"IM"},{name:"India",alpha2:"IN"},{name:"British Indian Ocean Territory",alpha2:"IO"},{name:"Iraq",alpha2:"IQ"},{name:"Iran, Islamic Republic of",alpha2:"IR"},{name:"Iceland",alpha2:"IS"},{name:"Italy",alpha2:"IT"},{name:"Jersey",alpha2:"JE"},{name:"Jamaica",alpha2:"JM"},{name:"Jordan",alpha2:"JO"},{name:"Japan",alpha2:"JP"},{name:"Kenya",alpha2:"KE"},{name:"Kyrgyzstan",alpha2:"KG"},{name:"Cambodia",alpha2:"KH"},{name:"Kiribati",alpha2:"KI"},{name:"Comoros",alpha2:"KM"},{name:"Saint Kitts and Nevis",alpha2:"KN"},{name:"Korea, Democratic People's Republic of",alpha2:"KP"},{name:"Korea, Republic of",alpha2:"KR"},{name:"Kuwait",alpha2:"KW"},{name:"Cayman Islands",alpha2:"KY"},{name:"Kazakhstan",alpha2:"KZ"},{name:"Lao People's Democratic Republic",alpha2:"LA"},{name:"Lebanon",alpha2:"LB"},{name:"Saint Lucia",alpha2:"LC"},{name:"Liechtenstein",alpha2:"LI"},{name:"Sri Lanka",alpha2:"LK"},{name:"Liberia",alpha2:"LR"},{name:"Lesotho",alpha2:"LS"},{name:"Lithuania",alpha2:"LT"},{name:"Luxembourg",alpha2:"LU"},{name:"Latvia",alpha2:"LV"},{name:"Libyan Arab Jamahiriya",alpha2:"LY"},{name:"Morocco",alpha2:"MA"},{name:"Monaco",alpha2:"MC"},{name:"Moldova, Republic of",alpha2:"MD"},{name:"Montenegro",alpha2:"ME"},{name:"Saint Martin",alpha2:"MF"},{name:"Madagascar",alpha2:"MG"},{name:"Marshall Islands",alpha2:"MH"},{name:"Macedonia",alpha2:"MK"},{name:"Mali",alpha2:"ML"},{name:"Myanmar",alpha2:"MM"},{name:"Mongolia",alpha2:"MN"},{name:"Macao",alpha2:"MO"},{name:"Northern Mariana Islands",alpha2:"MP"},{name:"Martinique",alpha2:"MQ"},{name:"Mauritania",alpha2:"MR"},{name:"Montserrat",alpha2:"MS"},{name:"Malta",alpha2:"MT"},{name:"Mauritius",alpha2:"MU"},{name:"Maldives",alpha2:"MV"},{name:"Malawi",alpha2:"MW"},{name:"Mexico",alpha2:"MX"},{name:"Malaysia",alpha2:"MY"},{name:"Mozambique",alpha2:"MZ"},{name:"Namibia",alpha2:"NA"},{name:"New Caledonia",alpha2:"NC"},{name:"Niger",alpha2:"NE"},{name:"Norfolk Island",alpha2:"NF"},{name:"Nigeria",alpha2:"NG"},{name:"Nicaragua",alpha2:"NI"},{name:"Netherlands",alpha2:"NL"},{name:"Norway",alpha2:"NO"},{name:"Nepal",alpha2:"NP"},{name:"Nauru",alpha2:"NR"},{name:"Niue",alpha2:"NU"},{name:"New Zealand",alpha2:"NZ"},{name:"Oman",alpha2:"OM"},{name:"Panama",alpha2:"PA"},{name:"Peru",alpha2:"PE"},{name:"French Polynesia",alpha2:"PF"},{name:"Papua New Guinea",alpha2:"PG"},{name:"Philippines",alpha2:"PH"},{name:"Pakistan",alpha2:"PK"},{name:"Poland",alpha2:"PL"},{name:"Saint Pierre and Miquelon",alpha2:"PM"},{name:"Pitcairn",alpha2:"PN"},{name:"Puerto Rico",alpha2:"PR"},{name:"Palestinian Territory",alpha2:"PS"},{name:"Portugal",alpha2:"PT"},{name:"Palau",alpha2:"PW"},{name:"Paraguay",alpha2:"PY"},{name:"Qatar",alpha2:"QA"},{name:"Reunion",alpha2:"RE"},{name:"Romania",alpha2:"RO"},{name:"Serbia",alpha2:"RS"},{name:"Russian Federation",alpha2:"RU"},{name:"Rwanda",alpha2:"RW"},{name:"Saudi Arabia",alpha2:"SA"},{name:"Solomon Islands",alpha2:"SB"},{name:"Seychelles",alpha2:"SC"},{name:"Sudan",alpha2:"SD"},{name:"Sweden",alpha2:"SE"},{name:"Singapore",alpha2:"SG"},{name:"Saint Helena",alpha2:"SH"},{name:"Slovenia",alpha2:"SI"},{name:"Svalbard and Jan Mayen",alpha2:"SJ"},{name:"Slovakia",alpha2:"SK"},{name:"Sierra Leone",alpha2:"SL"},{name:"San Marino",alpha2:"SM"},{name:"Senegal",alpha2:"SN"},{name:"Somalia",alpha2:"SO"},{name:"Suriname",alpha2:"SR"},{name:"South Sudan",alpha2:"SS"},{name:"Sao Tome and Principe",alpha2:"ST"},{name:"El Salvador",alpha2:"SV"},{name:"Sint Maarten",alpha2:"SX"},{name:"Syrian Arab Republic",alpha2:"SY"},{name:"Swaziland",alpha2:"SZ"},{name:"Turks and Caicos Islands",alpha2:"TC"},{name:"Chad",alpha2:"TD"},{name:"French Southern Territories",alpha2:"TF"},{name:"Togo",alpha2:"TG"},{name:"Thailand",alpha2:"TH"},{name:"Tajikistan",alpha2:"TJ"},{name:"Tokelau",alpha2:"TK"},{name:"Timor-Leste",alpha2:"TL"},{name:"Turkmenistan",alpha2:"TM"},{name:"Tunisia",alpha2:"TN"},{name:"Tonga",alpha2:"TO"},{name:"Turkey",alpha2:"TR"},{name:"Trinidad and Tobago",alpha2:"TT"},{name:"Tuvalu",alpha2:"TV"},{name:"Taiwan",alpha2:"TW"},{name:"Tanzania, United Republic of",alpha2:"TZ"},{name:"Ukraine",alpha2:"UA"},{name:"Uganda",alpha2:"UG"},{name:"United States Minor Outlying Islands",alpha2:"UM"},{name:"United States",alpha2:"US"},{name:"Uruguay",alpha2:"UY"},{name:"Uzbekistan",alpha2:"UZ"},{name:"Holy See (Vatican City State)",alpha2:"VA"},{name:"Saint Vincent and the Grenadines",alpha2:"VC"},{name:"Venezuela",alpha2:"VE"},{name:"Virgin Islands, British",alpha2:"VG"},{name:"Virgin Islands, U.S.",alpha2:"VI"},{name:"Vietnam",alpha2:"VN"},{name:"Vanuatu",alpha2:"VU"},{name:"Wallis and Futuna",alpha2:"WF"},{name:"Samoa",alpha2:"WS"},{name:"Yemen",alpha2:"YE"},{name:"Mayotte",alpha2:"YT"},{name:"South Africa",alpha2:"ZA"},{name:"Zambia",alpha2:"ZM"},{name:"Zimbabwe",alpha2:"ZW"}];

//easy antixss
function x(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

// like PHP time()
function time_seconds(){
    return Math.ceil(new Date().getTime() / 1000);
}

//achat ripped links
function openEx(url)
{
    w = window.open();
    w.document.write('<meta http-equiv="refresh" content="0;url=' + url + '">');
    w.document.close();
    return false;
}

//set favorites cookie
function setFavs(){
        $.getJSON(rel_url+"user/json_favs", function(data){
            $.cookie("sl_favs", data, { path: '/'})
        });
}

//set classes and inner html to fav btns
function renderFavs() {
        $(".btn-fav").each(function() {
            if ($.cookie("sl_favs").indexOf(this.title) != -1) {
                this.className = "btn btn-fav btn-fav-del"
                $(this).html('<img class="like-icon" src="'+rel_url+'images/icons/like.png"> fav');
            } else {
                this.className = "btn btn-fav btn-fav-add"
                $(this).html('<img class="like-icon" src="'+rel_url+'images/icons/dislike.png"> fav');
            }
        });
}

$(document).ready(function(){

    $("#back").click(function(){
        history.back();
    });

    $('div.script-box').click(function(){
        $(this).find('.hidden').show();
    });

    $('span.safe-link').click(function(){
        openEx(this.title);
    });

    $('#toggle-help').click(function(){
        $('#help').toggle();
    });

    $('#toggle-scripts').click(function(){
        $('div.script-box').each(function(){
            $(this).find('pre.hidden').toggle();
        });
    });
    
    $('img.flag').each(function(){
        code = this.title.toUpperCase();
        $.each(country_codes, function(i, val){
                if (val.alpha2 == code ) {
                        name = val.name;
                        return false;
                }  
        });
        $(this).after('<span> '+name+'</span>'); 
    });

    $('span.ip-search').click(function(){
        window.open(rel_url+"search?q=ip:"+encodeURIComponent(this.innerHTML));
    });

    // For targets in user/panel
    $("#open-targets").click(function(){
            $(this).hide();
            $("#target-form").show(1000);
    });


    // Get favorites and set cookie
    if ($.cookie("sl_login") == "true" && !$.cookie("sl_favs")) {
        setFavs();
    }

    if ($.cookie("sl_favs")) {
        renderFavs();
    }

    $(document).on("click", '.btn-fav-add', function() {
        report_id = this.title;
        $.post(rel_url+"user/add_fav", {id: report_id, token:token}).done(function(data) {
            if (data != "11") {
                alert("something went wrong");
            } else {
                $('#'+report_id).find('span.rating').html(function(){
                    new_rating = parseInt($(this).html()) + 1;
                    $(this).html(new_rating);
                });
                $('#'+report_id).find('.btn-fav').removeClass("btn-fav-add").addClass("btn-fav-del")
                    .html('<img class="like-icon" src="'+rel_url+'images/icons/like.png"> fav');

            }
        });

        setTimeout(function(){
            setFavs();
        }, 100);

    });

    $(document).on("click",'.btn-fav-del', function() {
        report_id = this.title;
        $.post(rel_url+"user/del_fav", {id: report_id, token:token}).done(function(data) {
            if (data != "11") {
                alert("something went wrong");
            } else {
                $('#'+report_id).find('span.rating').html(function(){
                    new_rating = parseInt($(this).html()) - 1;
                    $(this).html(new_rating);
                });
                $('#'+report_id).find('.btn-fav').removeClass("btn-fav-del").addClass("btn-fav-add")
                    .html('<img class="like-icon" src="'+rel_url+'images/icons/dislike.png"> fav');
            }
        });

        setTimeout(function(){
            setFavs();
        }, 100);

    });
});
