// Show add/edit popup
function ajaxEditClick(href){

    var id = href || $(this).attr('href');
    if(id instanceof Object) {
        var id = $(this).attr('href');
    }

    $('#dialogAddEditCurse div.divForForm').html('Loading...');
    ajaxGetSetData(id);
    $('#dialogAddEditCurse').dialog('open');
    return false;
}

// Send request to get or set data
function ajaxGetSetData(id)
{
    $.post(id,
        $('#dialogAddEditCurse div.divForForm form').serialize(),
        function(data){
            if (data.status == 'failure'){
                
                if(data.caption && data.caption != '')
                $('div.ui-dialog-buttonset button:eq(0) span').html(data.caption);
                
                $('#dialogAddEditCurse div.divForForm').html(data.div);
                $('#dialogAddEditCurse div.divForForm form').submit(function(){
                    ajaxGetSetData(id);
                    return false;
                });
				
            } else if (data.status == 'success'){
                $('#dialogAddEditCurse').dialog('close');
                $.fn.yiiGridView.update('curse-grid')
				
            } else {
                alert('Error: Unknown operation status, please try again or contact administrator');
            }
        }, 'json'
	);

    return false;
}