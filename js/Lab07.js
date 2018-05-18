function select1Fun() {
    let select1 = document.getElementById("select1");
    let opt = select1.options[select1.selectedIndex].value;
    let select2 = document.getElementById("select2");
    let warning = document.getElementById("warning");
    let btn = document.getElementById("commit");
    let createTable = document.getElementById("createTable");

    if(opt == "1"){
        select2.style.display = "none";
        warning.style.display = "none";
        btn.style.display = "none";
        createTable.style.display = "none";
        
    }
    if(opt == "2"){
        select2.style.display = "none";
        warning.style.display = "none";
        btn.style.display = "inline";
        createTable.style.display = "block";
        f2();
    }
    if(opt == "3"){
        select2.style.display = "inline";
        warning.style.display = "none";
        btn.style.display = "inline";
        createTable.style.display = "none";
        f3();
    }
    if(opt == "4"){
        select2.style.display = "inline";
        warning.style.display = "none";
        btn.style.display = "inline";
        createTable.style.display = "none";
        f3();
    }
    if(opt == "5"){
        select2.style.display = "inline";
        warning.style.display = "block";
        btn.style.display = "inline";
        createTable.style.display = "none";
        f5();
    }
}

function btnClick() {
    let opt = select1.options[select1.selectedIndex].value;
    if(opt == "2"){
        createTable();
    }
    if(opt == "3"){
        addRow();
    }
    if(opt == "4"){
        deleteRow();
    }
    if(opt == "5"){
        deleteTable();
    }
}

let tableArray = [];    //object

function removeAttrs() {    //remove the attrs already exist
    let attrs = document.getElementById("attrs");
    while(attrs.hasChildNodes()){
        attrs.removeChild(attrs.firstChild);
    }
}

function colNumChange() {
    let n = document.getElementById("columnsNum").value;
    createAttrs(n);
}

function createAttrs(n) {
    let attrs = document.getElementById("attrs");
    removeAttrs();
    for(let i = 0;i < n;i++){
        let attr = document.createElement("input");
        attr.placeholder = "Attri" + (i + 1);
        attrs.appendChild(attr);
    }
}

function f2() {     //create table
    removeAttrs();
}

function f3() {     //add row
    removeAttrs();
    let select2 = document.getElementById("select2");
    let optn = select2.selectedIndex;
    if(optn == 0){
        return;
    }
    let n = tableArray[optn - 1].colNum;
    createAttrs(n);
}

function f5() {     //delete table
    removeAttrs();
    let select2 = document.getElementById("select2");
    let optn = select2.selectedIndex;
    if(optn == 0){
        return;
    }
}

function createTable() {
    let attrs = document.getElementById("attrs");
    let tables = document.getElementById("tables");

    let num = tableArray.length;
    for(let i = 0;i < num;i++){
        tables.getElementsByTagName("table")[i].style.display = "none";
    }

    let n = document.getElementById("columnsNum").value;
    let table = new Table(document.getElementsByName("tableName")[0].value,n);
    tableArray.push(table);     //add to tableArray
    tables.appendChild(table.newTable);
    let tr = document.createElement("tr");
    table.newTable.appendChild(tr);
    for(let i = 0;i < n;i++){
        let th = document.createElement("th");
        let text = document.createTextNode(attrs.getElementsByTagName("input")[i].value);
        th.appendChild(text);
        tr.appendChild(th);
    }
    let select2 = document.getElementById("select2");
    select2.style.display = "inline";
    let optn = document.createElement("option");
    optn.innerHTML = document.getElementsByName("tableName")[0].value;
    select2.appendChild(optn);
    optn.selected = true;
}

function addRow() {
    let select2 = document.getElementById("select2");
    let optn = select2.selectedIndex;
    let selectedTable = tableArray[optn - 1];
    let n = selectedTable.colNum;
    let createTr = document.createElement("tr");
    let selectedT = selectedTable.newTable;
    let attrs = document.getElementById("attrs");
    selectedT.appendChild(createTr);
    for(let i = 0;i < n;i++){
        let createTd = document.createElement("td");
        createTr.appendChild(createTd);
        createTd.innerHTML = attrs.getElementsByTagName("input")[i].value;
    }
    selectedTable.rowNum++;
    selectedTable.trColor();
}

function deleteRow() {
    let select2 = document.getElementById("select2");
    let optn = select2.selectedIndex;
    let selectedTable = tableArray[optn - 1];
    let n = selectedTable.colNum;
    let createTr = document.createElement("tr");
    let selectedT = selectedTable.newTable;
    let attrs = document.getElementById("attrs");

    let flag = 0;
    for(let j = 1;j < selectedTable.rowNum;j++){
        for(let i = 0;i < n;i++){
            if(attrs.getElementsByTagName("input")[i].value === selectedT.getElementsByTagName("tr")[j].getElementsByTagName("td")[i].innerHTML){
                flag = 1;
            }
            else{
                flag = 0;
                break;
            }
        }
        if(flag == 1){
            selectedTable.newTable.deleteRow(j);
            selectedTable.rowNum--;
            selectedTable.trColor();
            break;
        }
    }
}

function deleteTable() {
    let select2 = document.getElementById("select2");
    let tables = document.getElementById("tables");
    let optn = select2.selectedIndex;
    if(optn == 0){
        return;
    }
    select2.removeChild(select2.getElementsByTagName("option")[optn]);
    tables.removeChild(tables.getElementsByTagName("table")[optn - 1]);
    tableArray.splice(optn - 1);
    delete tables.getElementsByTagName("table")[optn];
    select1Fun();
}

function changeSelect2() {
    let select2 = document.getElementById("select2");
    let optn = select2.selectedIndex;
    let table = tableArray[optn];   //
    let tables = document.getElementById("tables");
    let num = tableArray.length;
    if (optn == -1){    //have no table yet
        return;
    }

    for (let i = 0;i < num;i++){
        tables.getElementsByTagName("table")[i].style.display = "none";     //conceal all tables
    }
    tables.getElementsByTagName("table")[optn - 1].style.display = "inline";    //display selected table
    select1Fun();
}

window.onload = function () {
    select1Fun();
}

function Table(tableName,colNum) {
    this.name = tableName;
    this.rowNum = 1;    //th
    this.colNum = colNum;
    this.newTable = document.createElement("table");

    this.trColor = function () {    //special color
        let num = this.rowNum;
        for(let k = 1;k < num;k++){
            if(k % 2 == 0){
                this.newTable.getElementsByTagName("tr")[k].className = "";
                this.newTable.getElementsByTagName("tr")[k].className = "special";
            }
            if(k % 2 == 1){
                this.newTable.getElementsByTagName("tr")[k].className = "";
                this.newTable.getElementsByTagName("tr")[k].className = "normal";
            }
        }
    }
}

