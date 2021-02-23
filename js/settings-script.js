jQuery( document ).ready( function($) {
$("#exportButton").click( function() {
 loadPosts();
})

 $("#fileDownload").click( function() {
  $( "#fileDownload" ).html("").attr("disabled", true);

 })

 $("#but_upload").click( function() {
  uploadFile();
 })



function loadPosts(){
$.ajax( {
  type: 'POST',
      url: myAjax.ajaxurl,
      dataType: "json", // add data type
      cache: false,
      data: { action : 'get_ajax_posts',
      date: $("#pickdate").val() ,
      dateEnd: $("#pickedate").val() },
      beforeSend: function() {
         $("#exportButton").attr("disabled", true);
      },
      success: function( response ) {
        var dec = decodeURI(response);
        console.log( dec );
        alert("File Exported Successfully!");
        $( "#fileDownload" ).html(response).removeAttr("disabled");
      },
      complete: function() {
        $("#exportButton").removeAttr("disabled");
      }  
} );
}


function uploadFile() {
  var fd = new FormData();
          var files = $('#fileUpload')[0].files;
          
          // Check file selected or not
          if(files.length > 0 ){
             fd.append( 'fileUpload', files[0] );
             fd.append( "action" , 'upload_file_callback'); 
             fd.append( "nonce" , myAjax.nonce); 

             $.ajax({
                url: myAjax.ajaxurl,
                type: 'post',
                data: fd,
                contentType : false,
                processData : false,
                enctype: 'multipart/form-data',
                success: function(response){
                  //$('#myform').trigger("reset");
                  $('#fileUpload').val('');
                   alert("File Upload Initiated!!");
                   if(response != 0){
                      alert(response);
                     // $("#myform")[0].reset(); 
                   }else{
                      alert('file not uploaded');
                   }
                },
             });
          }else{
             alert("Please select a file.");
          }
}

} );


      