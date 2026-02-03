const calendarBody = document.getElementById("calendar-body");
const monthYear = document.getElementById("month-year");
let currentDate = new Date();

function generateCalendar(year, month) {
    calendarBody.innerHTML = "";
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    const dateObj = new Date(year, month);
    monthYear.textContent = dateObj.toLocaleString("default", { month: "long", year: "numeric" });

    let row = document.createElement("tr");
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement("td");
        row.appendChild(emptyCell);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        if ((firstDay + day - 1) % 7 === 0 && day !== 1) {
            calendarBody.appendChild(row);
            row = document.createElement("tr");
        }
        const cell = document.createElement("td");
        
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        let cellContent = `<div class="date">${day}</div>`;
        
        if (userReminders && userReminders[dateStr]) {
            cellContent += '<div class="reminder-container">';
            userReminders[dateStr].forEach(reminder => {
                const words = reminder.reminder.split(' '); 
                const needsTruncation = words.length > 4;
                const shortReminder = words.slice(0, 4).join(' ') + (needsTruncation ? '...' : '');
                cellContent += `
                    <div class="reminder-item">
                        <div class="reminder-dot"></div>
                        <span class="reminder-text">${shortReminder}</span>
                    </div>`;
            });
            cellContent += '</div>';
        }
        
        cell.innerHTML = cellContent;
        
        if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
            cell.classList.add('today');
        }
        
        row.appendChild(cell);

        cell.addEventListener('click', () => {
            if (userReminders && userReminders[dateStr]) {
                console.log('Reminders:', userReminders[dateStr]); 
                showCalendarModal(dateStr, userReminders[dateStr]);
            }
        });
    }

    const lastDay = new Date(year, month, daysInMonth).getDay();
    for (let i = lastDay + 1; i < 7; i++) {
        const emptyCell = document.createElement("td");
        row.appendChild(emptyCell);
    }
    calendarBody.appendChild(row);
}

function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
}

generateCalendar(currentDate.getFullYear(), currentDate.getMonth());


function showCalendarModal(date, reminders) {
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    overlay.style.zIndex = '999';
    
    document.body.appendChild(overlay);

    const modal = document.querySelector('.calendar-modal');
    const modalDate = modal.querySelector('.modal-date');
    const modalReminders = modal.querySelector('.modal-reminders');
    const closeButton = modal.querySelector('.cancel-button');

    modal.classList.add('show'); 
    
    modal.style.display = 'block';
    modalDate.textContent = new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    modalReminders.innerHTML = '';
    
    if (Array.isArray(reminders)) {
        modalReminders.innerHTML = reminders.map(reminder => `
        <div class="reminder-entry">
            <div class="reminder-name">
                ${reminder.name} <span class="reminder-type">(${reminder.type})</span>
            </div>
            <div class="reminder-text-in-modal">${reminder.reminder}</div>
            <div class="reminder-user">Added by ${reminder.user_name}</div>
        </div>
    `).join('');
    }

    closeButton.onclick = function() {
        modal.classList.remove('show');
        document.body.removeChild(overlay);
    }

    overlay.onclick = function() {
        modal.classList.remove('show');
        document.body.removeChild(overlay);
    }
}