function validateForm() {
	var msg='';
    var x = document.forms["user"]["post[f_name]"].value;
    if (x == null || x == "") {
        msg += "First Name must be filled out. \n";
    }
    var x = document.forms["user"]["post[l_name]"].value;
    if (x == null || x == "") {
        msg += "Last Name must be filled out. \n";
    }
    var x = document.forms["user"]["post[email]"].value;

    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
    	msg += "Not a valid e-mail address. \n";
    }
    if(msg!=''){
    	alert(msg);
    	return false;
    }
}

$(document).ready(function(){
	$(".dlt").click(function(){
		ans = window.confirm("Are you sure about this");
		if(ans==true){
		id = $(this).attr('data-id');
		$.post( "user.php/delete/"+id, { id:id})
		  .done(function( data ) {
		    alert( data);
		    $('#tr_'+id).fadeOut();
		  });
		}else{
			alert('Cancelled')
		}
	});
});