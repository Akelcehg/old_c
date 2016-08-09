/**
 * Created by alks on 11.05.2016.
 */


$(function () {

    $(document.body).on('click', '.emDrillDown', drillDown);
});


function drillDown(e) {

    var link = $(e && e.tagName ? e : this),
    //cell = link.closest('td'),
        row = link,
        func = link.attr('data-func');
    data = link.attr('all_id');


    // state of selected row
    if (row.hasClass('selected-' + func)) {

        row.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
            .toggleClass('selected', row.children('.active-cell').length)
            .nextAll('.appended-row-main-' + func + ':first').remove();

    } else {

        var spanColumn = (row.children('td:visible').length || 12),
        // filterData = $("#filter-form").serializeFormJSON(),
            cTitleId = row.find('.title_id').html(),
            cSeasonId = row.find('.season_id').html(),
            cChildrenTitleId = row.find('.children_title_id').html(),
            cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

        // remove all loading content
        row.nextAll('.loading-row').remove();

        // let add new one
        row.after('<tr class="loading-row"><td colspan="' + spanColumn + '">' + getLoadingIndicator() + '</td></tr>');

        // toggle state
        row.addClass('selected selected-' + func)
            .children('.active-cell').removeClass('loaded');

        row.addClass('active-cell loaded');

        $.get(app.baseUrl+'/prom/correctors/getdiagnostic', {
            all_id: data,
        }, function (htmlData) {

            // remove all loading content
            row.nextAll('.loading-row').remove();

            // insert new one
            row
                .after('<tr class="appended-row appended-row-main-' + func + '" data-func="' + func + '">' +
                    '<td colspan="' + spanColumn + '"  all_id="'+data+'">' +
                    htmlData +
                    '</td>' +
                    '</tr>');

            //set as loaded
            row.children('.active-cell').addClass('loaded');
            CounterAddressSearch='';
        });
    }
}

function getLoadingIndicator(className) {
    return '<div' + (className ? ' class="' + className + '"' : '') + ' style="text-align: center">'
        + '<span>Loading...</span>'
        + '<img src="' + BASE_URL + '/images/loading-icon.gif" width="15" height="15">'
        + '</div>';
}