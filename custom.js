(function($){


  // when "add to cart" button is pressed, show loader gif image which is placed next to the button, or inside of it
  jQuery("body.page-template-default .action-holder button.add-to-cart, body.archive .action-holder button.add-to-cart").click(function(){
    	jQuery(this).hide();
        jQuery(this).nextAll("img.loading-cart").show();  // show loader gif
  });
  
  // big deal buttons on the main page
  jQuery(".wpb_wrapper .mp-deal-btn-add-to-cart").click(function(){
    	jQuery(this).find('img.alignnone').hide(); // hide cart image
        jQuery(this).find('img.loading-cart').show();  // show loader gif
  });
  
  
  jQuery('.Blue-Light-Specialsowl').owlCarousel({
      loop:true,
      margin:10,
      merge:true,
      nav:true,
      dots: false,
      responsive:{
          0:{
          	  dots: false,
              items:2
          },
          768:{
          	  dots: false,
              merge:true,
              items:4
          },
          1024:{
          	  dots: false,
              merge:true,
              items:5
          },          
          1200:{
          	 dots: false,
             merge:true,
              items:6
          },          
          1450:{
          	 dots: false,
             merge:true,
              items:8
          }
      }
  })
  jQuery('.Blue-Light-Specialsowl1').owlCarousel({
      loop:true,
      margin:10,
      merge:true,
      nav:true,
      dots: false,
      responsive:{
          0:{
          	  dots: false,
              items:2
          },
          768:{
          	  dots: false,
              merge:true,
              items:4
          },
          1024:{
          	  dots: false,
              merge:true,
              items:5
          },          
          1200:{
          	 dots: false,
             merge:true,
              items:6
          },          
          1450:{
          	 dots: false,
             merge:true,
              items:8
          }
      }
  })
  jQuery('.Blue-Light-Specialsowl2').owlCarousel({
      loop:true,
      margin:10,
      merge:true,
      nav:true,
      dots: false,
      responsive:{
          0:{
          	  dots: false,
              items:2
          },
          768:{
          	  dots: false,
              merge:true,
              items:4
          },
          1024:{
          	  dots: false,
              merge:true,
              items:5
          },          
          1200:{
          	 dots: false,
             merge:true,
              items:6
          },          
          1450:{
          	 dots: false,
             merge:true,
              items:8
          }
      }
  })
  jQuery('.Blue-Light-Specialsowl4').owlCarousel({
      loop:false,
      margin:10,
      merge:true,
      nav:true,
      dots: false,
      responsive:{
          0:{
          	  dots: false,
              items:2
          },
          768:{
          	  dots: false,
              merge:true,
              items:4
          },
          1024:{
          	  dots: false,
              merge:true,
              items:5
          },          
          1200:{
          	 dots: false,
             merge:true,
              items:6
          },          
          1450:{
          	 dots: false,
             merge:true,
              items:8
          }
      }
  })
})(jQuery);