/*
 * $Id$  
 * Makhtar Diouf , javascript/jQuery hooks
 */
var postMsg = '';
var targetIds = [];
var targetNames = ["Default"];

function show(id) {
    $("#" + id).show();
}

function showMsg(msg) {
    $("#message").html(msg);
}

function loadContent(page) {
    $("#content").load(page);
    // $("#maincontent").load(page);
}

function loadGroupPop(page) {
    $("#groupsTable").load(page);   
}

// Remove duplicates in arrays
function unique(array) {
    return $.grep(array, function (el, i) {
        return i === $.inArray(el, array);
    });
}

/***** jQuery Entry Point ****/
$(document).ready(function () {

    // Add contact to selected groups
    $("input:checkbox").on("click", function () {
        try {
            checkbox = this.valueOf();
            n = $("input:checked").val();

            targetNames.push(n);
            targetIds.push(checkbox.id);
            console.log("Checked name " + n);

            n = $("#groups").text();
            var id = "";
            targetNames = unique(targetNames);
            targetIds = unique(targetIds);

            for (var i = 0; i < targetNames.length; i++) {
                if (targetNames[i] && targetNames[i].length > 0) {
                    n += targetNames[i] + ", ";
                    // $("#groups").append(n);
                }
                if (targetIds[i] && targetIds[i].length > 0)
                    id += targetIds[i] + ",";
            }
            $("#groups").val(n);
            $("#groupIds").val(id);
        } catch (err) {
            console.log("Error " + err.message);
        }
    });

    $("#addGroupBt").click(function () {
        show('groupIds');
    });


    // Async deletion of contacts
    $("#deleteBt").click(function () {
        var ok;
        for (var i = 0; i < targetIds.length; i++) {

            $.post("index.php?action=delete&id=" + targetIds[i], function (status) {
                ok = status;
                showMsg("Deleted Contact Id " + targetIds[i]);
            });
        }
        loadContent("index.php?action=list");
    });


    // Async deletion of Groups
    $("#deleteGroupBt").click(function () {
        var ok;
        for (var i = 0; i < targetIds.length; i++) {

            $.post("index.php?action=group_delete&id=" + targetIds[i], function (status) {
                ok = status;
                showMsg("Deleted Group Id " + targetIds[i]);
            });
        }
        loadContent("index.php?action=group_list");
    });

});
