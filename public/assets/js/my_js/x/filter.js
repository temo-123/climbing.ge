$(document).ready(function(){

    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        
        if(value == "all")
        {
            $('.filter').show('1000');
        }
        else
        {
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
        $(this).addClass("active");
    });
        
        
    $(".filter-button-1").click(function(){
        var value = $(this).attr('data-filter-1');
        
        if(value == "all-1")
        {
            $('.filter-1').show('1000');
        }
        else
        {
            $(".filter-1").not('.'+value).hide('3000');
            $('.filter-1').filter('.'+value).show('3000');
        }

        // if ($(".filter-button").removeClass("active")) {
        //     $(this).removeClass("active");
        // }
        // $(this).addClass("active");
    });
        
        
    $(".filter-button-2").click(function(){
        var value = $(this).attr('data-filter-2');
        
        if(value == "all-2")
        {
            $('.filter-2').show('1000');
        }
        else
        {
            $(".filter-2").not('.'+value).hide('3000');
            $('.filter-2').filter('.'+value).show('3000');
        }

        // if ($(".filter-button").removeClass("active")) {
        //     $(this).removeClass("active");
        // }
        // $(this).addClass("active");
    });
});