<?php
require_once 'layout/header.php';
?>

<?php
$dbModel = new DbModel();
$campaign_id = $_REQUEST['campaign_id'];

$campaign = $dbModel->get_campaign_by_id($campaign_id);
if (isset($campaign['id']) && !empty($campaign['id'])) {
    //
} else {
    echo 'Missing Campaign Id';
    exit;
}

$list = $dbModel->get_all_group($campaign_id);


?>

    <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Add Content</div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="group-tabs">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#generated-videos"
                                                                          aria-controls="generated-videos" role="tab"
                                                                          data-toggle="tab">Generated Videos</a></li>
                                <li role="presentation"><a href="#generated-comment-links"
                                                           aria-controls="generated-comment-links" role="tab"
                                                           data-toggle="tab">Generated Comment links</a></li>
                                <li role="presentation"><a href="#specific-video"
                                                           aria-controls="specific-video" role="tab"
                                                           data-toggle="tab">Specific Video</a></li>
                                <li role="presentation"><a href="#specific-comment-link"
                                                           aria-controls="specific-comment-link" role="tab"
                                                           data-toggle="tab">Specific Comment Link</a></li>
                                <li role="presentation">
                                    <a href="#custom-tasks-1" aria-controls="custom-tasks-1" role="tab" data-toggle="tab">Custom
                                        Tasks 1</a>
                                </li>
                                <li role="presentation">
                                    <a href="#custom-tasks-2" aria-controls="custom-tasks-2" role="tab" data-toggle="tab">Custom
                                        Tasks 2</a>
                                </li>
                                <li role="presentation">
                                    <a href="#custom-tasks-3" aria-controls="custom-tasks-3" role="tab" data-toggle="tab">Custom
                                        Tasks 3</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="generated-videos">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group keyword">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Keyword List</label>
                                                    <textarea class="form-control" name="keyword_list" required
                                                              placeholder="Nhập danh sách keyword"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="1"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="generated-comment-links">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group keyword">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Keyword List</label>
                                                    <textarea class="form-control" name="keyword_list" required
                                                              placeholder="Nhập danh sách keyword"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="2"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="specific-video">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Video Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group keyword">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Video URL</label>
                                                    <input class="form-control" type="url" name="url" value="" required
                                                           placeholder="Nhập video URL"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="3"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="specific-comment-link">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Comment Link Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group comment link">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Comment URL</label>
                                                    <input class="form-control" type="url" name="url" value="" required
                                                           placeholder="Nhập youtube comment URL"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="4"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="custom-tasks-1">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Custom Task Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Custom HTML Column</label>
                                                    <textarea class="form-control" name="custom_html"
                                                              placeholder="Nhập HTML column"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="5"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="custom-tasks-2">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Custom Task Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Custom HTML Column</label>
                                                    <textarea class="form-control" name="custom_html"
                                                              placeholder="Nhập HTML column"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="6"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="custom-tasks-3">
                                    <div class="panel-body">
                                        <form method="POST" class="add_form">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Custom Task Group Name</label>
                                                    <input class="form-control" name="group_name" required value=""
                                                           placeholder="Nhập tên group">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Custom HTML Column</label>
                                                    <textarea class="form-control" name="custom_html"
                                                              placeholder="Nhập HTML column"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Comment List</label>
                                                    <textarea class="form-control" name="comment_list" required
                                                              placeholder="Nhập danh sách comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="campaign_id"
                                                       value="<?php echo $campaign['id'] ?>"/>
                                                <input type="hidden" name="action" value="add_campaign_content"/>
                                                <input type="hidden" name="type" value="7"/>
                                                <button type="submit" class="btn btn-success" value="submit">Add
                                                </button>
                                                <button type="reset" class="btn btn-default">Reset</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                <div class="panel-heading">Content List
                </div>
                <div class="panel-body">
                    <table id="list_links" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Type</th>
                            <th>Group Name</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($list as $item) {
                            ?>
                            <tr id="urlItem_<?php echo $item['id'] ?>">
                                <td><?php echo $item['id'] ?></td>
                                <td><?php echo campaign_type_name($item['type']) ?></td>
                                <td><?php echo $item['group_name'] ?></td>
                                <td class="text-center">
                                    <a href="edit-content.php?group_id=<?php echo $item['id'] ?>&campaign_id=<?php echo $campaign_id ?>"
                                       class="btn btn-xs btn-primary" title="Edit item">Edit</a>
                                    <button type="button" class="btn btn-xs btn-danger" title="Delete this item"
                                            onclick="deleteItem('<?php echo $item['id'] ?>')">Delete
                                    </button>
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

            $(".add_form").submit(function (event) {
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
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

            $('#list_links').DataTable({
                "order": [[0, "desc"]]
            });
        });

        function deleteItem(id) {

            var r = confirm("Do you want to delete this item?");
            if (r == true) {

            } else {
                return;
            }
            var data = {};
            data.id = id;
            data.action = 'delete_group';

            $.ajax({
                url: "action.php",
                type: "post",
                data: data,
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.status == 1) {
                        console.log("delete");
                        console.log("#urlItem_".id);
                        $("#urlItem_" + id).remove();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
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

function campaign_type_name($type)
{
    $names = ['Generated Videos', 'Generated Comment Links', 'Specific Video', 'Specific Comment Link', 'Custom Tasks 1', 'Custom Tasks 2', 'Custom Tasks 3'];
    return $names[$type - 1] ?? 'Unknown';
}