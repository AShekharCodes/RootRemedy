<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RootRemedy - Search</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .results-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search for Plants, Medicines, or Diseases</h1>
        <div class="filter-controls">
            <select class="filter-dropdown" id="category">
                <option value="">Select Category</option>
                <option value="plants">Plants</option>
                <option value="medicines">Medicines</option>
                <option value="diseases">Diseases</option>
            </select>
        </div>
        <div id="results" class="results-container"></div>
    </div>

    <!-- jQuery and AJAX Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('category').addEventListener('change', function () {
            const category = this.value;
            if (category) {
                fetch(`filteredsearch.php?category=${category}`)
                    .then(response => response.json())
                    .then(data => displayResults(data, category))
                    .catch(error => console.error('Error:', error));
            } else {
                document.getElementById('results').innerHTML = '';
            }
        });

        function displayResults(data, category) {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = ''; // Clear previous results

            if (data.length > 0) {
                data.forEach(item => {
                    const card = document.createElement('div');
                    card.classList.add('card');

                    let image = '';
                    if (category === "plants" && item.image1) {
                        image = `<img src="data:image/jpeg;base64,${item.image1}" alt="${item.plant_name}" class="card-img-top">`;
                    }

                    const title = item.plant_name || item.medicine_name || item.disease_name;
                    const properties = item.plant_properties || item.preparation_method || item.properties;
                    const additional = item.how_to_take || item.symptoms || '';

                    card.innerHTML = `
                        ${image}
                        <div class="card-body">
                            <h5 class="card-title">${title}</h5>
                            <p class="card-text"><strong>Details:</strong> ${properties}</p>
                            ${additional ? `<p class="card-text"><strong>Additional Info:</strong> ${additional}</p>` : ''}
                        </div>
                    `;
                    resultsDiv.appendChild(card);
                });
            } else {
                resultsDiv.innerHTML = '<p>No results found for this category.</p>';
            }
        }
    </script>
</body>
</html>
