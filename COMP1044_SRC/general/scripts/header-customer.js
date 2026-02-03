window.addEventListener('DOMContentLoaded', () => {
    loadNotificationCount();
    setupHeaderNavigation();
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


function navigateTo(page) {
    const currentPage = getCurrentPage();
    if (currentPage !== page) {
        // Clean up any modal state before navigating
        localStorage.removeItem('openModalType');
        localStorage.removeItem('openModalCustomerID');
        localStorage.removeItem('openModalLeadID');
        localStorage.removeItem('openModalUserID');
        
        // Remove any overlay elements that might exist
        const overlays = document.querySelectorAll('div[style*="position: fixed"][style*="background-color: rgba(0, 0, 0, 0.5)"]');
        overlays.forEach(overlay => {
            if (document.body.contains(overlay)) {
                document.body.removeChild(overlay);
            }
        });
        
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
        
        // Now navigate to the requested page
        window.location.href = page;
    }
}


function setupHeaderNavigation() {
    // Target direct header elements
    const headerButtons = document.querySelectorAll('.header-button, .header a, .bell-icon, .logout-container');
    
    headerButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            
            if (this.hasAttribute('onclick') && this.getAttribute('onclick').includes('navigateTo')) {
                return;
            }
            
            // For elements with href attributes, prevent default and handle navigation manually
            if (this.hasAttribute('href')) {
                const href = this.getAttribute('href');
                if (href && !href.startsWith('#') && !href.includes('javascript:')) {
                    event.preventDefault();
                    
                    // Clear modal state
                    localStorage.removeItem('openModalType');
                    localStorage.removeItem('openModalCustomerID');
                    localStorage.removeItem('openModalLeadID');
                    localStorage.removeItem('openModalUserID');
                    
                    // Remove overlays
                    const overlays = document.querySelectorAll('div[style*="position: fixed"][style*="background-color: rgba(0, 0, 0, 0.5)"]');
                    overlays.forEach(overlay => {
                        if (document.body.contains(overlay)) {
                            document.body.removeChild(overlay);
                        }
                    });
                    
                    // Navigate
                    window.location.href = href;
                }
            }
        });
    });
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
                        <div class="header-button" onclick="navigateTo('salesrep-hp.php')">
                            <a>
                                <img class="home-icon" src="../general/dashboard-images/customer-icon.png">
                            </a>
                            <span class="menu-label">Customer Management</span>
                        </div>
                        <div class="header-button" onclick="navigateTo('salesrep-lead.php')">
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
                        <a class="bell-icon" href="../Notifications/redirectNotifications.php" onclick="clearModalData(); return !window.location.pathname.endsWith('notifications.php')">
                            <img class="bell-img" src="../general/menu-images/bell-icon.png">
                            <div class="notifications-count"></div>
                        </a>
                        <a href="../logout.php" class="logout-container" onclick="clearModalData()">
                            <img class="logout-icon" src="../general/menu-images/logout-icon.png">
                        </a>
                    </div>
            </div>
`;
