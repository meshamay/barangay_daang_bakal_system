// Dynamically adjust export modal spacing for Monthly/Yearly only

document.addEventListener('DOMContentLoaded', function() {
    const exportRangeInput = document.getElementById('exportRange');
    const formatSectionsContainer = document.getElementById('exportFormatSectionsContainer');
    const rangeBtns = document.querySelectorAll('.export-range-btn');

    function updateSpacing() {
        if (exportRangeInput.value === 'monthly' || exportRangeInput.value === 'yearly') {
            formatSectionsContainer.classList.add('mt-8');
        } else {
            formatSectionsContainer.classList.remove('mt-8');
        }
    }

    rangeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            exportRangeInput.value = btn.getAttribute('data-range');
            updateSpacing();
        });
    });

    updateSpacing(); // Initial state
});
