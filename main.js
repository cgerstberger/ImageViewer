$(document).ready(function(){

    $.ajax({
        type: "GET",
        url: "http://localhost/ImageViewer/trunk/getImage.php",
        dataType: "json",
        success: imagesLoaded
    });

});

function imagesLoaded(response){
    var jsonArr = response;
    var $albumRow = $(".albumRow");

    for (var i = 0; i < jsonArr.length; i++) {
        var imageArray = jsonArr[i];
        var imgUrl = imageArray[0];
        var price = imageArray[1];
        var $div = $("<div></div>").addClass("albumCard col-sm-12 col-md-6");
        var $img = $("<img />").attr("src", imgUrl);
        var $pTag = $("<p>" + price + "</p>");

        $div.append($img);
        $div.append($pTag);
        $albumRow.append($div);
    }

    $(".albumCard img").click(function(){
        var imgUrl = $(this).attr("src");
        window.open(imgUrl);
        var msg = "Hello world";
    });
}