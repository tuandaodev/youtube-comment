<?php
    require_once 'layout/header.php';
?>

<?php
    $dbModel = new DbModel();
    $list = $dbModel->get_all_campaign();
?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">New Campaign
                        </div>
                        <div class="panel-body">
                            <div class="row show-grid">
                                <form method="POST" id="add_comment">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên chiến dịch</label>
                                            <input class="form-control" name="name" value="" maxlength="256" placeholder="Nhập tên chiến dịch" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số lần Verify</label>
                                            <input class="form-control" name="verify_number" type="number" min="0" value="1" required placeholder="Nhập Số lần Verify">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Landing Page URL</label>
                                            <input class="form-control" type="url" name="landing_page" value="" maxlength="512" placeholder="Nhập trang đích của chiến dịch">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Button Text</label>
                                            <input class="form-control" name="btn_text" value="Verify" placeholder="Nhập Button Text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Custom CSS</label>
                                            <textarea class="form-control" name="custom_css" placeholder="Nhập CSS"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="action" value="add_campaign"/>
                                        <button type="submit" class="btn btn-success" value="submit">Submit</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    </div>
                                </form>
                                <div class="col-md-12" style="margin-top: 20px;">
                                    <div class="form-group" id="result">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">List
                        </div>
                        <div class="panel-body">
                                <table id="list_links" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Verify</th>
                                            <th>Btn Text</th>
                                            <th>Landing Page</th>
                                            <th>Count</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($list as $item) {
                                        ?>
                                            <tr id="urlItem_<?php echo $item['id'] ?>">
                                                <td><?php echo $item['id'] ?></td>
                                                <td><?php echo $item['name'] ?></td>
                                                <td><?php echo $item['verify_number'] ?></td>
                                                <td><?php echo $item['btn_text'] ?></td>
                                                <td><?php echo urldecode($item['landing_page']) ?></td>
                                                <td><?php echo $item['count_items'] ?? 0 ?></td>
                                                <td class="text-center">
                                                    <a href="edit-campaign.php?id=<?php echo $item['id'] ?>" class="btn btn-primary" title="Edit item">Edit</a>
                                                    <a href="campaign-content.php?campaign_id=<?php echo $item['id'] ?>" class="btn btn-success" title="Manage Content">Manage</a>
                                                    <button type="button" class="btn btn-danger" title="Delete this item" onclick="deleteItem('<?php echo $item['id'] ?>')">Delete</button>
                                                    <button type="button" class="btn btn-primary" title="Clone this item" onclick="cloneItem('<?php echo $item['id'] ?>')">Clone</button>
                                                    <a href="export_zip.php?campaign_id=<?php echo $item['id'] ?>" class="btn btn-success" title="Export this item">Export</a>
                                                    <a href="verify.php?cid=<?php echo $item['id'] ?>" target="_blank" class="btn btn-warning" title="Open">Open</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function () {

        $("#add_comment").submit(function(event){
            event.preventDefault();
            var values = $(this).serialize();
            $.ajax({
                url: "action.php",
                type: "post",
                data: values,
                dataType: 'json',
                success: function (response) {
                    if (response.status == 1) {
                        var html = '<div class="alert alert-success">' + response.html + '</div>'
                        $("#result").html(html);
                        location.reload();
                    } else {
                        var html = '<div class="alert alert-danger">' + response.html + '</div>'
                        $("#result").html(html);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        $('#list_links').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
    });

    function deleteItem(id) {

        var r = confirm("Do you want to delete this item?");
        if (r == true) {

        } else {
            return;
        }
        var data = {};
        data.id = id;
        data.action = 'delete_campaign';

        $.ajax({
            url: "action.php",
            type: "post",
            data: data,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.status == 1) {
                    console.log("delete");
                    console.log("#urlItem_" . id);
                    $("#urlItem_" + id).remove();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    };

    function cloneItem(id) {

        var r = confirm("Do you want to clone this campaign?");
        if (r == true) {
        } else {
            return;
        }

        var data = {};
        data.id = id;
        data.action = 'clone_campaign';

        $.ajax({
            url: "action.php",
            type: "post",
            data: data,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.status == 1) {
                    alert('Clone success');
                    window.location.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    };

</script>
<style>
    table.table-bordered.dataTable tbody td {
        word-break: break-word;
    }
</style>

<?php
require_once 'layout/footer.php';