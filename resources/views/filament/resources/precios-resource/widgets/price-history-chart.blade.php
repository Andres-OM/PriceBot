<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Precios</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .back-button {
        background-color: #80C9CE;
        border: none;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        transition-duration: 0.4s;
        cursor: pointer;
    }

    .back-button:hover {
        background-color: black;
    }
</style>
</head>
<body>
<button onclick="history.back()" class="back-button">Volver Atrás</button>
    <div style="width: 70%; margin: auto; padding-top: 50px; text-align: center">
        <h1>Historial de Precios</h1>
        <canvas id="priceHistoryChart"></canvas>
    </div>
    <script>
        const ctx = document.getElementById('priceHistoryChart').getContext('2d');
        const priceHistoryChart = new Chart(ctx, {
            type: 'line',
            data: {!! json_encode($chartData) !!},
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>