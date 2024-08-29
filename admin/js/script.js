document.getElementById('deleteLink').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default link behavior
    loadContent('delete.php'); // Load content from delete.php
});

document.getElementById('med').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default link behavior
    loadContent('addMed.php'); // Load content from addMed.php
});

document.getElementById('dis').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default link behavior
    loadContent('AddDis.php'); // Load content from AddDis.php
});

document.getElementById('pnt').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default link behavior
    loadContent('addPnt.php'); // Load content from addPnt.php
});

document.getElementById('con').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default link behavior
    loadContent('consultancy.php'); // Load content from consultancy.php
});

function loadContent(url) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true); // Request content from the specified URL
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('containerArea').innerHTML = xhr.responseText; // Load content into containerArea div
            attachFormSubmitHandler(url); // Attach the submit handler for the URL
        } else {
            console.error('Error loading content:', xhr.statusText); // Log any errors
        }
    };
    xhr.onerror = function () {
        console.error('Request failed'); // Log request failure
    };
    xhr.send();
}

function attachFormSubmitHandler(actionUrl) {
    var form = document.querySelector('#containerArea form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            var xhr = new XMLHttpRequest();
            xhr.open('POST', actionUrl, true); // Submit data to the provided URL
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('containerArea').innerHTML = xhr.responseText; // Show response
                } else {
                    console.error('Error submitting form:', xhr.statusText); // Log any errors
                }
            };
            xhr.onerror = function () {
                console.error('Request failed'); // Log request failure
            };

            var formData = new FormData(form);
            xhr.send(formData); // Send FormData directly
        });
    } else {
        console.warn('No form found in containerArea'); // Warn if no form is found
    }
}
