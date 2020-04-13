<?php
    require_once 'layout/header.php';
?>

<?php
    $url_id = $_GET['id'];
    $dbModel = new DbModel();
    $item = $dbModel->get_keyword_by_id($url_id);

    $campaign_id = $_REQUEST['campaign_id'];
    
    ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit Comment</div>
                        <div class="panel-body">
                            <div class="row show-grid">
                                <form method="POST" id="update_comment_form">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Content</label>
                                            <input class="form-control" id="content" name="content" required value="<?php echo $item['content'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
                                        <input type="hidden" name="action" value="update_keyword"/>
                                        <button type="submit" class="btn btn-success" value="submit">Update</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <footer class="page-footer font-small teal pt-4">
                <div class="footer-copyright py-3" style='text-align: right'>© 2018 Developer by
                    <a href='skype:live:tuandao.dev?chat'> Tuan Dao</a>
                </div>
            </footer>
        </div>

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/custom.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#update_comment_form").submit(function(event){
                    event.preventDefault();
                    var values = $(this).serialize();
                    $.ajax({
                        url: "action.php",
                        type: "post",
                        data: values,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === "1") {
                                var r = confirm("Update successful. Back to list.");
                                if (r == true) {
                                    window.location.href = 'keyword-list.php?campaign_id=<?php echo $campaign_id ?>';
                                } else {
                                    return;
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                           console.log(textStatus, errorThrown);
                        } 
                    });
                });
            });
        </script>

<?php
require_once 'layout/footer.php';
?>
