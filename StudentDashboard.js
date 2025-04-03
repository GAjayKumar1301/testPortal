// Toggle Dropdown Visibility
function toggleDropdown() {
    var dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show-dropdown');
}
// Close dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('#img_icon')) {
        var dropdown = document.getElementById('dropdownMenu');
        if (dropdown.classList.contains('show-dropdown')) {
            dropdown.classList.remove('show-dropdown');
        }
    }
}
// Function to check project title availability using AJAX
function checkProject() {
    let title = document.getElementById('projectTitle').value.trim();

    if (title === '') {
        alert('Please enter a project title!');
        return;
    }

    fetch('http://localhost:5000/api/check-project', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ title: title })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('searchResult').innerHTML = `<p>Similarity: ${data.similarity}%</p>`;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('searchResult').innerHTML = 'Error checking project!';
    });
}

