document.addEventListener("DOMContentLoaded", function () {
  // Navigation Links
  const navLinks = {
    deleteLink: "delete.php",
    med: "addMed.php",
    dis: "AddDis.php",
    pnt: "addPnt.php",
    con: "consultancy.php",
  };

  Object.keys(navLinks).forEach((linkId) => {
    document.getElementById(linkId)?.addEventListener("click", function (e) {
      e.preventDefault();
      console.log(`Navigating to: ${navLinks[linkId]}`);
      loadContent(navLinks[linkId]);
    });
  });

  // Function to load content dynamically
  function loadContent(url) {
    console.log(`Loading content from URL: ${url}`);
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Content loaded successfully.");
        document.getElementById("containerArea").innerHTML = xhr.responseText;
        attachFormSubmitHandler(url); // Attach form handler after loading content
        attachDeleteOptionHandler(); // Attach delete option handler
        attachDeleteButtonHandlers(); // Attach delete button handler
      } else {
        console.error("Error loading content:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed");
    };
    xhr.send();
  }

  // Handle form submissions using AJAX
  function attachFormSubmitHandler(actionUrl) {
    var form = document.querySelector("#containerArea form");
    if (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission
        console.log(`Submitting form to ${actionUrl} via AJAX.`);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", actionUrl, true);
        xhr.onload = function () {
          console.log("AJAX response received:", xhr.responseText);
          if (xhr.status === 200) {
            console.log("Form submitted successfully.");
            document.getElementById("containerArea").innerHTML = xhr.responseText;
            attachFormSubmitHandler(actionUrl); // Re-attach form handler
            attachDeleteOptionHandler(); // Re-attach delete option handler
            attachDeleteButtonHandlers(); // Re-attach delete button handler
          } else {
            console.error("Error submitting form:", xhr.statusText);
          }
        };
        xhr.onerror = function () {
          console.error("Request failed");
        };
        var formData = new FormData(form);
        xhr.send(formData);
      });
    } else {
      console.warn("No form found in containerArea");
    }
  }

  // Handle delete option change
  function attachDeleteOptionHandler() {
    const deleteOption = document.getElementById("deleteOption");
    if (deleteOption) {
      deleteOption.addEventListener("change", function (e) {
        e.preventDefault(); // Prevent default action
        console.log(`Selected delete option: ${deleteOption.value}`);
        loadDeleteContent(deleteOption.value); // Load delete content dynamically
      });
    }
  }

  // Load respective delete content (plants, diseases, medicines)
  function loadDeleteContent(option) {
    console.log(`Loading delete content for: ${option}`);
    var xhr = new XMLHttpRequest();
    var url = "";

    switch (option) {
      case "plants":
        url = "fetchDeletePlants.php";
        break;
      case "disease":
        url = "fetchDeleteDiseases.php";
        break;
      case "medicine":
        url = "fetchDeleteMedicines.php";
        break;
      default:
        console.error("Invalid option");
        return;
    }

    xhr.open("GET", url, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Delete content loaded successfully.");
        document.getElementById("containerArea").innerHTML = xhr.responseText;
        attachDeleteButtonHandlers(); // Re-attach delete button handlers after loading new content
      } else {
        console.error("Error loading delete content:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while loading delete content");
    };
    xhr.send();
    console.log("Request sent for delete content.");
  }

  // Handle delete button clicks
  function attachDeleteButtonHandlers() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default action
        const id = button.getAttribute("data-id");
        const type = button.getAttribute("data-type");

        console.log(`Delete button clicked for ${type} with ID: ${id}`);
        if (confirm(`Are you sure you want to delete this ${type}?`)) {
          deleteItem(id, type); // Call delete function
        }
      });
    });
  }

  // Separate delete action with AJAX
  function deleteItem(id, type) {
    console.log(`Deleting ${type} with ID: ${id}`);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "deleteItem.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        alert(xhr.responseText);
        // Reload the respective list after deletion
        loadDeleteContent(type);
      } else {
        console.error("Error deleting item:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while deleting item");
    };
    xhr.send(`id=${id}&type=${type}`);
  }
});
