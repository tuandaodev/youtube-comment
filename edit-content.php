<?php
require_once 'layout/header.php';
?>

<?php
$dbModel = new DbModel();
$group_id = $_REQUEST['group_id'];
$campaign_id = $_REQUEST['campaign_id'];

$group = $dbModel->get_group_by_id($group_id);
if (!$group) {
    echo "NOT FOUND ITEM";
    exit;
}
$type = $group['type'] ?? 0;


?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Content</div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="group-tabs">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" <?php disableTabClass($type, 1) ?> ><a <?php disableTabClass($type, 1) ?> href="#generated-videos"
                                                                                                                                      aria-controls="generated-videos" role="tab"
                                                                                                                                      data-toggle="tab">Generated Videos</a></li>
                                    <li role="presentation" <?php disableTabClass($type, 2) ?> ><a <?php disableTabClass($type, 2) ?> href="#generated-comment-links"
                                                                                                                                      aria-controls="generated-comment-links" role="tab"
                                                                                                                                      data-toggle="tab">Generated Comment links</a></li>
                                    <li role="presentation" <?php disableTabClass($type, 3) ?> ><a <?php disableTabClass($type, 3) ?> href="#specific-video"
                                                                                                                                      aria-controls="specific-video" role="tab"
                                                                                                                                      data-toggle="tab">Specific Video</a></li>
                                    <li role="presentation" <?php disableTabClass($type, 4) ?> ><a <?php disableTabClass($type, 4) ?> href="#specific-comment-link"
                                                                                                                                      aria-controls="specific-comment-link" role="tab"
                                                                                                                                      data-toggle="tab">Specific Comment Link</a></li>
                                    <li role="presentation" <?php disableTabClass($type, 5) ?> ><a <?php disableTabClass($type, 5) ?> href="#custom-tasks"
                                                                                                                                      aria-controls="custom-tasks" role="tab"
                                                                                                                                      data-toggle="tab">Custom Tasks</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane <?php activeTab($type, 1) ?>" id="generated-videos">
                                        <div class="panel-body">
                                            <form method="POST" class="update_form">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Group Name</label>
                                                        <input class="form-control" name="group_name" required value="<?php echo $group['group_name'] ?? '' ?>"
                                                               placeholder="Nhập tên group keyword">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Keyword List</label>
                                                        <textarea rows="10" class="form-control" name="keyword_list" required
                                                                  placeholder="Nhập danh sách keyword"><?php echo $group['keyword_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Comment List</label>
                                                        <textarea rows="10" class="form-control" name="comment_list" required
                                                                  placeholder="Nhập danh sách comment"><?php echo $group['comment_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $group['id'] ?? 0 ?>"/>
                                                    <input type="hidden" name="action" value="update_campaign_content"/>
                                                    <input type="hidden" name="type" value="1"/>
                                                    <button type="submit" class="btn btn-success" value="submit">Update
                                                    </button>
                                                    <button type="reset" class="btn btn-default">Reset</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane <?php activeTab($type, 2) ?>" id="generated-comment-links">
                                        <div class="panel-body">
                                            <form method="POST" class="update_form">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Group Name</label>
                                                        <input class="form-control" name="group_name" required value="<?php echo $group['group_name'] ?? '' ?>"
                                                               placeholder="Nhập tên group keyword">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Keyword List</label>
                                                        <textarea rows="10" class="form-control" name="keyword_list" required
                                                                  placeholder="Nhập danh sách keyword"><?php echo $group['keyword_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Comment List</label>
                                                        <textarea rows="10" class="form-control" name="comment_list" required
                                                                  placeholder="Nhập danh sách comment"><?php echo $group['comment_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $group['id'] ?? 0 ?>"/>
                                                    <input type="hidden" name="action" value="update_campaign_content"/>
                                                    <input type="hidden" name="type" value="2"/>
                                                    <button type="submit" class="btn btn-success" value="submit">Update
                                                    </button>
                                                    <button type="reset" class="btn btn-default">Reset</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane <?php activeTab($type, 3) ?>" id="specific-video">
                                        <div class="panel-body">
                                            <form method="POST" class="update_form">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Video Group Name</label>
                                                        <input class="form-control" name="group_name" required value="<?php echo $group['group_name'] ?? '' ?>"
                                                               placeholder="Nhập tên group keyword">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Video URL</label>
                                                        <input class="form-control" name="url" value="<?php echo urldecode($group['url']) ?? '' ?>" required
                                                               placeholder="Nhập video URL"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Comment List</label>
                                                        <textarea rows="10" class="form-control" name="comment_list" required
                                                                  placeholder="Nhập danh sách comment"><?php echo $group['comment_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $group['id'] ?? 0 ?>"/>
                                                    <input type="hidden" name="action" value="update_campaign_content"/>
                                                    <input type="hidden" name="type" value="3"/>
                                                    <button type="submit" class="btn btn-success" value="submit">Update
                                                    </button>
                                                    <button type="reset" class="btn btn-default">Reset</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane <?php activeTab($type, 4) ?>" id="specific-comment-link">
                                        <div class="panel-body">
                                            <form method="POST" class="update_form">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Comment Link Group Name</label>
                                                        <input class="form-control" name="group_name" required value="<?php echo $group['group_name'] ?? '' ?>"
                                                               placeholder="Nhập tên group comment link">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Comment URL</label>
                                                        <input class="form-control" name="url" value="<?php echo urldecode($group['url']) ?? '' ?>" required
                                                               placeholder="Nhập youtube comment URL"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Comment List</label>
                                                        <textarea rows="10" class="form-control" name="comment_list" required placeholder="Nhập danh sách comment"><?php echo $group['comment_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $group['id'] ?? 0 ?>"/>
                                                    <input type="hidden" name="action" value="update_campaign_content"/>
                                                    <input type="hidden" name="type" value="4"/>
                                                    <button type="submit" class="btn btn-success" value="submit">Update
                                                    </button>
                                                    <button type="reset" class="btn btn-default">Reset</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane <?php activeTab($type, 5) ?>" id="custom-tasks">
                                        <div class="panel-body">
                                            <form method="POST" class="update_form">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Custom Task Group Name</label>
                                                        <input class="form-control" name="group_name" required value="<?php echo $group['group_name'] ?? '' ?>"
                                                               placeholder="Nhập tên group">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Keyword</label>
                                                        <input class="form-control" name="keyword_list" value="<?php echo $group['keyword_list'] ?? '' ?>" required placeholder="Nhập keyword"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Channel/Website Name</label>
                                                        <input class="form-control" name="channel" value="<?php echo $group['channel'] ?? '' ?>"
                                                               required placeholder="Nhập tên Channel/Website"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Comment List</label>
                                                        <textarea rows="10" class="form-control" name="comment_list" required
                                                                  placeholder="Nhập danh sách comment"><?php echo $group['comment_list'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo $group['id'] ?? 0 ?>"/>
                                                    <input type="hidden" name="action" value="update_campaign_content"/>
                                                    <input type="hidden" name="type" value="5"/>
                                                    <button type="submit" class="btn btn-success" value="submit">Update
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
    </div>


    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
                if ($(this).hasClass("disabled")) {
                    e.preventDefault();
                    return false;
                }
            });
            $(".update_form").submit(function (event) {
                event.preventDefault();
                var values = $(this).serialize();
                $.ajax({
                    url: "action.php",
                    type: "post",
                    data: values,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == "1" || response.status == 1) {
                            var r = confirm("Update successful. Back to campaign content list.");
                            if (r == true) {
                                window.location.href = 'campaign-content.php?campaign_id=<?php echo $campaign_id; ?>';
                            } else {
                                return;
                            }
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

function campaign_type_name($type) {
    $names = ['Generated Videos', 'Generated Comment Links', 'Specific Video', 'Specific Comment Link', 'Custom Tasks'];
    return $names[$type-1] ?? 'Unknown';
}

function disableTabClass($type, $currentType) {
    if ($type == $currentType) echo 'class="active"';
    else echo 'class="disabled"';
}

function activeTab($type, $currentType) {
    if ($type == $currentType) echo 'active';
}