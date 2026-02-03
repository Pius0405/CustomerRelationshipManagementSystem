// Function to get current page filename
function getCurrentPage() {
    return window.location.pathname.split('/').pop();
}

// Function to navigate only if not on the current page
function navigateTo(page) {
    const currentPage = getCurrentPage();
    if (currentPage !== page) {
        window.location.href = page;
    }
}

document.querySelector(".header").innerHTML = `
                <div class = "header-content">
                    <div class="header-left">
                        <div class="header-button" onclick="navigateTo('dashboard.php')">
                            <a>
                                <img class="home-icon" src="../general/menu-images/home-icon.png">
                            </a>
                            <span class="menu-label">Home</span>
                        </div>
                        <div class="header-button" onclick="navigateTo('admin.php')">
                            <a>
                                <img class="home-icon" src="../general/dashboard-images/customer-icon.png">
                            </a>
                            <span class="menu-label">User Management</span>
                        </div>
                        <div class="header-button" onclick="navigateTo('all-customers.php')">
                            <a>
                                <img class="home-icon" src="../general/dashboard-images/customer-icon.png">
                            </a>
                            <span class="menu-label">Customer Management</span>
                        </div>
                        <div class="header-button" onclick="navigateTo('all-leads.php')">
                            <a>
                                <img class="home-icon" src="../general/dashboard-images/lead-icon.png">
                            </a>
                            <span class="menu-label">Lead Management</span>
                        </div>
                    </div>

                    <div class="header-mid">      
                        <img class="crm-icon" src="../general/menu-images/hp-logo.png">
                    </div>

                    <div class="header-right">
                        <a href="../logout.php" class="logout-container">
                            <img class="logout-icon" src="../general/menu-images/logout-icon.png">
                        </a>
                    </div>
            </div>
`;

