// script.js

document.addEventListener('DOMContentLoaded', () => {
    const memoryUsedElement = document.getElementById('memoryUsed');
    const memoryTotalElement = document.getElementById('memoryTotal');
    const diskFreeElement = document.getElementById('diskFree');
    const diskTotalElement = document.getElementById('diskTotal');
    const networkTrafficElement = document.getElementById('networkTraffic');
    const cpuUsageElement = document.getElementById('cpuUsage');
    const lastUpdatedTimeElement = document.getElementById('lastUpdatedTime');

    async function fetchPerformanceData() {
        try {
            const response = await fetch('api.php');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();

            if (data.error) {
                console.error("Error del servidor:", data.error);
                // Opcional: mostrar el error en la interfaz
                memoryUsedElement.textContent = `Error: ${data.error}`;
                memoryUsedElement.classList.remove('loading');
                return;
            }

            memoryUsedElement.textContent = data.memory_used_php;
            memoryUsedElement.classList.remove('loading');
            
            memoryTotalElement.textContent = data.memory_total_system;
            memoryTotalElement.classList.remove('loading');

            diskFreeElement.textContent = data.disk_free;
            diskFreeElement.classList.remove('loading');

            diskTotalElement.textContent = data.disk_total;
            diskTotalElement.classList.remove('loading');

            networkTrafficElement.textContent = data.network_traffic;
            networkTrafficElement.classList.remove('loading');

            cpuUsageElement.textContent = data.cpu_usage;
            cpuUsageElement.classList.remove('loading');


            lastUpdatedTimeElement.textContent = new Date().toLocaleTimeString();

        } catch (error) {
            console.error('Error al obtener datos de rendimiento:', error);
            // Mostrar un mensaje de error en la interfaz si la llamada falla
            memoryUsedElement.textContent = 'Error al cargar';
            memoryUsedElement.classList.remove('loading');
            memoryTotalElement.textContent = 'Error al cargar';
            memoryTotalElement.classList.remove('loading');
            diskFreeElement.textContent = 'Error al cargar';
            diskFreeElement.classList.remove('loading');
            diskTotalElement.textContent = 'Error al cargar';
            diskTotalElement.classList.remove('loading');
            networkTrafficElement.textContent = 'Error al cargar';
            networkTrafficElement.classList.remove('loading');
            cpuUsageElement.textContent = 'Error al cargar';
            cpuUsageElement.classList.remove('loading');
            lastUpdatedTimeElement.textContent = 'N/A';
        }
    }

    // Cargar datos al inicio
    fetchPerformanceData();

    setInterval(fetchPerformanceData, 1000); 
});