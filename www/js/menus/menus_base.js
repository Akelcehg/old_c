function ajaxEditClick(href)
{
    // NOTE: item ID is stored in HREF attr of A tag
    var id = href || $(this).attr('href');
    if(id instanceof Object) {
        var id = $(this).attr('href');
    }
    // clear previous form data before new data loads
    $('#dialogAddEditMenu div.divForForm').html('Loading...');
    ajaxGetSetData(id);
    $('#dialogAddEditMenu').dialog('open');
    return false;
}

// here is the magic
// this func is used to get form and send form data
function ajaxGetSetData(id)
{
    // send ajax request
    $.post(id,
        $('#dialogAddEditMenu div.divForForm form').serialize()
        ,
        function(data)
        {
            if (data.status == 'failure')
            {
                $('#dialogAddEditMenu div.divForForm').html(data.div);
                // the trick: on submit-> once again this function!
                $('#dialogAddEditMenu div.divForForm form').submit(function(){
                    ajaxGetSetData(id);
                    return false;
                });
            }
            else
            if (data.status == 'success')
            {
                //$.fn.yiiGridView.update("menus-list-grid");
                $('#dialogAddEditMenu').dialog('close');
            }
            else
            {
                alert('Error: Unknown operation status, please try again or contact administrator');
            }
        }, 'json'
        );

    return false;
}





function ajaxEditClickJstree(href)
{
    // NOTE: item ID is stored in HREF attr of A tag
    var id = href || $(this).attr('href');
    if(id instanceof Object) {
        var id = $(this).attr('href');
    }
    // clear previous form data before new data loads
    $('#dialogAddEditMenu div.divForForm').html('Loading...');
    ajaxGetSetDataJstree(id);
    $('#dialogAddEditMenu').dialog('open');
    return false;
}

// here is the magic
// this func is used to get form and send form data
function ajaxGetSetDataJstree(id)
{
    // send ajax request
    $.post(id,
        $('#dialogAddEditMenu div.divForForm form').serialize()
        ,
        function(data)
        {
            if (data.status == 'failure')
            {
                $('#dialogAddEditMenu div.divForForm').html(data.div);
                // the trick: on submit-> once again this function!
                $('#dialogAddEditMenu div.divForForm form').submit(function(){
                    ajaxGetSetDataJstree(id);
                    return false;
                });
            }
            else
            if (data.status == 'success')
            {
                $('#dialogAddEditMenu').dialog('close');
				window.location.reload();
            }
            else
            {
                alert('Error: Unknown operation status, please try again or contact administrator');
            }
        }, 'json'
        );

    return false;
}

function askRemoveMenu(id){
	 
	var answer = confirm("Delete this menu ?")
	if (answer){
		$.ajax({
			url: "/admin/menus/deleteMenu/id/"+id,
			dataType: 'html',
			data: {id: id},
			async: false,
			type: "post",
			success: function(data) {
				window.location.reload();
			}
		});
	}
}

function editMenu(id){
	ajaxEditClickJstree("/admin/menus/edit/menuId/"+id);
}