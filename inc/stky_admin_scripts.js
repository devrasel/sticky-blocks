jQuery(document).ready(function($){
    // Admin message display logic
    // This script shows and hides success/error messages in the admin area.
    // The previous stkyr/stkyrfailed logic was removed as it's handled by transients now.

    // Function to toggle visibility of specific ID/URL fields
    function toggleDisplayOptions(selectElement) {
        var selectedValue = selectElement.val();
        var parentForm = selectElement.closest('form');
        var specificIdsRow = parentForm.find('.stky_specific_ids_row');
        var specificUrlsRow = parentForm.find('.stky_specific_urls_row');

        if (selectedValue === 'specific_ids') {
            specificIdsRow.show();
            specificUrlsRow.hide();
        } else if (selectedValue === 'other_urls') {
            specificIdsRow.hide();
            specificUrlsRow.show();
        } else {
            specificIdsRow.hide();
            specificUrlsRow.hide();
        }
    }

    // Initialize on page load for Add form
    var addSelect = $('#stky_display_on_add');
    if (addSelect.length) {
        toggleDisplayOptions(addSelect);
        addSelect.on('change', function() {
            toggleDisplayOptions($(this));
        });
    }

    // Initialize on page load for Edit form
    var editSelect = $('#stky_display_on');
    if (editSelect.length) {
        toggleDisplayOptions(editSelect);
        editSelect.on('change', function() {
            toggleDisplayOptions($(this));
        });
    }
});