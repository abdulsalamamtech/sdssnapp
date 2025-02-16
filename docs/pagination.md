I want to generate custom pagination on my html tailwindcss table with 10 rows and maximum of ten pagination links based on this information from database use axios and jquery for the implementation on two codebase.

{
  "success": true,
  "message": "successful",
  "data": [
    {},{}
] ,
"metadata": {
    "total": 162,
    "per_page": 15,
    "current_page": 1,
    "last_page": 11,
    "previous_page_url": null,
    "next_page_url": "http://127.0.0.1:8000/api/refineries/purchases?page=2",
    "pages": {
      "1": "http://127.0.0.1:8000/api/refineries/purchases?page=1",
      "2": "http://127.0.0.1:8000/api/refineries/purchases?page=2",
      "3": "http://127.0.0.1:8000/api/refineries/purchases?page=3",
      "4": "http://127.0.0.1:8000/api/refineries/purchases?page=4",
      "5": "http://127.0.0.1:8000/api/refineries/purchases?page=5",
      "6": "http://127.0.0.1:8000/api/refineries/purchases?page=6",
      "7": "http://127.0.0.1:8000/api/refineries/purchases?page=7",
      "8": "http://127.0.0.1:8000/api/refineries/purchases?page=8",
      "9": "http://127.0.0.1:8000/api/refineries/purchases?page=9",
      "10": "http://127.0.0.1:8000/api/refineries/purchases?page=10",
      "11": "http://127.0.0.1:8000/api/refineries/purchases?page=11"
    }
  }
}












## Axios + Vanilla JavaScript Implementation

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination with Axios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="p-10 bg-gray-100">
    <div class="max-w-5xl mx-auto bg-white p-5 shadow-lg rounded-lg">
        <h2 class="text-xl font-bold mb-4">Paginated Table</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">#</th>
                    <th class="border p-2">Data</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Rows will be injected here -->
            </tbody>
        </table>
        <div id="pagination" class="flex gap-2 mt-4"></div>
    </div>

    <script>
        const apiBaseUrl = "http://127.0.0.1:8000/api/refineries/purchases";
        let currentPage = 1;

        async function fetchData(page = 1) {
            try {
                const response = await axios.get(`${apiBaseUrl}?page=${page}`);
                const { data, metadata } = response.data;
                
                renderTable(data);
                renderPagination(metadata);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById("table-body");
            tbody.innerHTML = "";

            data.forEach((item, index) => {
                tbody.innerHTML += `
                    <tr class="border">
                        <td class="border p-2">${index + 1}</td>
                        <td class="border p-2">${JSON.stringify(item)}</td>
                    </tr>
                `;
            });
        }

        function renderPagination(metadata) {
            const paginationDiv = document.getElementById("pagination");
            paginationDiv.innerHTML = "";

            const totalPages = Math.min(metadata.last_page, 10);
            
            for (let i = 1; i <= totalPages; i++) {
                paginationDiv.innerHTML += `
                    <button onclick="fetchData(${i})"
                        class="px-3 py-1 border ${metadata.current_page === i ? 'bg-blue-500 text-white' : 'bg-gray-200'}">
                        ${i}
                    </button>
                `;
            }
        }

        fetchData(currentPage);
    </script>
</body>
</html>



## Axios + jQuery Implementation

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination with jQuery & Axios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-10 bg-gray-100">
    <div class="max-w-5xl mx-auto bg-white p-5 shadow-lg rounded-lg">
        <h2 class="text-xl font-bold mb-4">Paginated Table</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">#</th>
                    <th class="border p-2">Data</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Rows will be injected here -->
            </tbody>
        </table>
        <div id="pagination" class="flex gap-2 mt-4"></div>
    </div>

    <script>
        const apiBaseUrl = "http://127.0.0.1:8000/api/refineries/purchases";
        let currentPage = 1;

        function fetchData(page = 1) {
            axios.get(`${apiBaseUrl}?page=${page}`)
                .then(response => {
                    const { data, metadata } = response.data;
                    renderTable(data);
                    renderPagination(metadata);
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function renderTable(data) {
            $("#table-body").empty();
            data.forEach((item, index) => {
                $("#table-body").append(`
                    <tr class="border">
                        <td class="border p-2">${index + 1}</td>
                        <td class="border p-2">${JSON.stringify(item)}</td>
                    </tr>
                `);
            });
        }

        function renderPagination(metadata) {
            $("#pagination").empty();
            const totalPages = Math.min(metadata.last_page, 10);
            
            for (let i = 1; i <= totalPages; i++) {
                $("#pagination").append(`
                    <button onclick="fetchData(${i})"
                        class="px-3 py-1 border ${metadata.current_page === i ? 'bg-blue-500 text-white' : 'bg-gray-200'}">
                        ${i}
                    </button>
                `);
            }
        }

        $(document).ready(() => {
            fetchData(currentPage);
        });
    </script>
</body>
</html>
