const menuButton = document.querySelector('.menu-button');
const menu = document.querySelector('.menu');
const header = document.querySelector('.header');

let MenuOpen = false;

menu.style.display = "none";

menuButton.addEventListener('click', () => {
    if (!MenuOpen) {
        slideDown();
        header.style.backgroundColor = "black";
        MenuOpen = true;
    } else {
        slideUp();
        header.style.backgroundColor = "#333333";
        MenuOpen = false;
    }
});

function slideUp() {
    menu.style.transition = "all 0.5s ease-in-out";
    menu.style.opacity = "0"; // Fade out
    setTimeout(() => {
        menu.style.display = "none"; // Hide after animation
    }, 500);
}

function slideDown() {
    menu.style.display = "flex"; // Show first
    menu.style.opacity = "0"; // Start from invisible
    setTimeout(() => {
        menu.style.transition = "all 0.5s ease-in-out";
        menu.style.opacity = "1"; 
    }, 10); 
}

function menuSearch() {
    const searchInput = document.querySelector('.menu-input input');
    const menuItems = document.querySelectorAll('.menu-item a');
    
    const searchText = searchInput.value.toLowerCase();
    
    menuItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        const menuItem = item.parentElement;
        
        if (text.includes(searchText)) {
            menuItem.style.display = 'block';
        } else {
            menuItem.style.display = 'none';
        }
    });
}
