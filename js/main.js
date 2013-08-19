$(window).load(function () {
    $('.flexslider').flexslider();
});

$(function () {
    $('#work').carouFredSel({
        width: '100%',
        scroll: 1,
        auto: false,
        pagination: false,
        prev: '.prev_item',
        next: '.next_item'
    });

    $("#work").touchwipe({
        wipeLeft: function () { $('.next_item').trigger('click'); },
        wipeRight: function () { $('.prev_item').trigger('click'); }
    });
    
    
    
    /* when document is ready */

      /* initiate the plugin */
      $("div.holder").jPages({
        containerID  : "dataholder",
        perPage      : 5,
        startPage    : 1,
        startRange   : 1,
        midRange     : 5,
        endRange     : 1
      });

if($("#dataholder div").length < 5)
	{
	 $("div.holder").hide();
	}
});

