//create xmlhttp to handling XMLHttpRequest object
var getXMLHttpObject = function() {
	var xmlHttp = null;
	if (window.XMLHttpRequest) {
//        for Firefox, Opera, Safari, Chrome
		xmlHttp = new XMLHttpRequest();
	} else {
//        for IE
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlHttp;
};

// Dialog modal
var dialogModal = function() {
	var divOverlay = document.getElementById("overlay");
	if (divOverlay.style.visibility == 'visible') {
		divOverlay.style.visibility = "hidden";
	} else {
		divOverlay.style.visibility = "visible";
		resetDialog();
	}
};
// add city on address form using ajax
var addCity = function() {
	var url = 'partial/add_city.php';
	var result = document.getElementById('message');
	var city_name = document.getElementById('city_name').value;
	if (city_name == '' || city_name.length == 0) {
		result.className = 'error';
		result.innerHTML = 'Please input city name!';
		return false;
	}
	var description = document.getElementById('description').value;
	xmlHttp = getXMLHttpObject();
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			var response = stringToJson(xmlHttp.responseText);
			console.log(response);
			if (response.status === true) {
				addOptionSelect(response.lastId, city_name);
				resetDialog();
			} else {
				result.className = 'error';
			}
			result.innerHTML = response.message;
		}
	};
	xmlHttp.open("POST", url, true);
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.send("city_name=" + city_name + "&" + "description=" + description);
};

// sorting data using ajax
var sortData = function(order_by) {
	var basic_url = "partial/sort_contact.php";
	var result = document.getElementById('content_data');
	var sort = document.getElementById('sort').value;
	var params = "?order_by=" + order_by + "&sort=" + sort + "&t=" + Math.random();
	var url = basic_url + params
	xmlHttp = getXMLHttpObject();
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			result.innerHTML = xmlHttp.responseText;
		}
	};
	xmlHttp.open("GET", url, true);
	xmlHttp.send();
};

// call back function to add more city to drop down list
var addOptionSelect = function(id, cityName) {
	var selectCity = document.getElementById('id_city');
	var newCity = document.createElement("option");
	newCity.text = cityName;
	newCity.value = id;
	selectCity.add(newCity);
};
// convert json string to object
var stringToJson = function(jsonString) {
	return JSON.parse(jsonString);
};
// reset all data on dialog add city
var resetDialog = function() {
	document.getElementById('message').innerHTML = '';
	document.getElementById('message').className = '';
	document.getElementById('fmAddCity').reset();
}

// confirm with user before delete anything
var deleteConfirm = function(urlDelete){
	var confirmMessage = 'Are you sure delete this item?';
	if(confirm(confirmMessage)){
		document.location.href=urlDelete;
	}else{
		return false;
	}
}
// display message response to user
var alertMessage = function(message){
	alert(message);
}
