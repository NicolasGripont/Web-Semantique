var liste = [
    "Vin rouge",
    "Vin Rosé",
    "Vin blanc",
    "Vinasse",
    "Cote du rhone"
];

$(document).ready(function(){
	
    $('#search_bar').autocomplete({
    source : liste
    });
});


