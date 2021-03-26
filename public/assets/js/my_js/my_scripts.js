/*
* -----------------------------------------------------------------------------------------------------------------------------
*   Site preloader
* -----------------------------------------------------------------------------------------------------------------------------
*/
function preloader() {
    $(() => {
        setInterval(() => {
            let p = $('.preloader');
            p.css('opacity', 0);
  
            setInterval(
                () => p.remove(),
                parseInt(p.css('--duration')) * 500
            );
        }, 500);
    });
}
preloader();





/*
* -----------------------------------------------------------------------------------------------------------------------------
*   Filter
* -----------------------------------------------------------------------------------------------------------------------------
*/

$(document).ready(function(){
    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
    
        if(value == "all"){
            $('.filter').show('1000');
        }
        else{
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
        $(this).addClass("active");
    });
        
    $(".filter-button-1").click(function(){
        var value = $(this).attr('data-filter-1');
        
        if(value == "all-1"){
            $('.filter-1').show('1000');
        }
        else{
            $(".filter-1").not('.'+value).hide('3000');
            $('.filter-1').filter('.'+value).show('3000');
        }
    });
        
    $(".filter-button-2").click(function(){
        var value = $(this).attr('data-filter-2');
        
        if(value == "all-2"){
            $('.filter-2').show('1000');
        }
        else{
            $(".filter-2").not('.'+value).hide('3000');
            $('.filter-2').filter('.'+value).show('3000');
        }
        // if ($(".filter-button").removeClass("active")) {
        //     $(this).removeClass("active");
        // }
        // $(this).addClass("active");
    });
});




/*
* -----------------------------------------------------------------------------------------------------------------------------
* Head Slider
* -----------------------------------------------------------------------------------------------------------------------------
*/

var $item = $('.carousel .item');
var $wHeight = $(window).height();
$item.eq(0).addClass('active');
$item.height($wHeight); 
$item.addClass('full-screen');

$('.carousel img').each(function() {
  var $src = $(this).attr('src');
  var $color = $(this).attr('data-color');
  $(this).parent().css({
    'background-image' : 'url(' + $src + ')',
    'background-color' : $color
  });
  $(this).remove();
});

$(window).on('resize', function (){
  $wHeight = $(window).height();
  $item.height($wHeight);
});

$('.carousel').carousel({
  interval: 3000,
  pause: "false"
});




/*
*
* Security Block Move
*
*/

// new Vue({
//   el: '#app',
// //   data: {
// //     slides: 5
// //   },
//   components: {
//     'carousel-3d': Carousel3d.Carousel3d,
//     'slide': Carousel3d.Slide
//   }
// })
//# sourceURL=pen.js
// From - https://bootsnipp.com/snippets/1eqrV






/*
* -----------------------------------------------------------------------------------------------------------------------------
* Text Form 
* -----------------------------------------------------------------------------------------------------------------------------
*/

$('#js_form').bootstrapValidator({
// live: 'disabled',
    message: 'This value is not valid',
    // feedbackIcons: {
    //     valid: 'glyphicon glyphicon-ok',
    //     invalid: 'glyphicon glyphicon-remove',
    //     validating: 'glyphicon glyphicon-refresh'
    // },
    fields: {
        name: {
            validators: {
                notEmpty: {
                    message: 'The Name is required and cannot be empty'
                }
            }
        },            
        surname: {
            validators: {
                notEmpty: {
                    message: 'The Surname is required and cannot be empty'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'The email address is required'
                },
                emailAddress: {
                    message: 'The email address is not valid'
                }
            }
        },
        text: {
            validators: {
                notEmpty: {
                    message: 'The Comment is required and cannot be empty'
                }
            }
        },
        msg: {
            validators: {
                notEmpty: {
                    message: 'The Comment is required and cannot be empty'
                }
            }
        },
        num: {
            validators: {
                notEmpty: {
                    message: 'The Comment is required and cannot be empty'
                }
            }
        },
        country: {
            validators: {
                notEmpty: {
                    message: 'The Comment is required and cannot be empty'
                }
            }
        }
    }
});




/*
* -----------------------------------------------------------------------------------------------------------------------------
*   Partners block moving in about page
* -----------------------------------------------------------------------------------------------------------------------------
*/

var swiper = new Swiper('.swiper-container', {
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

/*
* -----------------------------------------------------------------------------------------------------------------------------
*   Partners block moving in about page
* -----------------------------------------------------------------------------------------------------------------------------
*/

function Login_password_show() {
  var x = document.getElementById("inputPassword");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function Register_password_show() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

/*
* -----------------------------------------------------------------------------------------------------------------------------
*   Partners block moving in about page
* -----------------------------------------------------------------------------------------------------------------------------
*/

$(function(){
    $('#js_form').submit(function(event){
        var verified = grecaptcha.getResponse();
        if (verified.length === 0) {
            event.preventDefault();
        }
    });
});