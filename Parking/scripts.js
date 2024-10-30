function fetchLogs() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_logs.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            displayLogs(data);
        } else {
            console.error('Failed to fetch logs');
        }
    };
    xhr.send();
}

function displayLogs(data) {
    const container = document.getElementById('logsContainer');
    container.innerHTML = ''; // Kosongkan kontainer sebelumnya

    if (data.length > 0) {
        data.forEach(row => {
            const div = document.createElement('div');
            div.innerHTML = `
                <p>ID: ${row.id}</p>
                <p>Plat Nomor: ${row.plat_nomor}</p>
                <p>UID: ${row.uid}</p>
                <p>Username: ${row.username}</p>
                <p>timestamp: ${row.timestamp}</p>
                <p>Status: ${row.status}</p>
            `;
            container.appendChild(div);
        });
    } else {
        container.innerHTML = '<p>No logs available</p>';
    }
}

// Panggil fungsi fetchLogs saat halaman dimuat
window.onload = function() {
    fetchLogs();
};
