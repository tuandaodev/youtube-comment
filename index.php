<?php
require_once 'layout\header.php';

$dbModel = new DbModel();
if (isset($_POST['action']) && !empty($_POST['action'])) {
    switch ($_POST['action']) {
        case 'option_main':
            $dbModel->update_option('verify_number', $_POST['verify_number']);
            $dbModel->update_option('items_number', $_POST['items_number']);
            break;
        case 'option_type_1':
            $dbModel->update_option('help_1_image', $_POST['help_1_image']);
            $dbModel->update_option('help_1_video', $_POST['help_1_video']);
            break;
        case 'option_type_2':
            $dbModel->update_option('help_2_image', $_POST['help_2_image']);
            $dbModel->update_option('help_2_video', $_POST['help_2_video']);
            break;
    }
}

$options = $dbModel->get_options();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Cấu hình
            </div>
            <div class="panel-body">
                <div class="row show-grid">
                    <form method="POST" id="find_links_form">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số lần Verify</label>
                                <input class="form-control" name="verify_number" value="<?php echo $options['verify_number'] ?? VERIFY_TIME ?>" placeholder="Nhập Số lần Verify">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số Items</label>
                                <input class="form-control" name="items_number" value="<?php echo $options['items_number'] ?? MAX_ITEMS ?>" placeholder="Nhập số items hiển thị">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="action" value="option_main"/>
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
            <div class="panel-heading">Hướng dẫn cho Video
            </div>
            <div class="panel-body">
                <div class="row show-grid">
                    <form method="POST" id="find_links_form">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ảnh hướng dẫn</label>
                                <input class="form-control" name="help_1_image" value="<?php echo $options['help_1_image'] ?? '' ?>" placeholder="Nhập link ảnh">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Video Hướng dẫn</label>
                                <textarea class="form-control" name="help_1_video" placeholder="Nhập iframe youtube"><?php echo urldecode($options['help_1_video']) ?? '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="action" value="option_type_1"/>
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
            <div class="panel-heading">Hướng dẫn cho Comment Link
            </div>
            <div class="panel-body">
                <div class="row show-grid">
                    <form method="POST" id="find_links_form">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ảnh hướng dẫn</label>
                                <input class="form-control" name="help_2_image" value="<?php echo $options['help_2_image'] ?? '' ?>" placeholder="Nhập link ảnh">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Video Hướng dẫn</label>
                                <textarea class="form-control" name="help_2_video" placeholder="Nhập iframe youtube"><?php echo urldecode($options['help_2_video']) ?? '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="action" value="option_type_2"/>
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

<?php
require_once 'layout/footer.php';
?>
