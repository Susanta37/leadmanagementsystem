 <script>
        // Add any dashboard-specific JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Animate counters
            const statValues = document.querySelectorAll('.stat-value');
            
            statValues.forEach(stat => {
                const finalValue = stat.textContent;
                const numericValue = parseInt(finalValue.replace(/[^0-9]/g, ''));
                
                if (!isNaN(numericValue)) {
                    let currentValue = 0;
                    const increment = numericValue / 50;
                    const timer = setInterval(() => {
                        currentValue += increment;
                        if (currentValue >= numericValue) {
                            stat.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            stat.textContent = Math.floor(currentValue).toLocaleString();
                        }
                    }, 30);
                }
            });
        });
    </script>