// Get base URL (without controller and action)
var baseURL = window.location.href;
if (baseURL.substr(-1, 1) == "/") {
    var strLen = baseURL.length;
    baseURL = baseURL.slice(0, strLen - 1);
}
baseURL = baseURL.split("/");
baseURL = baseURL.slice(0, -2);
baseURL = baseURL.join("/") + "/";

// Start when the page is loaded
$(document).ready(function () {
    var getParams = parseGetParams();

    // Listener for items clicks
    $("#jstree").change(function () {
        var items = $('#jstree').nestedSortable('toArray', {startDepthCount: 0});
        var json_items = JSON.stringify(items, '');
        $.post(baseURL + 'menus/jstreeactions/', {
            action: 'save_items_orders',
            items: json_items,
            menu: getParams['menuId']
        }, function (data) {
            if (data != 'ok') {
                $("#error-dialog>.errorDialogText").html("Error ! Please contact administrator");
                $("#error-dialog").dialog('open');
            } else {

                // Show success message
                if ($(".flash-success").html()) {
                    $(".flash-success").html("Menu was moved");
                } else {
                    $("#custom_js_message").html("Menu was moved").show();
                }

            }
        });
    });

    // Remove adding as submenu
    $("#add_as_main").click(function () {
        $("#owner_id").val("0");
        $(".add_to_menu").html("None");
        $('#content_add .icons-container').show();
    });

    // Listener for items clicks
    $(".items").click(function () {

        // If we are selecting in which menu the posts of the menu that we are deleting should go
        if ($("#delete_menu_id").val() != "0") {

            // Mark menu as active
            $("#jstree div").removeClass("selected");
            $(this).addClass("selected");

            // Get menu id and set it to owner
            var idmenu = $(this).parent().attr("id").replace("list_", "");
            $("#delete_menu_posts").val(idmenu);
            // If going to add/edit/delete the menu (show the box for first time)
        } else {

            // Mark menu as active
            $("#jstree div").removeClass("active");
            $(this).addClass("active");

            // Add menu name to the popup
            $(".add_to_menu").html($(this).html());

            // Show menu name in the edit tab
            $("#edit_title").val($(this).html());
            // TODO: add url filling
            $("#edit_url").val($(this).attr("title"));
            $("#edit_login_state_id option[value='" + $(this).attr("login_state_id") + "']").attr('selected', 'selected');

            $("#edit_new_window").prop('checked', $(this).attr('data-window') == 1);

            if (!$(this).parent().parent().is('#jstree')) {
                $('#content_edit .icons-container').hide();
            } else {
                $('#content_edit .icons-container').show();
            }
            if ($(this).data('icon'))
                $("#content_edit .icons-selector input[value=" + $(this).data('icon') + "]").prop('checked', true);
            else
                $("#content_edit .icons-selector input[value=fa-table]").prop('checked', true);

            $('#content_add .icons-container').hide();

            if ($(this).hasClass("DEACTIVATED")) {
                $("#edit_hide").prop('checked', true);
            }
            else {
                $("#edit_hide").prop('checked', false);
            }

            // Get menu id and set it to owner
            var idmenu = $(this).parent().attr("id").replace("list_", "");
            $("#owner_id").val(idmenu);

            // Show the add/edit/remove box
            $("#menu_box").show();

            $.get(baseURL + 'menus/menuaccess', {menuId: idmenu}, function (response) {
                $(".menuAccessEdit").removeAttr('checked');

                if (response.access && response.access.length > 0) {
                    $(".menuAccessEdit").each(function () {
                        if (jQuery.inArray($(this).val(), response.access) != -1) {
                            $(this).prop('checked', true);
                        }
                    });
                }

            }, "JSON");

            openTab('add');
        }
    });

});

// Add menu
function addMenu() {
    var getParams = parseGetParams();
    $("#add_menu_button").html('<a href="javascript:;" class="button">Add menu</a>');

    if ($("#add_title").val() == "") {
        $("#error-dialog>.errorDialogText").html("Please, insert Title.");
        $("#add_menu_button").html('<a href="javascript:;" onClick="addMenu();" class="button">Add menu</a>');
        $("#error-dialog").dialog('open');
        return false;
    } /*else if ($("#add_url").val() == ""){
     $("#error-dialog>.errorDialogText").html("Please, insert Url.");
     $("#add_menu_button").html('<a href="javascript:;" onClick="addMenu();" class="button">Add menu</a>');
     $("#error-dialog").dialog('open');
     return false;
     }*/
    else {
        //$.post(baseURL + "menus/add/parent/"+$("#owner_id").val(), {
        $.post(baseURL + "menus/add/?parent=" + $("#owner_id").val(), {
            "MenuItem[menu_id]": getParams['menuId'],
            "MenuItem[label]": $("#add_title").val(),
            "MenuItem[url]": $("#add_url").val(),
            "MenuItem[login_state_id]": $("#add_login_state_id").val(),
            "MenuItem[icon]": $("#content_add .icons-selector input:checked").val(),
            "MenuItem[owner_id]": $("#owner_id").val(),
            "MenuItem[new_window]": $("#add_new_window").prop('checked') ? 1 : 0,
            "MenuItem[status]": (($('#add_hide').is(":checked")) ? "DEACTIVATED" : "ACTIVE"),
            'menusAccess': $(".menuAccessAdd:checked").map(function (i, n) {
                return $(n).val();
            }).get()
        }, function (data) {
            var response = $.parseJSON(data);
            if (response.status == 'success') {
                window.location.reload();
            }
            else {
                $("#add_menu_button").html('<a href="javascript:;" onClick="addMenu();" class="button">Add menu</a>');
                $("#error-dialog>.errorDialogText").html("Error: Unknown operation status, please try again or contact administrator");
                $("#error-dialog").dialog('open');
            }
        });
    }
}

// Save menu
function saveMenu() {
    var getParams = parseGetParams();
    if ($("#edit_title").val() == "") {
        $("#error-dialog>.errorDialogText").html("Please, insert Title.");
        $("#error-dialog").dialog('Please, insert title');
        return false;
    }
    /*else if($("#edit_url").val() == ""){
     $("#error-dialog>.errorDialogText").html("Please, insert Url.");
     $("#error-dialog").dialog('Please, insert url');
     return false;
     } */
    else {
        //$.post(baseURL + "menus/edit/menuId/"+$("#owner_id").val(), {
        $.post(baseURL + "menus/edit/?menuId=" + $("#owner_id").val(), {
            "MenuItem[menu_id]": getParams['menuId'],
            "MenuItem[label]": $("#edit_title").val(),
            "MenuItem[login_state_id]": $("#edit_login_state_id").val(),
            "MenuItem[url]": $("#edit_url").val(),
            "MenuItem[icon]": $("#content_edit .icons-selector input:checked").val(),
            "MenuItem[new_window]": $("#edit_new_window").prop('checked') ? 1 : 0,
            "MenuItem[status]": (($('#edit_hide').is(":checked")) ? "DEACTIVATED" : "ACTIVE"),
            "menusAccess": $(".menuAccessEdit:checked").map(function (i, n) {
                return $(n).val();
            }).get()
        }, function (data) {
            var response = $.parseJSON(data);
            if (response.status == 'success') {
                window.location.reload();
            } else {
                $("#error-dialog>.errorDialogText").html("Error: Unknown operation status, please try again or contact administrator");
                $("#error-dialog").dialog('open');
            }
        });
    }
}

// Open one of the add/edit/delete tabs
function openTab(tab) {
    $(".tab_contents").hide();
    $("#content_" + tab).show();
    $(".tabs").removeClass("active");
    $(".tab_" + tab).addClass("active");
}

// Try to remove a menu
function tryRemoveMenu() {

    // Check if the menu has subcategories
    var submenus = $("#list_" + $("#owner_id").val()).find("li");
    if (submenus.length > 0) {
        $("#error-dialog>.errorDialogText").html("You need to move all submenus to other menu first");
        $("#error-dialog").dialog('open');
        return false;
    }

    confirmDeleteMenu(false);
}

// Remove menu
function confirmDeleteMenu() {


    // Transfer posts first
    $.post(baseURL + "menus/deletemenujstree/", {
        idmenu_from: $("#owner_id").val(),
        idmenu_to: $("#delete_menu_posts").val()
    }, function (data) {
        var result = $.parseJSON(data);
        if (result.status == "success") {
            var items = $('#jstree').nestedSortable('toArray', {startDepthCount: 0});
            var json_items = JSON.stringify(items, '');
            $.post(baseURL + 'menus/jstreeactions/', {action: 'save_items_orders', items: json_items}, function (data) {
                if (data == 'ok') {

                    // When menu is removed
                    $("#list_" + $("#owner_id").val()).remove();
                    $("#jstree div").removeClass("selected");
                    $("#menu_box").hide();
                    $("#owner_id").val("0")
                    $("#delete_menu_id").val("0")
                    $("#delete_menu_posts").val("0")

                    // If there are no categories - show add button
                    if ($("#jstree div").size() == "0") {
                        $("#add_menu").show();
                    }

                    // Show success message
                    if ($(".flash-success").html()) {
                        $(".flash-success").html("Menu was removed");
                    } else {
                        $("#custom_js_message").html("Menu was removed").show();
                    }

                } else {
                    $("#error-dialog>.errorDialogText").html("Error ! Please contact administrator");
                    $("#error-dialog").dialog('open');
                    return false;
                }
            });
        } else {
            $("#error-dialog>.errorDialogText").html("Error ! Please contact administrator");
            $("#error-dialog").dialog('open');
            return false;
        }
    });
}
