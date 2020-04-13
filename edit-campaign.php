<?php
require_once 'layout/header.php';
?>

<?php
$id = $_GET['id'];
$dbModel = new DbModel();
$campaign = $dbModel->get_campaign_by_id($id);
if (!$campaign) {
    echo '404';
    exit;
}
$campaign_id = $campaign['id'];
$settings = $dbModel->get_campaign_options_all($campaign_id);

?>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Campaign</div>
                <div class="panel-body">
                    <div class="row show-grid">
                        <form method="POST" id="update_campaign_form">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tên chiến dịch</label>
                                        <input class="form-control" name="name" value="<?php echo $campaign['name'] ?>"
                                               maxlength="256" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số lần Verify</label>
                                        <input class="form-control" name="verify_number"
                                               value="<?php echo $campaign['verify_number'] ?>" required
                                               placeholder="Nhập Số lần Verify">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Landing Page URL</label>
                                        <input class="form-control" name="landing_page"
                                               value="<?php echo urldecode($campaign['landing_page']) ?>"
                                               maxlength="512"
                                               placeholder="Nhập trang đích của chiến dịch">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Custom CSS</label>
                                        <textarea class="form-control" name="custom_css"
                                                  placeholder=".btn-verify { color: red; }"><?php echo $campaign['custom_css'] ?? '' ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Button Text</label>
                                        <input class="form-control" name="btn_text"
                                               value="<?php echo $campaign['btn_text'] ?>"
                                               placeholder="Nhập Button Text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="id" value="<?php echo $campaign['id'] ?>"/>
                                <input type="hidden" name="action" value="update_campaign"/>
                                <button type="submit" class="btn btn-success" value="submit">Update</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Type Settings</div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="group-tabs">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#generated-videos"
                                                                              aria-controls="generated-videos"
                                                                              role="tab"
                                                                              data-toggle="tab">Generated Videos</a>
                                    </li>
                                    <li role="presentation"><a href="#generated-comment-links"
                                                               aria-controls="generated-comment-links" role="tab"
                                                               data-toggle="tab">Generated Comment links</a></li>
                                    <li role="presentation"><a href="#specific-video"
                                                               aria-controls="specific-video" role="tab"
                                                               data-toggle="tab">Specific Video</a></li>
                                    <li role="presentation"><a href="#specific-comment-link"
                                                               aria-controls="specific-comment-link" role="tab"
                                                               data-toggle="tab">Specific Comment Link</a></li>
                                    <li role="presentation"><a href="#custom-tasks"
                                                               aria-controls="custom-tasks" role="tab"
                                                               data-toggle="tab">Custom Tasks</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="generated-videos">
                                        <div class="panel-body">
                                            <?php renderOptionItem($campaign_id, 1, $settings) ?>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="generated-comment-links">
                                        <div class="panel-body">
                                            <?php renderOptionItem($campaign_id, 2, $settings) ?>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="specific-video">
                                        <div class="panel-body">
                                            <?php renderOptionItem($campaign_id, 3, $settings) ?>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="specific-comment-link">
                                        <div class="panel-body">
                                            <?php renderOptionItem($campaign_id, 4, $settings) ?>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="custom-tasks">
                                        <div class="panel-body">
                                            <?php renderOptionItem($campaign_id, 5, $settings) ?>
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


    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#update_campaign_form").submit(function (event) {
                event.preventDefault();
                var values = $(this).serialize();
                $.ajax({
                    url: "action.php",
                    type: "post",
                    data: values,
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        if (response.status === "1") {
                            alert("Cập nhật thành công.");
                            window.location.reload();
                            // var r = confirm("Update successful. Back to list.");
                            // if (r == true) {
                            //     window.location.href = 'campaign-list.php';
                            // } else {
                            //     return;
                            // }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

            $(".setup_type_setting").submit(function (event) {
                event.preventDefault();
                var values = $(this).serialize();
                $.ajax({
                    url: "action.php",
                    type: "post",
                    data: values,
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        if (response.status === 1 || response.status === "1") {
                            alert("Cập nhật thành công.");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });
        });
    </script>

<?php
require_once 'layout/footer.php';
?>


<?php
function renderOptionItem($campaign_id, $type, $settings)
{
    $temp = array_filter($settings, function ($item) use ($type) {
        return $item['type'] == $type;
    });
    $mapSettings = [];
    foreach ($temp as $item) {
        $mapSettings[$item['key']] = $item['value'];
    }
    ?>
    <form method="POST" class="setup_type_setting">
        <div class="col-md-12">
            <div class="form-group">
                <label>Số Items</label>
                <input class="form-control" type="number" min="0" name="items_number"
                       value="<?php echo $mapSettings['items_number'] ?? 0 ?>" required
                       placeholder="Nhập số items hiển thị">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Header HTML</label>
                <textarea class="form-control" name="header_html" rows="8"
                          placeholder="Nhập Header HTML"><?php echo $mapSettings['header_html'] ?? '' ?></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <input type="hidden" name="campaign_id"
                   value="<?php echo $campaign_id ?>"/>
            <input type="hidden" name="action" value="setup_type_setting"/>
            <input type="hidden" name="type" value="<?php echo $type ?>"/>
            <button type="submit" class="btn btn-success" value="submit">Setup
            </button>
            <button type="reset" class="btn btn-default">Reset</button>
        </div>
    </form>
    <?php
}

?>