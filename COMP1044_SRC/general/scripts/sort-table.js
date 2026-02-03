let currentSort = {
    column: 'name', 
    direction: 'asc'  
};

function sortTable(column, direction) {
    const table = document.getElementById('customerTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    let columnIndex;
    let dateColumn = false;
    
    const headers = Array.from(table.querySelectorAll('th')).map(th => 
        th.textContent.trim().toLowerCase());
    
    const isInteractionsTable = headers.includes('interaction type');
    const isLeadsTable = headers.includes('status');
    const isAdminTable = headers.includes('role') && headers.includes('username');
    
    // Set column index based on table type and column name
    if (isAdminTable) {
        switch(column) {
            case 'role':
                columnIndex = 1;
                break;
            case 'username':
                columnIndex = 2;
                break;
            case 'name':
                columnIndex = 3;
                break;
            case 'email':
                columnIndex = 4;
                break;
            case 'phone':
                columnIndex = 5;
                break;
            default:
                columnIndex = 3; 
        }
    } else if (isInteractionsTable) {
        switch(column) {
            case 'type':
                columnIndex = 1; // Interaction type
                break;
            case 'description':
                columnIndex = 2;
                break;
            case 'date':
                columnIndex = 3;
                dateColumn = true;
                break;
            default:
                columnIndex = 1; 
        }
    } else if (isLeadsTable) {
        switch(column) {
            case 'name':
                columnIndex = 1;
                break;
            case 'company':
                columnIndex = 2;
                break;
            case 'email':
                columnIndex = 3;
                break;
            case 'phone':
                columnIndex = 4;
                break;
            case 'status':
                columnIndex = 5;
                break;
            default:
                columnIndex = 1; 
        }
    } else {
        switch(column) {
            case 'name':
                columnIndex = 1;
                break;
            case 'company':
                columnIndex = 2;
                break;
            case 'email':
                columnIndex = 3;
                break;
            case 'phone':
                columnIndex = 4;
                break;
            default:
                columnIndex = 1; 
        }
    }
    
    currentSort.column = column;
    currentSort.direction = direction;
    
    // Sort rows
    rows.sort((a, b) => {
        if (a.querySelector('td[colspan]') || b.querySelector('td[colspan]')) {
            return 0;
        }
        
        if (a.cells.length <= columnIndex || b.cells.length <= columnIndex) {
            return 0;
        }
        
        let valueA = a.cells[columnIndex].textContent.trim();
        let valueB = b.cells[columnIndex].textContent.trim();
        
        if (dateColumn) {
            const dateA = new Date(valueA);
            const dateB = new Date(valueB);
            
            if (direction === 'asc') {
                return dateA - dateB;
            } else {
                return dateB - dateA;
            }
        } else {
            valueA = valueA.toLowerCase();
            valueB = valueB.toLowerCase();
            
            if (direction === 'asc') {
                return valueA.localeCompare(valueB);
            } else {
                return valueB.localeCompare(valueA);
            }
        }
    });
    
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    rows.forEach(row => tbody.appendChild(row));
    
    if (rows.length > 1 || 
        (rows.length === 1 && !rows[0].querySelector('td[colspan]'))) {
        updateRowNumbers();
    }
}

function updateRowNumbers() {
    const tbody = document.getElementById('customerTable').querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    
    rows.forEach((row, index) => {
        if (!row.querySelector('td[colspan]')) {
            const cell = row.cells[0];
            if (cell) {
                cell.textContent = index + 1;
            }
        }
    });
}

function handleSortChange(column) {
    sortTable(column, currentSort.direction);
}

function generateSortOptions() {
    const table = document.getElementById('customerTable');
    const headers = Array.from(table.querySelectorAll('th')).map(th => 
        th.textContent.trim().toLowerCase());
    
    const isInteractionsTable = headers.includes('interaction type');
    const isLeadsTable = headers.includes('status');
    const isAdminTable = headers.includes('role') && headers.includes('username');
    
    if (isAdminTable) {
        return `
            <select id="sortSelect" onchange="handleSortChange(this.value)">
                <option value="role">Role</option>
                <option value="username">Username</option>
                <option value="name">Name</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
            </select>
            <button id="toggleSortDirection" class="sort-direction-btn">↓</button>
        `;
    } else if (isInteractionsTable) {
        return `
            <select id="sortSelect" onchange="handleSortChange(this.value)">
                <option value="type">Interaction Type</option>
                <option value="description">Description</option>
                <option value="date">Date</option>
            </select>
            <button id="toggleSortDirection" class="sort-direction-btn">↓</button>
        `;
    } else if (isLeadsTable) {
        return `
            <select id="sortSelect" onchange="handleSortChange(this.value)">
                <option value="name">Name</option>
                <option value="company">Company</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="status">Status</option>
            </select>
            <button id="toggleSortDirection" class="sort-direction-btn">↓</button>
        `;
    } else {
        return `
            <select id="sortSelect" onchange="handleSortChange(this.value)">
                <option value="name">Name</option>
                <option value="company">Company</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
            </select>
            <button id="toggleSortDirection" class="sort-direction-btn">↓</button>
        `;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Add sort dropdown to table header
    const tableHeader = document.querySelector('.table-header');
    if (tableHeader) {
        const sortDropdown = document.createElement('div');
        sortDropdown.className = 'sort-dropdown';
        
        // Generate appropriate options for current table
        sortDropdown.innerHTML = generateSortOptions();
        
        tableHeader.appendChild(sortDropdown);
        
        // Add event listener for sort direction toggle button
        const toggleBtn = document.getElementById('toggleSortDirection');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                // Toggle direction
                const newDirection = currentSort.direction === 'asc' ? 'desc' : 'asc';
                currentSort.direction = newDirection;
                
                // Update button text
                this.textContent = newDirection === 'asc' ? '↓' : '↑';
                
                // Resort with new direction
                sortTable(currentSort.column, newDirection);
            });
        }
        
        // Detect table type for default sort
        const table = document.getElementById('customerTable');
        if (table) {
            const headers = Array.from(table.querySelectorAll('th')).map(th => 
                th.textContent.trim().toLowerCase());
            
            let defaultColumn = 'name';
            
            if (headers.includes('interaction type')) {
                defaultColumn = 'type';
                currentSort.column = 'type';
            } else if (headers.includes('role') && headers.includes('username')) {
                defaultColumn = 'role';
                currentSort.column = 'role';
            }
            
            // Default sort
            sortTable(currentSort.column, 'asc');
        }
    }
});