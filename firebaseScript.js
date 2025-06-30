var FIREBASE_URL = "https://dispensadoriteligente-default-rtdb.firebaseio.com";
var DISPENSER_ID = "dispenser_001";

function onFormSubmit(e) {
  try {
    var userEmail = "";
    if (e && e.response && typeof e.response.getRespondentEmail === 'function') {
      userEmail = e.response.getRespondentEmail();
    }

    // Generar un timestamp
    var timestamp = new Date().toISOString();

    // Datos que se enviarán a Firebase
    var data = {
      "timestamp": timestamp,
      "status": "pending",
      "user_email": userEmail, 
 
    };


    var firebasePath = "/dispensers/" + DISPENSER_ID + "/dispense_requests.json";


    var options = {
      "method": "post", 
      "contentType": "application/json",
      "payload": JSON.stringify(data)
    };

    // Enviar la solicitud a Firebase
    var response = UrlFetchApp.fetch(FIREBASE_URL + firebasePath, options);
    Logger.log("Firebase response: " + response.getContentText());

  } catch (error) {
    Logger.log("Error en onFormSubmit: " + error.toString());
  }
}