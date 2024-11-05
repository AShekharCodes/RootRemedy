document.addEventListener("DOMContentLoaded", function () {
  // Navigation Links
  const navLinks = {
    deleteLink: "delete.php",
    med: "addMed.php",
    dis: "AddDis.php",
    pnt: "addPnt.php",
    con: "consultancy.php",
    report: "report.php",
    fea: "featured.php",
    pri: "privacy.html",
    use: "user_manual.html"
  };

  // Attach click event to navigation links
  Object.keys(navLinks).forEach((linkId) => {
    const linkElement = document.getElementById(linkId);
    if (linkElement) {
      linkElement.addEventListener("click", function (e) {
        e.preventDefault();
        console.log(`Navigating to: ${navLinks[linkId]}`);
        loadContent(navLinks[linkId]);
      });
    }
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

        // Attach handlers after content is loaded
        attachFormSubmitHandler(url);  // Attach form handler if form is found
        attachDeleteOptionHandler();   // Attach delete option handler
        attachDeleteButtonHandlers();  // Attach delete button handler
      } else {
        console.error("Error loading content:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed");
    };
    xhr.send();
  }

  // Function to generate report
  function generateReport() {
    console.log("Generating report...");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "generateReport.php", true); // Adjust URL as needed for report generation
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Report generated successfully.");
        alert("Report generated! Check your downloads.");
      } else {
        console.error("Error generating report:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while generating report");
    };
    xhr.send();
  }

  // Handle form submissions using AJAX
  function attachFormSubmitHandler(actionUrl) {
    var form = document.querySelector("#containerArea form");
    if (form) {
      console.log(`Form found in the loaded content. Attaching submit handler for form action: ${actionUrl}`);
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
            attachFormSubmitHandler(actionUrl);  // Re-attach form handler
            attachDeleteOptionHandler();         // Re-attach delete option handler
            attachDeleteButtonHandlers();        // Re-attach delete button handler
          } else {
            console.error("Error submitting form:", xhr.statusText);
          }
        };
        xhr.onerror = function () {
          console.error("Request failed while submitting form");
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
        loadDeleteContent(deleteOption.value);  // Load delete content dynamically
      });
    }
  }

  // Load respective delete content (plants, diseases, medicines)
  function loadDeleteContent(option) {
    console.log(`Loading delete content for: ${option}`);
    var xhr = new XMLHttpRequest();
    var url = "";

    // Select URL based on the delete option
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
        console.error("Invalid delete option");
        return;
    }

    xhr.open("GET", url, true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Delete content loaded successfully.");
        document.getElementById("containerArea").innerHTML = xhr.responseText;
        attachDeleteButtonHandlers();  // Attach delete button handlers after loading new content
      } else {
        console.error("Error loading delete content:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while loading delete content");
    };
    xhr.send();
  }

  // Handle delete button clicks
  function attachDeleteButtonHandlers() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault();  // Prevent the default action
        const id = button.getAttribute("data-id");
        const type = button.getAttribute("data-type");
        const row = button.closest("tr");  // Get the closest row for deletion

        console.log(`Delete button clicked for ${type} with ID: ${id}`);
        if (confirm(`Are you sure you want to delete this ${type}?`)) {
          deleteItem(id, type, row);  // Pass the row to delete function
        }
      });
    });
  }

  // Separate delete action with AJAX
  function deleteItem(id, type, row) {
    console.log(`Deleting ${type} with ID: ${id}`);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "deleteItem.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        alert(xhr.responseText);
        if (row) {
          row.remove();  // Remove the row from the table immediately
        }
      } else {
        console.error("Error deleting item:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while deleting item");
    };
    xhr.send(`id=${id}&type=${type}`);
  }

  // Search functionality
  const searchButton = document.getElementById("searchButton");
  const searchInput = document.getElementById("searchInput");

  if (searchButton) {
    searchButton.addEventListener("click", function () {
      const query = searchInput.value.trim();
      if (query) {
        performSearch(query);
      } else {
        alert("Please enter a search term.");
      }
    });
  }

  // Function to perform the search
  function performSearch(query) {
    console.log(`Searching for: ${query}`);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        console.log("Search results received:", xhr.responseText);
        document.getElementById("containerArea").innerHTML = xhr.responseText;  // Update the container area with search results
      } else {
        console.error("Error during search:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed while searching");
    };
    xhr.send(`query=${encodeURIComponent(query)}`);
  }
});
