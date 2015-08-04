var file; 
var last_count=0; // last element added
var elements_in_row=1; // total tile in one row
var request_lock=0; //avoiding parallel request shared lock
var buffer=[]; //fetch image from buffer
var initialWrappers; // Initial tile count
$(document).ready(function(){
        //Initial height
        $(".image_display_area").height($(window).height()-50);
        //setting scroll bar 
        $(".imageContainer").css("min-height",$(".image_display_area").height()+5);
        //Calculate first load images
         //Calculate height of view port
        var containerHeight=$(".image_display_area").height()-20; // removing padding

        // Calculate maximum possible rows
        var rowCount= containerHeight / 280;
        rowCount=Math.floor(rowCount);

        //Calculate width of view port
        var containerWidth=$(".image_display_area").width()-20;//removing padding

        //Calculate maximum possible columns
        var columnCount=containerWidth /325;
        columnCount=Math.floor(columnCount);
        elements_in_row=columnCount;

        initialWrappers=rowCount * columnCount;
        // Initialize images
        loadImages(last_count,initialWrappers);

        //Callback handler for form submit event
        $("#imageform").submit(function(e)
        {

                var formObj = $(this);
                var formURL = formObj.attr("action");
                var formData = new FormData(this);
                if(!$("#uploadImage").val())
                {
                        formData.append('image',file);
                }
                $.ajax({
                        url: formURL,
                        type: 'POST',
                        data:  formData,
                        dataType: 'json',
                        mimeType:"multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData:false,
                success: function(data)
                {
                        if(!data.status)
                        {
                                alert("Cannot upload Image. Please try again");
                        }
                        else
                        {
                                last_count=0;
                                $(".imageContainer").html("");
                                loadImages(last_count,initialWrappers);
                                $("#reset").click();
                        }
                },
                 error: function() 
                 {
                        alert("Cannot upload Image. Please try again");	
                 }          
                });
                e.preventDefault(); //Prevent Default action. 
        }); 
        $(".color").keyup(function(){
                if($(this).val()>255)
                        $(this).val(255);
                var red=$(".red").val();
                var green=$(".green").val();
                var blue=$(".blue").val();
                $(".color_demo").css("background-color", "rgb("+ red +"," + green + "," + blue + ")")
                $(".strip").css("background-color", "rgb("+ red +"," + green + "," + blue + ")")
        });
        $(".fcolor").keyup(function(){
                if($(this).val()>255)
                        $(this).val(255);
                var red=$(".fred").val();
                var green=$(".fgreen").val();
                var blue=$(".fblue").val();
                $(".fcolor_demo").css("background-color", "rgb("+ red +"," + green + "," + blue + ")")
                $(".strip").css("color", "rgb("+ red +"," + green + "," + blue + ")")

        });
        //Quickfix color set
        $(".color").trigger("keyup");
        $(".fcolor").trigger("keyup");

        //infinite scroll
        $('.image_display_area').bind('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
                if(!request_lock)
                    loadImages(last_count,elements_in_row);
            }
            destroyHiddenImages();
        });

});
//file drag and drop function
function allowDrop(ev) {
        ev.preventDefault();
        $("#drop").addClass("active");

}

function drop(ev) {
        ev.preventDefault();
        $("#drop").removeClass("active");
        $(".preview").show();
        $(".file_input").hide();
        var oFReader = new FileReader();
        file=ev.dataTransfer.files[0];
        oFReader.readAsDataURL(file);

        oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
        //ev.target.appendChild(document.getElementById(data));
}
// Updating the bottom Line
function updateBottomLine(obj)
{
        $(".strip").html($(obj).val());
}
// Previewing the image
function PreviewImage() {
        $(".preview").show();
        $(".file_input").hide();
        var oFReader = new FileReader();
        file=document.getElementById("uploadImage").files[0];
        oFReader.readAsDataURL(file);

        oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
};
// Clear the image on reset
function clearImage()
{
        $(".preview").hide();
        $(".file_input").show();
        $("#uploadImage").attr("src","");
        $(".strip").html("");
}
function loadMoreImages()
{
        loadImages(last_count,elements_in_row)
}
function loadImages(from_count,possibleWrappers)
{
        if(request_lock)
            return;
        request_lock=1;
        $.ajax({
                url: "controller.php",
                type: 'GET',
                data:  "from="+from_count+"&limit="+possibleWrappers,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {
                        if(!data.status)
                        {
                                $(".loadMore").html("No more images to load");
                        }
                        else
                        {
                                $.each( data.images, function( key, image ) {

                                  $(".imageContainer").append('\
                                                                  <div class="image_wrapper" data_index="'+image['image_id']+'">\
                                                <img class="img-thumbnail" src="images/thumb/'+image["image_server_name"]+'"/>\
                                                <div class="image_name">'+image['image_name']+'</div>\
                                        </div>\
                                  ');
                                    buffer[image['image_id']]="images/thumb/"+image["image_server_name"];
                                  last_count++;
                                  request_lock=0;
                                });
                                destroyHiddenImages()
                        }
                },
                 error: function() 
                 {
                        //$(".imageContainer").after("No more images to load");
                 }          
                });
}
//Destroying hidden image
function destroyHiddenImages()
{
    $(".image_wrapper").each(function(){
        if(isElementVisible(this))
        {
          $(this).removeClass("hidden_test");  
          $(this).addClass("visible_test");
          $(this).find("img").attr("src",buffer[$(this).attr("data_index")]);
        }
        else
        {
          $(this).removeClass("visible_test");  
          $(this).addClass("hidden_test");
          $(this).find("img").attr("src","");
        }
    });
}
//check element visibiltiy
function isElementVisible(element)
{
    var TopView = $(".imageContainer").scrollTop()-340;
    var BotView = TopView + $(".imageContainer").height()+340;
    var TopElement = $(element).offset().top;
    var BotElement = TopElement + $(element).height();
    return ((BotElement <= BotView) && (TopElement >= TopView));
}