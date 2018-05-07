const countries = [
    { name: "Canada", continent: "North America", cities: ["Calgary","Montreal","Toronto"], photos: ["canada1.jpg","canada2.jpg","canada3.jpg"] },
    { name: "United States", continent: "North America", cities: ["Boston","Chicago","New York","Seattle","Washington"], photos: ["us1.jpg","us2.jpg"] },
    { name: "Italy", continent: "Europe", cities: ["Florence","Milan","Naples","Rome"], photos: ["italy1.jpg","italy2.jpg","italy3.jpg","italy4.jpg","italy5.jpg","italy6.jpg"] },
    { name: "Spain", continent: "Europe", cities: ["Almeria","Barcelona","Madrid"], photos: ["spain1.jpg","spain2.jpg"] }
];

for(let i = 0;i < countries.length;i++){
    let country = countries[i];
    displayCountry(country);
}

function displayCountry(country) {
    let item = document = document.createElement("div");
    let div = document.getElementsByClassName("flex-container justify")[0];
    div.appendChild(item);

    let country_h2 = document.createElement("h2");
    let continent_p = document.createElement("p");
    let innerBox1 = document.createElement("div");
    let cities_h3 = document.createElement("h3");
    let cities_ul = document.createElement("ul");
    let innerBox2 = document.createElement("div");
    let photos_h3 = document.createElement("h3");
    let visit_button = document.createElement("button");

    item.className = "item";
    innerBox1.className = "inner-box";
    innerBox2.className = "inner-box";

    item.appendChild(country_h2);
    item.appendChild(continent_p);
    item.appendChild(innerBox1);
    item.appendChild(innerBox2);
    item.appendChild(visit_button);

    country_h2.appendChild(document.createTextNode(country.name));
    continent_p.appendChild(document.createTextNode(country.continent));

    innerBox1.appendChild(cities_h3);
    innerBox1.appendChild(cities_ul);

    cities_h3.appendChild(document.createTextNode("Cities"));
    for(let j of country.cities){
        let cities_li = document.createElement("li");
        cities_li.appendChild(document.createTextNode(j));
        cities_ul.appendChild(cities_li);
    }

    innerBox2.appendChild(photos_h3);

    photos_h3.appendChild(document.createTextNode("Popular Photos"));
    for(let j of country.photos){
        let photo_img = document.createElement("img");
        photo_img.className = "photo";
        photo_img.src = "images/" + j;
        innerBox2.appendChild(photo_img);
    }

    visit_button.appendChild(document.createTextNode("Visit"));
}

