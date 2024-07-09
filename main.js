
window.onload = function () {

    // ***** Events *****
    document.querySelector("#selectCity").addEventListener("change", cityChanged);
    // ***** Start-up *****
    getAllItems();
    initStoresDropdown(); //calls function to load select values
};

// ***** Updates products view based on city dropdown *****
function cityChanged()
{
  console.log("Selected city changed!");
  let siteID = document.querySelector("#selectCity").value;
  // console.log("siteID is: " + siteID);

  let url = "bullseye/items/" + siteID;
    let method = "GET";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        //console.log(xhr.responseText);
        let resp = JSON.parse(xhr.responseText);
        if (resp.data) {
          console.log(resp.data);
          //buildTable(resp.data);
          storeCurrentCity(); //call to add city to session!
          buildCards(resp.data);
          //setDeleteUpdateButtonState(false);
        } else {
          alert(resp.error + "; status code: " + xhr.status);
        }
      }
    };
    xhr.open(method, url, true);
    xhr.send();
}

function storeCurrentCity() {
  console.log("Selected city changed!");
  let siteID = document.querySelector("#selectCity").value;
  // console.log("siteID is: " + siteID);

  let method = "POST";
  let data = new FormData();
  data.append('siteID', siteID);

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          console.log(xhr.responseText);
          // You can handle the response here if needed
      }
  };
  xhr.open(method, 'storeSiteID.php', true);
  xhr.send(data);
}

// ***** same previous function using fetch *****
function cityChanged2()
{
  console.log("Selected city changed!");
  let siteID = document.querySelector("#selectCity").value;
  console.log("siteID is: " + siteID);
 
  //let url = `bullseye/items?siteID=${siteID}`;
  let url = `bullseye/items/${siteID}`;
  let method = "GET";

  fetch(url)
  .then(response => {
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      return response.json();
  })
  .then(data => {
      // Process the JSON data
      if (data.data) {
          let resp = JSON.parse(xhr.responseText);
          buildTable(resp.data);
          //buildCards(data.data);
          //setDeleteUpdateButtonState(false);
      } else {
          alert(data.error);
      }
  })
  .catch(error => {
      console.error('There was a problem with the fetch operation:', error);
  });
}

// ***** Populates items for city dropdown *****
function initStoresDropdown(){
  console.log("initStoresDropdown executing on js ");
  let url = "bullseye/sites";
  let method = "GET";
  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          let resp = JSON.parse(xhr.responseText);
          //console.log("Received data is" + resp);
          if (resp.data !== null) {
              buildComboBox(resp.data);
          } else {
              alert(resp.error + " status code: " + xhr.status);
          }
      }
  };
  xhr.open(method, url, true);
  xhr.send();
}

// ***** Build html with info from db *****
function buildComboBox(text) {
  let arr = JSON.parse(text);
  let html = "";
  for (let i = 0; i < arr.length; i++) {
      let row = arr[i];
      html +=
          "<option value='" +
          row.siteID +
          "'>" +
          row.name +
          "</option>";
  }
  let selectElement = document.querySelector("select#selectCity");
  selectElement.innerHTML = html;
}

// ***** Gets all items to build table *****
function getAllItems() {
    //URL = "bullseye/items"
    let url = "bullseye/items";
    let method = "GET";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        //console.log(xhr.responseText);
        let resp = JSON.parse(xhr.responseText);
        if (resp.data) {
          //buildTable(resp.data);
          buildCards(resp.data);
          //setDeleteUpdateButtonState(false);
        } else {
          alert(resp.error + "; status code: " + xhr.status);
        }
      }
    };
    xhr.open(method, url, true);
    xhr.send();
  }

// ***** Build items table into itemsOutput div
function buildTable0(text) {
    //hideAllOutput();
    //showPlayersTable();
    console.log("Build table running, data is: ");
    let arr = JSON.parse(text); // get JS Objects
    console.log(arr);
    let html = "<table><tr><th>Item ID</th><th>Item Name</th></tr>";
    for (let i = 0; i < arr.length; i++) {
      let row = arr[i];
      html += "<tr>";
      html += "<td>" + row.itemID + "</td>";
      html += "<td>" + row.name + "</td>";
      //html += "<td>" + (row.vegetarian === 1 ? "Yes" : "No") + "</td>";
      //Add button to buy
      html += "<td><form action='addtoCart.php' method='post'>";
      html += "<input type='hidden' name='itemID' value='" + row.itemID + "'>";
      html += "<button type='submit' name='addItem'>";
      html += "<i class='fa-solid fa-bag-shopping' style='color: #74C0FC;'></i></button></form></td>"
      html += "</tr>";
    }
    html += "</table>";
    let theTable = document.querySelector("#itemsOutput");
    theTable.innerHTML = html;
  }

function buildCards1(text){
  let arr = JSON.parse(text);

  let html = "";
  for (let i=0; i< arr.length; i++){
    let row = arr[i];
    html += "<div class='card' style='width: 18rem;'>";
    html += "<img src='" + row.image_url + "' class='card-img-top' alt='" + row.name + "'>";
    html += "<div class='card-body'>";
    html += "<h5 class='card-title'>"+ row.name +"</h5>";
    html += "<p class='card-text'>" + row.description + "</p>";
    html += "<ul class='list-group list-group-flush'>";
    html += "<li class='list-group-item'>" + "Price " + row.retailPrice + "</li>";
    html += "<li class='list-group-item'>" + "Available " + row.quantity + "</li>";
    html += "</ul>";
    html += "<form action='addtoCart.php' method='post'>";
    html += "<input type='hidden' name='itemID' value='" + row.itemID + "'>";
    html += "<button type='submit' name='addItem'>";
    html += "<i class='fa-solid fa-bag-shopping' style='color: #74C0FC;'></i></button></form>";
    html += "</div></div>";
  }
  
  let container = document.querySelector("#cardsContainer");
  container.innerHTML = html;

}

// ***** Build a items table into itemsOutput div
function buildCards(text) {
    
let arr = JSON.parse(text); // get JS Objects
console.log(arr);

let container = document.querySelector("#itemsOutput");

for (let i = 0; i < arr.length; i++) {
  let row = arr[i];

  let card = document.createElement("div");
  card.classList.add("card");
  card.style.width = "18rem";

  let image = document.createElement("img");
  image.src = row.image_url;
  //console.log("Url of this image is: " + row.image_url);
  image.classList.add("card-img-top");
  image.alt = row.name;
  card.appendChild(image);

  let body = document.createElement("div");
  body.classList.add("card-body");

  let title = document.createElement("h5");
  title.classList.add("card-title");
  title.textContent = row.name;
  body.appendChild(title);

  let text = document.createElement("p");
  text.classList.add("card-text");
  text.textContent = "Available quantity: " + row.quantity + ", Price: $" + row.retailPrice;
  body.appendChild(text);

  let form = document.createElement("form");
  form.action = "addtoCart.php"; //addtoCart.php
  form.method = "post";

  let input = document.createElement("input");
  input.type = "hidden";
  input.name = "itemID";
  input.value = row.itemID;
  form.appendChild(input);

  // Convert the object to a JSON string because
  // value attribute expects a string
  let rowJSON = JSON.stringify(row);

  let input1 = document.createElement("input");
  input1.type = "hidden";
  input1.name = "row";
  input1.value = rowJSON;
  form.appendChild(input1);
  //console.log(row); //JSON object
  console.log(rowJSON); //JSON obj string
  
  let button = document.createElement("button");
  button.type = "submit";
  button.name = "addItem";
  button.classList.add("btn", "btn-primary");
  button.innerHTML = "<i class='fa-solid fa-bag-shopping' style='color: #fff;'></i> Add to Cart";
  form.appendChild(button);

  body.appendChild(form);
  card.appendChild(body);

  container.appendChild(card);
}  
}

// ***** Builds a items table into itemsOutput div
function buildCards3(text) {
  let arr = JSON.parse(text); // get JS Objects
  let cardsContainer = document.getElementById("cardsContainer");
  cardsContainer.innerHTML = ""; // Clear previous content
  
  for (let i = 0; i < arr.length; i++) {
    let item = arr[i];
    let card = document.createElement("div");
    card.classList.add("card");
    
    let cardBody = document.createElement("div");
    cardBody.classList.add("card-body");
    
    let title = document.createElement("h5");
    title.classList.add("card-title");
    title.innerText = item.name;
    
    let subtitle = document.createElement("h6");
    subtitle.classList.add("card-subtitle");
    subtitle.classList.add("mb-2");
    subtitle.classList.add("text-muted");
    subtitle.innerText = "Item ID: " + item.itemID;
    
    let description = document.createElement("p");
    description.classList.add("card-text");
    description.innerText = item.description;
    
    cardBody.appendChild(title);
    cardBody.appendChild(subtitle);
    cardBody.appendChild(description);
    
    card.appendChild(cardBody);
    
    cardsContainer.appendChild(card);
  }
}


