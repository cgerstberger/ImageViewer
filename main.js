$(document).ready(function(){
    $(".albumCard img").click(function(){
        var imgUrl = $(this).attr("src");
        window.open(imgUrl);
    });
});