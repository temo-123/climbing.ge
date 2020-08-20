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
* Gallery images open (move not work)
* -----------------------------------------------------------------------------------------------------------------------------
*/





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

new Vue({
  el: '#carousel3d',
//   data: {
//     slides: 5
//   },
  components: {
    'carousel-3d': Carousel3d.Carousel3d,
    'slide': Carousel3d.Slide
  }
})
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
*   view 360
* -----------------------------------------------------------------------------------------------------------------------------
*/

//Ref: https://github.com/mahonnaise/cyclotron

$(document).ready(function($) {
    $('.cycle').cyclotron();

    $(".cycle").css('cursor', 'url(http://i.imgur.com/FrQFOJo.png),auto');
});


// library

(function($) {
    $.fn.cyclotron = function(options) {
        var settings = $.extend({
            dampingFactor: 0.93,
            historySize: 5
        }, options);
        return this.each(function() {
            var container, sx, dx = 0,
                armed, offset = 0,
                tick, prev, h = [];
            container = $(this);

            container.mousedown(function(e) {
                sx = e.pageX - offset;
                armed = true;
                e.preventDefault();
            });
            container.mousemove(function(e) {
                var px;
                if (armed) {
                    px = e.pageX;
                    if (prev === undefined) {
                        prev = px;
                    }
                    offset = px - sx;
                    if (h.length > settings.historySize) {
                        h.shift();
                    }
                    h.push(prev - px);

                    container.css('background-position', offset);

                    prev = px;
                }
            });
            container.bind('mouseleave mouseup', function() {
                if (armed) {
                    var i, len = h.length,
                        v = h[len - 1];
                    for (i = 0; i < len; i++) {
                        v = (v * len + (h[i])) / (len + 1);
                    }
                    dx = v;
                }
                armed = false;
            });
            tick = function() {
                if (!armed && dx) {
                    dx *= settings.dampingFactor;
                    offset -= dx;
                    container.css('background-position', offset);
                    if (Math.abs(dx) < 0.001) {
                        dx = 0;
                    }
                }
            };
            setInterval(tick, 16);
        });
    };
}(jQuery));




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