function removeAllOverlays() {
    const overlays = document.querySelectorAll('div[style*="position: fixed"][style*="background-color: rgba(0, 0, 0, 0.5)"]');
    overlays.forEach(overlay => {
        if (document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
    });
}

function clearModalData() {
    localStorage.removeItem('openModalType');
    localStorage.removeItem('openModalCustomerID');
    localStorage.removeItem('openModalLeadID');
    localStorage.removeItem('openModalUserID');
}

function checkAndReopenModals() {
    const modalType = localStorage.getItem('openModalType');
    const userID = localStorage.getItem('openModalUserID');
    
    removeAllOverlays();
    
    if (modalType === 'customer') {
        const customerID = localStorage.getItem('openModalCustomerID');
        if (customerID && userID) {
            showCustomerProfileModal(userID, customerID);
        }
    } else if (modalType === 'lead') {
        const leadID = localStorage.getItem('openModalLeadID');
        if (leadID && userID) {
            showLeadProfileModal(userID, leadID);
        }
    }
}

function setupNavigationLinkHandlers() {
    // Target ALL header navigation elements
    const navElements = document.querySelectorAll(
        // Header buttons
        '.header-button, .header a, .header-left a, .header-right a, ' +
        // Navigation links
        'nav a, .navigation a, .nav-link, ' +
        // Specific elements
        '.bell-icon, .logout-container, .back-button a'
    );
    
    navElements.forEach(element => {
        element.addEventListener('click', function(event) {
            // Close any open modals
            const customerModal = document.querySelector('.customer-profile-modal.show');
            const leadModal = document.querySelector('.lead-profile-modal.show');
            const otherModals = document.querySelector('.modal.show');
            
            if (customerModal) {
                customerModal.classList.remove('show');
            }
            
            if (leadModal) {
                leadModal.classList.remove('show');
            }
            
            if (otherModals) {
                otherModals.classList.remove('show');
            }
            
            removeAllOverlays();
            
            clearModalData();
        });
    });
}


function navigateTo(page) {
    const currentPage = getCurrentPage();
    if (currentPage !== page) {

        localStorage.removeItem('openModalType');
        localStorage.removeItem('openModalCustomerID');
        localStorage.removeItem('openModalLeadID');
        localStorage.removeItem('openModalUserID');
        
        const overlays = document.querySelectorAll('div[style*="position: fixed"][style*="background-color: rgba(0, 0, 0, 0.5)"]');
        overlays.forEach(overlay => {
            if (document.body.contains(overlay)) {
                document.body.removeChild(overlay);
            }
        });
        
        window.location.href = page;
    }
}


function showModal(table, id) {
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; 
    overlay.style.zIndex = '999'; 
    
    document.body.appendChild(overlay);

    modal = document.querySelector(".modal");
    modal.classList.add("show");

    document.querySelector(".cancel-button").addEventListener("click", () => {
        if (table == "customer") {
            window.location.href = "salesrep-hp.php";
        } else {
            window.location.href = "salesrep-lead.php";
        }
        modal.classList.remove("show");
        document.body.removeChild(overlay);
    });

    document.querySelector(".delete-button").addEventListener("click", () => {
        fetch("delete.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "deleteID=" + id + "&table=" + table
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); //debug purpose
            document.querySelector(".modal").classList.remove("show");
            document.body.removeChild(overlay);
            if (table == "customer") {
                window.location.href = "salesrep-hp.php"; 
            } else {
                window.location.href = "salesrep-lead.php"; 
            }
        })
        .catch(error => {
            console.error("Error:", error); 
        });
    });
}

function showUpdateRestrictModal() {
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; 
    overlay.style.zIndex = '999'; 
    
    document.body.appendChild(overlay);

    modal = document.querySelector(".modal");
    modal.classList.add("show");

    document.querySelector(".cancel-button").addEventListener("click", () => { 
        modal.classList.remove("show");
        document.body.removeChild(overlay);
    });
}

function showUserInfoModal(userID) {
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; 
    overlay.style.zIndex = '999'; 
    
    document.body.appendChild(overlay);

    modal = document.querySelector(".modal");
    modal.classList.add("show");

    fetch("getUserData.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "userID=" + userID
    })
    .then(response => response.json()) 
    .then(data => {
        document.querySelector(".user-info-block").innerHTML = `
            <p class="user-information-header">User Information</p>
            <div class="user-details"
            <p><span class="section">Username: </span> ${data.username}</p>
            <p><span class="section">Name: </span> ${data.name}</p>
            <p><span class="section">Email: </span> ${data.email}</p>
            <p><span class="section">Phone: </span> ${data.phone}</p></div>`
        ;
    })
    .catch(error => {
        console.error("Error:", error);
    });

    document.querySelector(".cancel-button").addEventListener("click", () => { 
        modal.classList.remove("show");
        document.body.removeChild(overlay);
    });
}


function showCustomerProfileModal(userID, customerID) {
    removeAllOverlays();
    
    // Save the currently open modal data in localStorage
    localStorage.setItem('openModalType', 'customer');
    localStorage.setItem('openModalCustomerID', customerID);
    localStorage.setItem('openModalUserID', userID);

    const overlay = document.createElement('div');
    overlay.id = 'modal-overlay';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; 
    overlay.style.zIndex = '999'; 
    
    document.body.appendChild(overlay);

    const modal = document.querySelector(".customer-profile-modal");
    
    // Set the inner HTML of the modal
    modal.innerHTML = `
        <div class="modal-header">
            <p class="customer-name"></p>
            <button class="close-modal-btn">&times;</button>
        </div>
        <div class="customer-info">
            <p class="customer-company"></p>
            <div class="customer-data-wrapper">
                <div class="customer-phone-block">
                    <img class="info-icon" src="../general/cust-lead-profile-images/phone-icon.png">
                    <p class="customer-phone"></p>
                </div>
                <form action="salesrep-hp.php" method="POST">
                <input type="hidden" name="returnToModal" value="customer-${customerID}-${userID}">
                    <button type="submit" name="customerUpdateID" value="${customerID}" class="viewButton">Update Profile</button>
                </form>
            </div>
            <div class="customer-data-wrapper">
                <div class="customer-email-block">
                    <img class="info-icon" src="../general/cust-lead-profile-images/email-icon.png">
                    <p class="customer-email"></p>
                </div>
            </div>
        </div>
        <div class="tabs-container">
            <div class="tabs-navigation">
                <div class="tab-group active" data-tab="interactions">
                    <div class="tab-inner">
                        <img src="../general/cust-lead-profile-images/chat-icon.png" class="tab-icon">
                        <div class="tab-text">Recent Interactions</div>
                    </div>
                </div>
                <div class="tab-group" data-tab="reminders">
                    <div class="tab-inner">
                        <img src="../general/cust-lead-profile-images/bell-icon.png" class="tab-icon">
                        <div class="tab-text">Reminders</div>
                    </div>
                </div>
            </div>
            <!-- Tab content -->
            <div class="tab-content" id="interactions-content">
                <div class="customer-interaction-block">
                </div>
            </div>
            <div class="tab-content hidden" id="reminders-content">
                <div class="customer-reminders-block">
                </div>
            </div>
        </div>
    `;
    
    // Show the modal
    modal.classList.add("show");
    
    // Fetch customer details
    fetch("getCustomerData.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "customerID=" + customerID
    })
    .then(response => response.json())
    .then(data => {
        // Update customer info in the modal
        document.querySelector(".customer-name").textContent = data.name;
        document.querySelector(".customer-company").textContent = data.company;
        document.querySelector(".customer-phone").textContent = data.phone_num;
        document.querySelector(".customer-email").textContent = data.email;
    })
    .catch(error => {
        console.error("Error fetching customer data:", error);
        document.querySelector(".customer-name").textContent = "Error loading data";
    });

    fetch("getCustomerInteractions.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "customerID=" + customerID
    })
    .then(response => response.json())
    .then(interactions => {
        const interactionsContainer = document.querySelector(".customer-interaction-block");
        
        if (interactions.length === 0) {
            interactionsContainer.innerHTML = `<p>No interactions found for this customer.</p>`;
            interactionsContainer.innerHTML += `<form action='salesrep-hp.php' method='POST'>
                                    <input type="hidden" name="returnToModal" value="customer-${customerID}-${userID}">
                                    <button type='submit' name='customerIntrID' value=${customerID}  class='viewButton'>Click To Add New Interactions</button>
                                </form>`;
        } else {
            const recentInteractions = interactions.slice(0, 3);
            
            let interactionsHTML = ``;
            
            recentInteractions.forEach(interaction => {
                const date = new Date(interaction.date).toLocaleDateString();
                interactionsHTML += `
                    <div class="interaction-item">
                        <div class="interaction-header">
                            <span class="interaction-type">${interaction.interaction_type}</span>
                            <span class="interaction-date">${date}</span>
                        </div>
                        <div class="interaction-description">${interaction.description}</div>
                    </div>
                `;
            });
            interactionsHTML += `<form action='salesrep-hp.php' method='POST'>
                                    <input type="hidden" name="returnToModal" value="customer-${customerID}-${userID}">
                                    <button type='submit' name='customerIntrID' value=${customerID}  class='viewButton'>View All Interactions</button>
                                </form>`;
            interactionsContainer.innerHTML = interactionsHTML;
        }
    })
    .catch(error => {
        console.error("Error fetching interactions:", error);
        document.querySelector(".customer-interaction-block").innerHTML = 
            `<p>Error loading interactions.</p>`;
    });


     // Fetch customer reminders
     fetch("getCustomerReminders.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "customerID=" + customerID
    })
    .then(response => response.json())
    .then(reminders => {
        const remindersContainer = document.querySelector(".customer-reminders-block");
        
        if (reminders.length === 0) {
            remindersContainer.innerHTML = `<p>No reminders set for this customer.</p>`;
            remindersContainer.innerHTML += `<form action='salesrep-hp.php' method='POST'>
                                                <input type="hidden" name="returnToModal" value="customer-${customerID}-${userID}">
                                                <button type='submit' name='customerRemID' value=${customerID}  class='viewButton'>Click To Set Reminder</button>
                                            </form>`;
        } else {
            const recentReminders = reminders.slice(0, 3);
            
            let remindersHTML = ``;
            
            recentReminders.forEach(reminder => {
                const date = new Date(reminder.date).toLocaleDateString();
                remindersHTML += `
                    <div class="reminder-item">
                        <div class="reminder-header">
                            <span class="reminder-date">${date}</span>
                        </div>
                        <div class="reminder-description">${reminder.reminder}</div>
                    </div>
                `;
            });
            remindersHTML += `<form action='salesrep-hp.php' method='POST'>
                                <input type="hidden" name="returnToModal" value="customer-${customerID}-${userID}">
                                <button type='submit' name='customerRemID' value=${customerID}  class='viewButton'>Set Reminder</button>
                            </form>`;
            remindersContainer.innerHTML = remindersHTML;
        }
    })
    .catch(error => {
        console.error("Error fetching reminders:", error);
    });

    // Add click event handler for tab switching
    const setupTabs = () => {
        const tabGroups = document.querySelectorAll('.tab-group');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabGroups.forEach(tabGroup => {
            tabGroup.addEventListener('click', () => {
                // Remove active class from all tab groups
                tabGroups.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab group
                tabGroup.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(content => content.classList.add('hidden'));
                
                // Show the selected tab content
                const tabName = tabGroup.getAttribute('data-tab');
                document.getElementById(`${tabName}-content`).classList.remove('hidden');
            });
        });
    };
    
    setTimeout(setupTabs, 100);

    document.querySelector(".close-modal-btn").addEventListener("click", () => { 
        modal.classList.remove("show");
        if (document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
        clearModalData();
    });
}




function updateStatus(status) {
    document.querySelectorAll('.status-step').forEach(step => {
        step.classList.remove('active', 'completed');
    });

    // Convert status to lowercase and handle spaces for case-insensitive comparison
    const statusLower = status.toLowerCase().replace(/\s+/g, '-');
    
    // Define status values as they appear in data-status attributes
    const statusValues = ['new', 'in-progress', 'contacted', 'closed'];
    
    // Find the index of the current status
    const currentIndex = statusValues.indexOf(statusLower);

    if (currentIndex !== -1) {
        // Mark previous steps as completed
        for (let i = 0; i < currentIndex; i++) {
            const stepElement = document.querySelector(`.status-step[data-status="${statusValues[i]}"]`);
            if (stepElement) {
                stepElement.classList.add('completed');
            }
        }
        
        // Mark current step as active
        const currentStep = document.querySelector(`.status-step[data-status="${statusValues[currentIndex]}"]`);
        if (currentStep) {
            currentStep.classList.add('active');
        }
    }
}


function showLeadProfileModal(userID, leadID) {
    removeAllOverlays();
    
    localStorage.setItem('openModalType', 'lead');
    localStorage.setItem('openModalLeadID', leadID);
    localStorage.setItem('openModalUserID', userID);

    const overlay = document.createElement('div');
    overlay.id = 'modal-overlay';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; 
    overlay.style.zIndex = '999'; 
    
    document.body.appendChild(overlay);

    const modal = document.querySelector(".lead-profile-modal");
    
    modal.innerHTML = `
        <div class="modal-header">
            <p class="lead-name"></p>
            <button class="close-modal-btn">&times;</button>
        </div>
        <div class="lead-info">
            <p class="lead-company"></p>
            <div class="lead-data-wrapper">
                <div class="lead-phone-block">
                    <img class="info-icon" src="../general/cust-lead-profile-images/phone-icon.png">
                    <p class="lead-phone"></p>
                </div>
                <form action="salesrep-lead.php" method="POST">
                    <input type="hidden" name="returnToModal" value="lead-${leadID}-${userID}">
                    <button type="submit" name="leadUpdateID" value="${leadID}" class="viewButton">Update Profile</button>
                </form>
            </div>
            <div class="lead-data-wrapper">
                <div class="lead-email-block">
                    <img class="info-icon" src="../general/cust-lead-profile-images/email-icon.png">
                    <p class="lead-email"></p>
                </div>
            </div>
            <div class="lead-data-wrapper">
                <div class="lead-notes-block">
                    <img class="info-icon" src="../general/cust-lead-profile-images/notes-icon.png">
                    <p class="lead-notes"></p>
                </div>
            </div>

            <div class="status-tracker">
                <div class="status-step" data-status="new">
                    <div class="status-circle"></div>
                    <div class="status-label">New</div>
                </div>
                <div class="status-step" data-status="in-progress">
                    <div class="status-circle"></div>
                    <div class="status-label">In progress</div>
                </div>
                <div class="status-step" data-status="contacted">
                    <div class="status-circle"></div>
                    <div class="status-label">Contacted</div>
                </div>
                <div class="status-step" data-status="closed">
                    <div class="status-circle"></div>
                    <div class="status-label">Closed</div>
                </div>
            </div>

        </div>
        <div class="tabs-container">
            <div class="tabs-navigation">
                <div class="tab-group active" data-tab="interactions">
                    <div class="tab-inner">
                        <img src="../general/cust-lead-profile-images/chat-icon.png" class="tab-icon">
                        <div class="tab-text">Recent Interactions</div>
                    </div>
                </div>
                <div class="tab-group" data-tab="reminders">
                    <div class="tab-inner">
                        <img src="../general/cust-lead-profile-images/bell-icon.png" class="tab-icon">
                        <div class="tab-text">Reminders</div>
                    </div>
                </div>
            </div>
            <!-- Tab content -->
            <div class="tab-content" id="interactions-content">
                <div class="lead-interaction-block">
                </div>
            </div>
            <div class="tab-content hidden" id="reminders-content">
                <div class="lead-reminders-block">
                </div>
            </div>
        </div>
    `;
    
    modal.classList.add("show");
    
    fetch("getLeadData.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "leadID=" + leadID
    })
    .then(response => response.json())
    .then(data => {
        document.querySelector(".lead-name").textContent = data.name;
        document.querySelector(".lead-company").textContent = data.company;
        document.querySelector(".lead-phone").textContent = data.phone_num;
        document.querySelector(".lead-email").textContent = data.email;
        document.querySelector(".lead-notes").textContent = data.notes;
        updateStatus(data.status);
    })
    .catch(error => {
        console.error("Error fetching lead data:", error);
        document.querySelector(".lead-name").textContent = "Error loading data";
    });

    fetch("getLeadInteractions.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "leadID=" + leadID
    })
    .then(response => response.json())
    .then(interactions => {
        const interactionsContainer = document.querySelector(".lead-interaction-block");
        
        if (interactions.length === 0) {
            interactionsContainer.innerHTML = `<p>No interactions found for this lead.</p>`;
            interactionsContainer.innerHTML += `<form action='salesrep-lead.php' method='POST'>
                                                <input type="hidden" name="returnToModal" value="lead-${leadID}-${userID}">
                                                <button type='submit' name='leadIntrID' value=${leadID}  class='viewButton'>Click To Add New Interactions</button>
                                            </form>`;
        } else {
            const recentInteractions = interactions.slice(0, 3);
            
            let interactionsHTML = ``;
            
            recentInteractions.forEach(interaction => {
                const date = new Date(interaction.date).toLocaleDateString();
                interactionsHTML += `
                    <div class="interaction-item">
                        <div class="interaction-header">
                            <span class="interaction-type">${interaction.interaction_type}</span>
                            <span class="interaction-date">${date}</span>
                        </div>
                        <div class="interaction-description">${interaction.description}</div>
                    </div>
                `;
            });
            interactionsHTML += `<form action='salesrep-lead.php' method='POST'>
                                    <input type="hidden" name="returnToModal" value="lead-${leadID}-${userID}">
                                    <button type='submit' name='leadIntrID' value=${leadID}  class='viewButton'>View All Interactions</button>
                                </form>`;
            interactionsContainer.innerHTML = interactionsHTML;
        }
    })
    .catch(error => {
        console.error("Error fetching interactions:", error);
        document.querySelector(".lead-interaction-block").innerHTML = 
            `<p>Error loading interactions.</p>`;
    });


     fetch("getLeadReminders.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "leadID=" + leadID
    })
    .then(response => response.json())
    .then(reminders => {
        const remindersContainer = document.querySelector(".lead-reminders-block");
        
        if (reminders.length === 0) {
            remindersContainer.innerHTML = `<p>No reminders set for this lead.</p>`;
            remindersContainer.innerHTML += `<form action='salesrep-lead.php' method='POST'>
                                                <input type="hidden" name="returnToModal" value="lead-${leadID}-${userID}">
                                                <button type='submit' name='leadRemID' value=${leadID}  class='viewButton'>Set Reminder</button>
                                            </form>`;
        } else {
            const recentReminders = reminders.slice(0, 3);
            
            let remindersHTML = ``;
            
            recentReminders.forEach(reminder => {
                const date = new Date(reminder.date).toLocaleDateString();
                remindersHTML += `
                    <div class="reminder-item">
                        <div class="reminder-header">
                            <span class="reminder-date">${date}</span>
                        </div>
                        <div class="reminder-description">${reminder.reminder}</div>
                    </div>
                `;
            });
            remindersHTML += `<form action='salesrep-lead.php' method='POST'>
                                    <input type="hidden" name="returnToModal" value="lead-${leadID}-${userID}">
                                    <button type='submit' name='leadRemID' value=${leadID}  class='viewButton'>Set Reminder</button>
                                </form>`;
            remindersContainer.innerHTML = remindersHTML;
        }
    })
    .catch(error => {
        console.error("Error fetching reminders:", error);
    });

    const setupTabs = () => {
        const tabGroups = document.querySelectorAll('.tab-group');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabGroups.forEach(tabGroup => {
            tabGroup.addEventListener('click', () => {
                // Remove active class from all tab groups
                tabGroups.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab group
                tabGroup.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(content => content.classList.add('hidden'));
                
                // Show the selected tab content
                const tabName = tabGroup.getAttribute('data-tab');
                document.getElementById(`${tabName}-content`).classList.remove('hidden');
            });
        });
    };
    
    setTimeout(setupTabs, 100);

    document.querySelector(".close-modal-btn").addEventListener("click", () => { 
        modal.classList.remove("show");
        if (document.body.contains(overlay)) {
            document.body.removeChild(overlay);
        }
        clearModalData();
    });
}




// Function to check for and reopen modals on page load
function checkAndReopenModals() {
    const modalType = localStorage.getItem('openModalType');
    const userID = localStorage.getItem('openModalUserID');
    
    if (modalType === 'customer') {
        const customerID = localStorage.getItem('openModalCustomerID');
        if (customerID && userID) {
            showCustomerProfileModal(userID, customerID);
        }
    } else if (modalType === 'lead') {
        const leadID = localStorage.getItem('openModalLeadID');
        if (leadID && userID) {
            showLeadProfileModal(userID, leadID);
        }
    }
}



function setupNavigationLinkHandlers() {
    const navElements = document.querySelectorAll(
        // Header buttons
        '.header-button, .header a, .header-left a, .header-right a, ' +
        // Navigation links
        'nav a, .navigation a, .nav-link, ' +
        // Specific elements
        '.bell-icon, .logout-container, .back-button a, ' +
        // Any other clickable navigation element
        '[onclick*="location"], [onclick*="navigateTo"]'
    );
    
    navElements.forEach(element => {
        element.addEventListener('click', function(event) {
            // Close any open modals
            const customerModal = document.querySelector('.customer-profile-modal.show');
            const leadModal = document.querySelector('.lead-profile-modal.show');
            const otherModals = document.querySelector('.modal.show');
            
            if (customerModal) {
                customerModal.classList.remove('show');
            }
            
            if (leadModal) {
                leadModal.classList.remove('show');
            }
            
            if (otherModals) {
                otherModals.classList.remove('show');
            }
            
            // Remove overlay
            const overlays = document.querySelectorAll('div[style*="position: fixed"][style*="background-color: rgba(0, 0, 0, 0.5)"]');
            overlays.forEach(overlay => {
                if (document.body.contains(overlay)) {
                    document.body.removeChild(overlay);
                }
            });
            
            // Clear localStorage data to prevent modal reopening
            localStorage.removeItem('openModalType');
            localStorage.removeItem('openModalCustomerID');
            localStorage.removeItem('openModalLeadID');
            localStorage.removeItem('openModalUserID');
        });
    });
}





document.addEventListener('DOMContentLoaded', function() {
    // Initialize tab functionality
    const tabGroups = document.querySelectorAll('.tab-group');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabGroups.forEach(tabGroup => {
        tabGroup.addEventListener('click', () => {
            // Remove active class from all tab groups
            tabGroups.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab group
            tabGroup.classList.add('active');
            
            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Show the selected tab content
            const tabName = tabGroup.getAttribute('data-tab');
            document.getElementById(`${tabName}-content`).classList.remove('hidden');
        });
    });

    // Check URL parameters FIRST, as they have priority over localStorage
    const urlParams = new URLSearchParams(window.location.search);
    const returnToModal = urlParams.get('returnToModal');
     
    if (returnToModal) {
        // Format is "type-id-userid"
        const [type, id, userId] = returnToModal.split('-');
        
        if (type === 'customer') {
            showCustomerProfileModal(userId, id);
        } else if (type === 'lead') {
            showLeadProfileModal(userId, id);
        }
    } else {
        // Only check for localStorage modals if no URL parameter is present
        checkAndReopenModals();
    }

    // Set up navigation handlers for proper modal cleanup when using navigation
    setupNavigationLinkHandlers();
});