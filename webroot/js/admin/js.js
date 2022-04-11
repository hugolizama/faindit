$(function() {
  //$('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {
  $(window).bind("load resize", function () {
    topOffset = 50;
    width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
    if (width < 992) {
      $('div.navbar-collapse').addClass('collapse');
      //corregir de sidebar al cambiar tamanio de pantalla
    $('#menu-collapse').css('height','');
      //topOffset = 100; // 2-row-menu
    } else {
      $('div.navbar-collapse').removeClass('collapse');
      //corregir de sidebar al cambiar tamanio de pantalla
    $('#menu-collapse').css('height','51px');
    }

    height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
    height = height - topOffset;
    if (height < 1)
      height = 1;
    if (height > topOffset) {
      $("#page-wrapper").css("min-height", (height-5) + "px");
    }    
  });
});


/*$(function(){
  $('.navbar-toggle').click(function(){    
    //alert($('#menu-collapse').css('height'));
    $('#menu-collapse').css('height','51px');
  });
});*/
