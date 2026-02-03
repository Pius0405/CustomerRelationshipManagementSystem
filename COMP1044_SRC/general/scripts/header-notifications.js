window.addEventListener('DOMContentLoaded', () => {
    loadNotificationCount();
});
  
function loadNotificationCount() {
    fetch('../fetchNotificationsCount.php')
    .then(response => response.json())
    .then(data => {
      const count = data.count; 
      let notiArlert = document.querySelector(".notifications-count");
      notiArlert.innerHTML = `${count}`;
    if (count > 0) {
        notiArlert.style.opacity = '1';        
    } else {
        notiArlert.style.opacity = '0';
    }
    })
    .catch(error => {
      console.error('Error fetching notifications:', error);
    });
}

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
                        <div class="header-button" onclick="navigateTo('../SalesRepresentative/dashboard.php')">
                            <a>
                                <img class="home-icon" src="../general/menu-images/home-icon.png">
                            </a>
                            <span class="menu-label">Home</span>
                        </div>
                        <div class="header-button" onclick="navigateTo('../SalesRepresentative/salesrep-hp.php')">
                            <a>
                                <img class="home-icon" src="../general/dashboard-images/customer-icon.png">
                            </a>
                            <span class="menu-label">Customer Management</span>
                        </div>
                        <div class="header-button" onclick="navigateTo('../SalesRepresentative/salesrep-lead.php')">
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
                        <a class="bell-icon" href="../Notifications/redirectNotifications.php" onclick="return !window.location.pathname.endsWith('notifications.php')">
                            <img class="bell-img" src="../general/menu-images/bell-icon.png">
                            <div class="notifications-count"></div>
                        </a>
                        <a href="../logout.php" class="logout-container">
                            <img class="logout-icon" src="../general/menu-images/logout-icon.png">
                        </a>
                    </div>
            </div>
`;