function filterCustomers() {
    let searchInput = document.querySelector("#searchInput");
    let inputValue = searchInput.value.trim().toLowerCase();
    let targetTable = document.querySelector("#customerTable");
    let rows = targetTable.querySelectorAll("tbody tr");
    rows.forEach(
        row => {
            let cells = row.querySelectorAll("td");
            let name = cells[1].textContent.trim().toLowerCase();
            let company = cells[2].textContent.trim().toLowerCase();
            let email = cells[3].textContent.trim().toLowerCase();
            let phone= cells[4].textContent.trim().toLowerCase();

            if (name.indexOf(inputValue) > -1 || 
            company.indexOf(inputValue) > -1 || 
            email.indexOf(inputValue) > -1 || 
            phone.indexOf(inputValue) > -1) {
            row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    );
}

function filterLeads() {
    let searchInput = document.querySelector("#searchInput");
    let inputValue = searchInput.value.trim().toLowerCase();
    let targetTable = document.querySelector("#customerTable");
    let rows = targetTable.querySelectorAll("tbody tr");
    rows.forEach(
        row => {
            let cells = row.querySelectorAll("td");
            let name = cells[1].textContent.trim().toLowerCase();
            let company = cells[2].textContent.trim().toLowerCase();
            let email = cells[3].textContent.trim().toLowerCase();
            let phone= cells[4].textContent.trim().toLowerCase();
            let status = cells[5].textContent.trim().toLowerCase();
            let notes= cells[6].textContent.trim().toLowerCase();

            if (name.indexOf(inputValue) > -1 || 
            company.indexOf(inputValue) > -1 || 
            email.indexOf(inputValue) > -1 || 
            phone.indexOf(inputValue) > -1 ||
            status.indexOf(inputValue) > -1 ||
            notes.indexOf(inputValue) > -1) {
            row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    );
}

function filterInteractions() {
    let searchInput = document.querySelector("#searchInput");
    let inputValue = searchInput.value.trim().toLowerCase();
    let targetTable = document.querySelector("#customerTable");
    let rows = targetTable.querySelectorAll("tbody tr");
    rows.forEach(
        row => {
            let cells = row.querySelectorAll("td");
            let interactionType = cells[1].textContent.trim().toLowerCase();
            let description = cells[2].textContent.trim().toLowerCase();
            let date = cells[3].textContent.trim().toLowerCase();

            if (interactionType.indexOf(inputValue) > -1 ||
            description.indexOf(inputValue) > -1 ||
            date.indexOf(inputValue) > -1) {
            row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    );
}

function filterUsers() {
    let searchInput = document.querySelector("#searchInput");
    let inputValue = searchInput.value.trim().toLowerCase();
    let targetTable = document.querySelector("#customerTable");
    let rows = targetTable.querySelectorAll("tbody tr");
    rows.forEach(
        row => {
            let cells = row.querySelectorAll("td");
            let role = cells[1].textContent.trim().toLowerCase();
            let username = cells[2].textContent.trim().toLowerCase();
            let name = cells[3].textContent.trim().toLowerCase();
            let email = cells[4].textContent.trim().toLowerCase();
            let phone = cells[5].textContent.trim().toLowerCase();

            if (role.indexOf(inputValue) > -1 ||
            username.indexOf(inputValue) > -1 ||
            email.indexOf(inputValue) > -1 ||
            phone.indexOf(inputValue) > -1 ||
            name.indexOf(inputValue) > -1) {
            row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    );
}