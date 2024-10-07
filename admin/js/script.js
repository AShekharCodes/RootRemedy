document.addEventListener('DOMContentLoaded', function () {
    // Navigation Links
    const navLinks = {
        deleteLink: 'delete.php',
        med: 'addMed.php',
        dis: 'AddDis.php',
        pnt: 'addPnt.php',
        con: 'consultancy.php'
    };

    Object.keys(navLinks).forEach(linkId => {
        document.getElementById(linkId)?.addEventListener('click', function (e) {
            e.preventDefault();
            loadContent(navLinks[linkId]);
        });
    });

    function loadContent(url) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('containerArea').innerHTML = xhr.responseText;
                attachFormSubmitHandler(url); // Attach handler after loading new content
                attachDeleteOptionHandler(); // Attach delete option handler after loading content
                attachDeleteButtonHandlers(); // Attach delete button handler after loading content
            } else {
                console.error('Error loading content:', xhr.statusText); // Log error on failure
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
                xhr.open('POST', actionUrl, true);
                xhr.onload = function () {
                    console.log('AJAX response:', xhr.responseText); // Log response for debugging
                    if (xhr.status === 200) {
                        document.getElementById('containerArea').innerHTML = xhr.responseText;
                        attachFormSubmitHandler(actionUrl); // Re-attach form handler
                        attachDeleteOptionHandler(); // Re-attach delete option handler
                        attachDeleteButtonHandlers(); // Re-attach delete button handler
                    } else {
                        console.error('Error submitting form:', xhr.statusText); // Log error on failure
                    }
                };
                xhr.onerror = function () {
                    console.error('Request failed'); // Log request failure
                };
                var formData = new FormData(form);
                xhr.send(formData);
            });
        } else {
            console.warn('No form found in containerArea'); // Log warning if no form is found
        }
    }

    // Attach delete option change handler
    function attachDeleteOptionHandler() {
        const deleteOption = document.getElementById('deleteOption');
        if (deleteOption) {
            deleteOption.addEventListener('change', function (e) {
                e.preventDefault(); // Prevent the default action
                console.log(`Selected delete option: ${deleteOption.value}`); // Debugging log
                loadDeleteContent(deleteOption.value); // Call the loadDeleteContent function
            });
        }
    }

    // Load the respective delete data (plants, diseases, or medicines)
    function loadDeleteContent(option) {
        console.log(`Loading delete content for: ${option}`); // Debugging log before AJAX call
        var xhr = new XMLHttpRequest();
        var url = '';

        switch (option) {
            case 'plants':
                url = 'fetchDeletePlants.php';
                break;
            case 'disease':
                url = 'fetchDeleteDiseases.php';
                break;
            case 'medicine':
                url = 'fetchDeleteMedicines.php';
                break;
            default:
                console.error('Invalid option'); // Log invalid option
                return;
        }

        xhr.open('GET', url, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('containerArea').innerHTML = xhr.responseText;
                attachDeleteButtonHandlers(); // Attach delete button handlers after loading content
            } else {
                console.error('Error loading delete content:', xhr.statusText); // Log error on failure
            }
        };
        xhr.onerror = function () {
            console.error('Request failed while loading delete content'); // Log request failure
        };
        xhr.send();
    }

    // Attach delete button event handlers
    function attachDeleteButtonHandlers() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = button.getAttribute('data-id');
                const type = button.getAttribute('data-type');

                if (confirm(`Are you sure you want to delete this ${type}?`)) {
                    deleteItem(id, type); // Call deleteItem function
                }
            });
        });
    }

    // Separate delete action
    function deleteItem(id, type) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'deleteItem.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);
                // Reload the respective list after deletion
                if (type === 'plant') {
                    loadDeleteContent('plants');
                } else if (type === 'disease') {
                    loadDeleteContent('disease');
                } else if (type === 'medicine') {
                    loadDeleteContent('medicine');
                }
            } else {
                console.error('Error deleting item:', xhr.statusText); // Log error on failure
            }
        };
        xhr.onerror = function () {
            console.error('Request failed while deleting item'); // Log request failure
        };
        xhr.send(`id=${id}&type=${type}`);
    }
});
