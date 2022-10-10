// JavaScript Document
// Claudio Gomboli . the EGGS LAB
// 2012 / 8 / 29
// Responsive animated gallery

$('.dashbord').each(function(index)
{
    $(this).attr('id', 'img' + (index + 1));
});
    
$('.dashbord').each(function(){
  $('#navi').append('<div class="circle"></div>');
});
  
$('.circle').each(function(index)
{
    $(this).attr('id', 'circle' + (index + 1));
});   
   
$('.dashbord').click(function(){
if($(this).hasClass('opened')){
    $(this).removeClass('opened');
    $(".dashbord").fadeIn("fast");
    $(this).find('.ombra').fadeOut();
    $("#navi div").removeClass("activenav");
}
else{
	var indexi = $("#maincontent .dashbord").index(this) + 1;
    $(this).addClass('opened'); 
    $(".dashbord").not(this).fadeOut("fast");
    $(this).find('.ombra').fadeIn();
    $("#circle" + indexi).addClass('activenav'); 
}
});	

//navi buttons
// $("#navi div").click(function() {
// if($(this).hasClass("activenav")){
// 	return false;
// }
		
// 	$("#navi div").removeClass("activenav");
// 	$(".dashbord").removeClass('opened');
// 	$(".dashbord").show();
//         $('.ombra').hide();
		
// 	var index = $("#navi div").index(this) + 1;
// 	$("#img" + index).addClass('opened'); 
//     $(".dashbord").not("#img" + index).fadeOut("fast");
//     $("#img" + index).find('.ombra').fadeIn();
        
//     $(this).addClass("activenav");
// });