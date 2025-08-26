jQuery(document).ready(function($){
    if (typeof stky_blocks_data !== 'undefined' && stky_blocks_data.length > 0 && typeof stky_current_page_data !== 'undefined') {
        var currentPageData = stky_current_page_data;

        function shouldDisplayBlock(block) {
            var displayOn = block.display_on;
            var specificIds = block.specific_ids.split(',').map(Number).filter(Boolean); // Convert to array of numbers
            var specificUrls = block.specific_urls.split('\n').map(s => s.trim()).filter(Boolean); // Convert to array of trimmed strings
            var currentUrl = currentPageData.current_url.replace(/\/$/, ""); // Remove trailing slash for comparison

            switch (displayOn) {
                case 'entire_website':
                    return true;
                case 'all_pages':
                    return currentPageData.is_page || currentPageData.is_front_page;
                case 'all_posts':
                    return currentPageData.post_type === 'post';
                case 'all_products':
                    return currentPageData.post_type === 'product';
                case 'specific_ids':
                    return specificIds.includes(Number(currentPageData.current_id));
                case 'other_urls':
                    return specificUrls.some(url => currentUrl === url.replace(/\/$/, ""));
                default:
                    return false;
            }
        }

        stky_blocks_data.forEach(function(row) {
            if (shouldDisplayBlock(row)) {
                if ($(row.stkycon).length) {
                    $(row.stkycon).addClass('stickyBlockWrapper');
                } else {
                    console.log('No such container selector: ' + row.stkycon);
                }

                if ($(row.stkycolleft).length) {
                    $(row.stkycolleft).addClass('colLeft');
                }

                if ($(row.stkycolright).length) {
                    $(row.stkycolright).addClass('colRight');
                }

                if ($(row.stkysec).length) {
                    $(row.stkysec).addClass('sticky');
                }
            }
        });

        // Function to fix overflow issues and adjust parent height
        function fixStickyParent(stickyElement, leftColumnSelector) {
            var $stickyParent = $(stickyElement).parent();
            var $leftColumn = $(leftColumnSelector);

            if ($leftColumn.length) {
                // Set the parent's height to match the left column's height
                var leftColumnHeight = $leftColumn.height();
                $stickyParent.css('height', leftColumnHeight + 'px');
            }

            // Also fix overflow on all parents
            $stickyParent.parents().add($stickyParent).each(function() {
                if ($(this).css('overflow') === 'hidden' || $(this).css('overflow-x') === 'hidden' || $(this).css('overflow-y') === 'hidden') {
                    $(this).css('overflow', 'visible');
                }
            });
        }

        // Apply the fix for each sticky element
        stky_blocks_data.forEach(function(row) {
            if (shouldDisplayBlock(row) && $(row.stkysec).length) {
                fixStickyParent(row.stkysec, row.stkycolleft);
            }
        });

    }
});