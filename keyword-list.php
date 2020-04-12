<?php
    require_once 'layout/header.php';
?>

<?php
    $dbModel = new DbModel();
    $list = $dbModel->get_all_keyword();
?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">New Keyword
                        </div>
                        <div class="panel-body">
                            <div class="row show-grid">
                                <form method="POST" id="add_form">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Keyword</label>
                                            <input class="form-control" id="content" name="content" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="action" value="add_keyword"/>
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
                        <div class="panel-heading">Comment List
                        </div>
                        <div class="panel-body">
                                <table id="list_links" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Content</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($list as $item) {
                                        ?>
                                            <tr id="urlItem_<?php echo $item['id'] ?>">
                                                <td><?php echo $item['id'] ?></td>
                                                <td><?php echo $item['content'] ?></td>
                                                <td class="text-center">
                                                    <a href="edit-keyword.php?id=<?php echo $item['id'] ?>" class="btn btn-xs btn-primary" title="Edit item">Edit</a><button type="button" class="btn btn-xs btn-danger" title="Delete this item" onclick="deleteItem('<?php echo $item['id'] ?>')">Delete</button>
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

        $("#add_form").submit(function(event){
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
        data.action = 'delete_keyword';

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
</script>
<style>
    table.table-bordered.dataTable tbody td {
        word-break: break-word;
    }
</style>

<?php
require_once 'layout/footer.php';