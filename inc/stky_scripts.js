// stick jqueries 

jQuery(document).ready(function($){

    // Sticky Block Function Starts
    $.fn.stickyBlock = function (){
        // Define elements
        let columnLeft;
        let columnRight
        let leftBlockHeight;

        // Define window width
        let windowWidthCurrent = $(window).width();

        $(window).on('load resize scroll', () => {
            // Update window width
            windowWidthCurrent = $(window).width();

            this.each((index, element) => {

                // Find element to apply values
                columnLeft = $(element).find('.colLeft');
                columnRight = $(element).find('.colRight');
    
                // get &  Assign height of left column
                leftBlockHeight = $(columnLeft).outerHeight();
    
                // Apply height to Right column
                $(leftBlockHeight).promise().done(() => {
                    columnRight.css('height', leftBlockHeight);
                });
    
                // male sure on mobile doesn't Apply height to right column
                if( windowWidthCurrent >= 1024 ){
                    $(leftBlockHeight).promise().done(() => {
                        columnRight.css('height', leftBlockHeight);
                    });
                }
                else{
                    columnRight.css('height', 'auto');
                }
    
            });
    
            return this;
        });
        
    }

// Usage
// Conatiner class or id
$('.stickyBlockWrapper').stickyBlock();

});


