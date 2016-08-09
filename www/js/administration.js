var thisId = "";
var _options = new Array();
var user_payments = {};

$(document).ready(function() {
    $("#selectAll").click(function() {
        var allSelected = $(".selectProducts option:not(:selected)").length == 0;
        if (!allSelected) {
            $(".selectProducts option").attr("selected", "selected");
            $(this).find("span").html("Unselect all");
        } else {
            $(".selectProducts option").removeAttr("selected");
            $(this).find("span").html("Select all");
        }
    });

    $(".selectProducts").change(function() {
        var allSelected = $(".selectProducts option:not(:selected)").length == 0;
        if (allSelected) {
            $("#selectAll").find("span").html("Unselect all");
        } else {
            $("#selectAll").find("span").html("Select all");
        }
    });

    $('#series :checkbox').click(function() {
        if ($('#series input').is(':checked')) {
            $('span#series_row').css('display', 'inline-block');
        } else {
            $('span#series_row').css('display', 'none');
            $('span#series_row input:text').each(function() {
                $(this).val('');
            });
        }
    });

    /**
     * Show/Hide Reserved Periods, using in View User Page
     */
    $('.showHidePeriods a').click(function(e) {
        e.preventDefault();
        $('#reservedPeriodsGrid').slideToggle();
    });

    /**
     * Show/Hide LanguageCode table on update client form loaded
     */
    showHideLanguageCodes(false);

    $('.showHideLangCodeCBox').change(function(e) {
        showHideLanguageCodes(true);
    });

    $('.legendLink').click(function(e) {
        $.post($(this).attr('href'), {}, function(response) {
            $('#users').html(response);

            var handler = function() {
                $(this).attr('id', 'closeAccountLink');
                openCloseUserPopUp($(this).parent().parent().children(':first-child').text());
            };

            $('.closeAccountLink').live('click', handler);
            $.noConflict();
        });
        e.preventDefault();
    });

	$('body').on('blur', '#usertable input[type=text], #usertable select', function() {
		var text = '';
		if ($(this).is('select')) {
			text = $('option:selected', this).text();
		} else {
			text = $(this).val().trim();
			$(this).val(text);
		}

		if (text != '') {
			$(this).parents('td').find('div:first-child').html(text);
		}
	});
});


function setOption(key, value) {
    _options[key] = value;
}


function getOption(key) {
    return _options[key];
}

function changeBillingInfo() {
    $(".moreRows input:text, .billingSelect").each(function() {
        if ($(this).attr("disabled"))
            $(this).attr("disabled", false);
        else
            $(this).attr("disabled", true);
    });
}

function setHiddenColor(hex, fieldId) {
    $('#' + fieldId).val(hex);
}

function showHideLanguageCodes(scrollTo) {
    var checkbox = $('.showHideLangCodeCBox:first');

    if (checkbox.prop('checked'))
    {
        $('#language_code_table').fadeIn('normal');

        if (scrollTo) {
            $('html, body').stop(true).animate({scrollTop: checkbox.offset().top}, 800);
        }
    }
    else
    {
        $('#language_code_table').fadeOut('normal');
    }

}

function openApprovePopUp(id) {
    thisId = parseInt(id);
    $("#dialogApprove").dialog({
        autoOpen: true,
        dialogClass: 'center-dialog',
        position: 'center',
        open: function() {
            $('.center-dialog').center();
            $('.center-dialog').css('z-index', 1050);
        }
    });
}


function openResultsPopUp(id) {
    ajaxUrl = baseUrl + ajaxPath;
    userid = parseInt(id);
    thisId = userid;
    $.ajax({
        url: ajaxUrl,
        type: 'post',
        data: {
            userId: userid
        },
        success: function($data) {
            $("#dialogResults").html($data);
        },
        complete: function() {
            $("#dialogResults").dialog({
                close: function(event, ui) {
                    $('body').css('overflow', 'auto');
                    $(window).unbind('scroll');
                }
            });

            $("#dialogResults").dialog({
                autoOpen: true,
                dialogClass: 'center-dialog',
                position: 'center',
                open: function() {
                    $('.center-dialog').center();
                    $('.center-dialog').css('z-index', 1050);

                    // Disable scrollbar while dialog is opened
                    $('body').css('overflow', 'hidden');
                    var top = $(window).scrollTop();
                    var left = $(window).scrollLeft();
                    $(window).scroll(function() {
                        $(this).scrollTop(top).scrollLeft(left);
                    });
                }
            });
        }
    });
}



function refreshGrid(id, serializedFormData) {
    serializedFormData = serializedFormData || false;
    if (serializedFormData)
        $.fn.yiiGridView.update(id, {
            data: serializedFormData
        });
    else
        $.fn.yiiGridView.update(id);
}


function verify(roleId, comment) {
    var params = {};
    params['userId'] = thisId;
    params['roleId'] = roleId;
    params['comment'] = comment;

    console.log("roleId = " + roleId);

    if (roleId <= 0) {
        alert("Wrong role id");
        return false;
    }
    console.log("url " + getOption('verifyUrl'));

    $.post(getOption('verifyUrl'), params, function() {
        if ($('.grid-view').length)
            $.fn.yiiGridView.update($('.grid-view').attr('id'));
    });

    if ($("#dialogResults").dialog("isOpen") === true) {
        $("#dialogResults").dialog("close");
    }
}

function approve() {
    $("#dialogApprove").dialog("disable");
    $.post(getOption('approveUrl'), {userId: thisId}, function() {
        $("#dialogApprove").dialog("enable");
        $("#dialogApprove").dialog("close");

        if (getOption('isUserDetailsView'))
            $('#approve_contract').hide();
        else
            refreshGrid('aprove-users-grid');
    });
}

var sendingContract = false;
function resendContract(userId) {

    if (sendingContract)
        return false;

    $('#resend_contract_loading').show();
    $('#resend_contract').hide();

    sendingContract = true;

    $.post(getOption('resendContractUrl'), {userId: userId}, function() {
        $('#resend_contract_loading').hide();
        $('#resend_contract').show();
        sendingContract = false;
    });
}

function showEditScoreForm() {
    $('#showEditScore').hide();
    $('#editScoreSubmit').show();
    $('#statTable').addClass('editing');
    $('#userScoreInput').focus();

    correctScoreInputSize();

    //set int value into input
    $('#userScoreInput').val(parseInt($('#statTable table tbody tr:first-child td').text()));
}
function showEditUserForm() {
    $('#showEditUser').hide();
    $('#editUserSubmit').show();
    $('#usertable').addClass('editing');
    $('#userFullNameInput').focus();

    correctUserEditSize();
}

/**
 * Made some temporary changes, to make ability change the role also,
 * when the "stats" tab is being editable
 *
 * @returns void
 */
function updateUserScore(userId) {

    if ($('#userScoreInput').val() == "")
    {
        finishUpdateUserScore();
        return false;
    }

    var currentScore = parseFloat($('#statTable table tbody tr:first-child td').text());
    var newScore = parseFloat($('#userScoreInput').val());
    var newRoleId = parseInt($('#roleId').val());
    var ignoreTplSettings = $('#ignoreTplSettings').prop('checked') ? 1 : 0;
    var allowToOverwrite = $('#allowToOverwrite').prop('checked') ? 1 : 0;

    /*if(currentScore == newScore)
     {
     finishUpdateUserScore();
     return false;
     }*/

    $.post(getOption('resetUserScoreUrl'),
            {userId: userId, score: newScore, roleId: newRoleId, ignoreTplSettings: ignoreTplSettings, allowToOverwrite: allowToOverwrite},
    function(data) {

        var response = JSON.parse(data);

        if (response.status == 'ok') {
            //$('#statTable table tbody tr:eq(0) td').html(response.score);
            //$('#statTable table tbody tr:eq(1) td').html(response.avg);
            //$('#statTable table tbody tr:eq(2) td').html(response.allAvg);
            $('#statTable table tbody tr:eq(2) .cell-val').html(response.roleName);
            $('#statTable table tbody tr:eq(3) .cell-val').html(response.ignoreTplSettings);
            $('#statTable table tbody tr:eq(4) .cell-val').html(response.allowToOverwrite);
        }

        finishUpdateUserScore();
    });
}
function updateUser(userId) {
    var FirstName = $('#userFirstNameInput').val();
    var MiddleName = $('#userMiddleNameInput').val();
    var LastName = $('#userLastNameInput').val();
    var PriEmail = $('#userEmailInput').val();
    var AltEmail = $('#userAltEmailInput').val();
    var CompanyName = $('#userCompanyNameInput').val();
    var PhoneNumber = $('#userPhoneNumberInput').val();
    var AltPhoneNumber = $('#userAltPhoneNumberInput').val();
    var AddressLine1 = $('#userAddressLine1Input').val();
    var AddressLine2 = $('#userAddressLine2Input').val();
    var City = $('#userCityInput').val();
    var ZipCode = $('#userZipCodeInput').val();
    var Province = $('#userProvinceInput').val();
    var countryId = $('#userCountryList').val();

    $.post(getOption('updateUserUrl'), {
        userId: userId,
        firstName: FirstName,
        middleName: MiddleName,
        lastName: LastName,
        email: PriEmail,
        altEmail: AltEmail,
        companyName: CompanyName,
        phoneNumber: PhoneNumber,
        altPhoneNumber: AltPhoneNumber,
        addressLine1: AddressLine1,
        addressLine2: AddressLine2,
        city: City,
        zipCode: ZipCode,
        province: Province,
        countryId: countryId
    }, function(data) {

		$('div.errorMessage').each(function() {
			$(this).removeClass('errorMessage');			
			$(this).removeAttr('rel');
		});

        var result = JSON.parse(data);

        if (result.status == 'error') {
			for (var field in result.errors) {				
				$('#' + field).addClass('errorMessage');
				$('#' + field).attr('rel', 'tooltip');
				$('#' + field).attr('data-original-title', result.errors[field]);
			}
		}

        finishUpdateUser();
    });
}
function finishUpdateUserScore() {

    $('#showEditScore').show();
    $('#editScoreSubmit').hide();
    $('#statTable').removeClass('editing');
    correctScoreInputSize();
}
function finishUpdateUser() {

    $('#showEditUser').show();
    $('#editUserSubmit').hide();
    $('#usertable').removeClass('editing');

}

/**
 * Made some temporary changes, to make ability change the role also,
 * when the "stats" tab is being editable
 *
 * @returns void
 */
function correctScoreInputSize() {
    //$('#editScoreBlock').width($('#statTable table tbody tr:first-child td').outerWidth()+1);
    //$('#userScoreInput').height($('#statTable table tbody tr:first-child td').height());
    //$('#roleId').parent().width($('#statTable table tbody tr:last-child td').outerWidth()+1);
    //$('#ignoreTplSettingsContainer').width($('#statTable table tbody tr:last-child td').outerWidth()-26);
    //$('#roleId').parent().height($('#statTable table tbody tr:last-child td').height());
}

function correctUserEditSize() {
    //$('#editUserBlock *').width($('#usertable table tbody tr:first-child td').width());
    //$('#editUserBlock').css($('#usertable table tbody tr:first-child td').position());
}

// Remove all symbols except the numeric ones
function onlyInteger(field) {

    var re = /^[0-9]*$/;
    if (!re.test(field.value)) {
        field.value = field.value.replace(/[^0-9]/g, "");
    }
}

function checkMax(field, max)
{
    var n = parseFloat(field.value);
    if (n > max)
        field.value = max;
}

function openResetTestPopUp() {
    $("#dialogResetTest").dialog({
        autoOpen: true,
        dialogClass: 'center-dialog',
        position: 'center',
        open: function() {
            $('.center-dialog').center();
            $('.center-dialog').css('z-index', 1050);
        }
    });
}

function resetTest() {
    $.post(getOption('resetUserTestUrl'),
            {testId: $('#resetUserTestsList option:selected').val()},
    function() {
        $('#resetUserTestsList option:selected').remove();

        if ($('#resetUserTestsList option').length == 0)
            $('#resetTestButton').hide();

        $("#dialogResetTest").dialog("close");
    });
}

function openRetestPopUp(id) {
    thisId = parseInt(id);

    $("#dialogRetest").dialog({
        autoOpen: true,
        dialogClass: 'center-dialog',
        position: 'center',
        open: function() {
            $('.center-dialog').center();
            $('.center-dialog').css('z-index', 1050);
        }
    });
}

function openReaskContractPopUp(id, denied) {
    thisId = parseInt(id);

    $("#dialogReaskContract").dialog({
        autoOpen: true,
        dialogClass: 'center-dialog',
        position: 'center',
        open: function() {
            $('.center-dialog').center();
            $('.center-dialog').css('z-index', 1050);
        }
    });

    $('#dialogReaskContract')
        .find('.on-first').toggleClass('hidden', denied)
        .siblings('.on-repeat').toggleClass('hidden', !denied);
}

function openCloseUserPopUp(id) {
    thisId = parseInt(id);
    $("#dialogCloseUser").dialog({
        autoOpen: true,
        dialogClass: 'center-dialog',
        position: 'center',
        open: function() {
            $('.center-dialog').center();
            $('.center-dialog').css('z-index', 1050);
        }
    });
    setCloseUserReason();
}

function setCloseUserReason()
{
    $('#reasonToCloseInput').val($('#reasonsList option:selected').html());
    $('#dialogCloseUserErrorMessage').html('');
}

function showHideReasonToCloseInput()
{
    if ($('#reasonToCloseInput').css('display') == 'none')
    {
        $('#reasonToCloseInput').show();
        $('#reasonToCloseInput').val('');
    }
    else
    {
        $('#reasonToCloseInput').hide();
        setCloseUserReason();
    }
}

function closeUser() {

    if ($('#reasonToCloseInput').val() == '')
    {
        $('#dialogCloseUserErrorMessage').html('Reason cannot be blank.');
        return;
    }

    $("#dialogCloseUser").dialog("disable");
    $.post(getOption('closeUserUrl'),
            {userId: thisId, reasonToClose: $('#reasonToCloseInput').val()},
    function() {

        $("#dialogCloseUser").dialog("enable");
        $("#dialogCloseUser").dialog("close");

        if (getOption('gridId'))
        {
            refreshGrid(getOption('gridId'));
        }
        else
        {
            //only for statistic onboarding
            $('#closeAccountLink').hide();
            $('#closeAccountLink').removeAttr("id");
        }
    });
}

function retest(comment) {
    var params = {};
    params['userId'] = thisId;
    params['comment'] = comment;

    $.post(getOption('retestUrl'), params, function() {
        refreshGrid('aprove-users-grid');

        if ($("#dialogResults").dialog("isOpen") === true)
            $("#dialogResults").dialog("close");
        else if ($("#dialogRetest").dialog("isOpen") === true)
            $("#dialogRetest").dialog("close");
    });
}

function resetContract() {
    $("#dialogReaskContract").dialog("disable");
    $.post(getOption('resetContractUrl'), {userId: thisId}, function() {
        $("#dialogReaskContract").dialog("enable");
        $("#dialogReaskContract").dialog("close");
        refreshGrid('aprove-users-grid');
    });
}

function openInvoicingPopup(user_id, sum, bonus) {
    $("#dialogInvoicing").dialog("open");
    $("#amount").val(sum);
    $("#bonus").val(bonus);
    $("#userId").val(user_id);
}

function saveInvoice() {
    $('.showError').html('');
    if ($("#amount").val() != "" && $("#bonus").val() != "" && !isNaN($("#amount").val()) && !isNaN($("#bonus").val())) {
        $("#dialogInvoicing").dialog("close");
        user_payments[$("#userId").val()] = {amount: $("#amount").val(), bonus: $("#bonus").val()};
        $('.user_id').filter(function(i) {
            return $.text([this]) == $("#userId").val();
        }).siblings('.payable').text($("#amount").val());
        $('.user_id').filter(function(i) {
            return $.text([this]) == $("#userId").val();
        }).siblings('.bonus').text($("#bonus").val());
    } else {
        if ($("#amount").val() == "" || isNaN($("#amount").val())) {
            $("#amount").siblings('.showError:first').html('Please enter number');
        }
        if ($("#bonus").val() == "" || isNaN($("#bonus").val())) {
            $("#bonus").siblings('.showError:first').html('Please enter number');
        }
    }
}

function loadingProgress()
{
    $("#loadingProgress").activity();
}

/**
 * Copy summery text from grid
 * to the div outside of the
 * grid
 *

 */
function updateSummaryText(gridId, copyTextId) {
    $("#" + gridId + " .summary").css('display', 'none');
    $("#" + copyTextId).empty();
    $("#" + copyTextId).append($("#" + gridId + " .summary"));
    $("#" + copyTextId + " .summary").css('display', 'block');
//    $(".clientSearch #" + copyTextId).val($("#" + gridId).html($("#" + gridId + " .summary").text()));
}

/**
 * clear filters
 * of a form
 *
 * @author Alexey K
 */
function clearFormFilters(formId) {
    $('#' + formId + ' input:text').val('');
    $('#' + formId + ' select').val('');
    $('#' + formId).submit();
}

function validatePhoneInput(obj)
{
    //ltrim text if needed
    var re = /^\s+/g;
    if (re.test($(obj).val()))
        $(obj).val($(obj).val().replace(re, ""));

    //remove multiple spaces
    re = /[\s]{2,}/g;
    if (re.test($(obj).val()))
        $(obj).val($(obj).val().replace(re, " "));

    //allow only digits and space
    re = /^[0-9\s]*$/;
    if (!re.test($(obj).val()))
        $(obj).val($(obj).val().replace(/[^0-9\s]/g, ""));
}

function adminTakeTest()
{
    $.post(getOption('adminTakeTestUrl') + '?check=true', function(data) {
        var response = JSON.parse(data);

        if (response.status)
            location.href = getOption('adminTakeTestUrl');
        else
        {
            if (response.message)
                alert(response.message);
        }
    });
}

function reactivateProject(projectId)
{
    $.post(getOption('reactivateProjectUrl') + '?projectId=' + projectId, function(data) {
        var response = JSON.parse(data);

        if (response.status == 'error')
            alert(response.message);
        else
            refreshGrid('project-grid');
    });
}

function reactivateProjectJob(projectJobId)
{
    $.post(getOption('reactivateProjectJobUrl') + '?projectJobId=' + projectJobId, function(data) {
        var response = JSON.parse(data);

        if (response.status == 'error')
            alert(response.message);
        else
            refreshGrid('projectJobs');
    });
}

// Update user password
function updateUserPassword(userId) {
    var password = $('#new_password').val();

    $.post(getOption('updateUserUrl'),
            {userId: userId, password: password},
    function(data) {
        var response = JSON.parse(data);
		var pswInp = $('#editUserPassword');
        if (response.status == "error") {
            $(pswInp).addClass('errorMessage');
			$(pswInp).attr('rel', 'tooltip');
			$(pswInp).attr('data-original-title', response.errors.password);
        } else {
			$(pswInp).removeClass('errorMessage');			
			$(pswInp).removeAttr('rel');
            $('#new_password').val('');			
        }

    });
}

// Change generation of payments
function changeGeneratePayment(obj, userId) {
    var obj = $(obj),
        input = obj.find('input'),
        generate_payment = 1;

    if (input.prop('checked')) {
        generate_payment = 0;
    }

    if (!confirm('Are you sure?')) {
        return false;
    }
    
    $.post(getOption('updateGeneratePayment'), {
        userId: userId, 
        generate_payment: generate_payment
    }, function(data) {
        input.prop('checked', generate_payment);
    });

    return false;
}


function approveUser(userId){
    if(!confirm("Approve user?")) {
        return false;
    }

    $.get(getOption('takeAllTests'),
        {
            userId: userId,
            ignoreCheck: 1
        },
        function(data) {
            var response = JSON.parse(data);

            if(response.error) {
                alert(response.error);
                return false;
            }

            window.location.reload();
        });
}