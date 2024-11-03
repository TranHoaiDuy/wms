<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-lists"></i> Quản Lý Xuất Kho
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-purple">
                    <div class=" box-header">
                        <h3 class="box-title">Danh Sách Phiếu Nhận</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>goods-issue" method="POST" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value=""
                                        class="form-control input-sm pull-right" style="width: 150px;"
                                        placeholder="Search" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default searchList"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Ngày Tạo</th>
                                <th>Mã phiếu </th>
                                <th>Người Lập Phiếu</th>
                                <th>Phân Xưởng</th>
                                <th>Trạng thái</th>
                            </tr>
                            <?php
                            if (!empty($goodsIssue)) {
                                foreach ($goodsIssue as $issue) {
                            ?>
                            <tr
                                onclick="window.location.href='<?php echo base_url() ?>goods-issue/detail/<?php echo $issue->id ?>'">
                                <td><?php echo  date('Y-m-d', strtotime($issue->createdDtm)); ?></td>
                                <td><?php echo $issue->code; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $issue->name; ?></td>
                                <?php
                                       if ($issue->status == 0) {
                                        echo "<td style='color: #656565;font-weight: bold;'>Đang Khởi Tạo</td>";
                                    } elseif ($issue->status == 1) {
                                        echo "<td style='color: #0014ff;font-weight: bold;'>Đang Xử Lý</td>";
                                    } elseif ($issue->status == 2) {
                                        echo "<td style='color: #ff0000;font-weight: bold;'>Đang Kiểm Hàng</td>";
                                    } elseif ($issue->status == 3) {
                                        echo "<td style='color: #640096;font-weight: bold;'>Đang Xuất</td>";
                                    } elseif ($issue->status == 4) {
                                        echo "<td style='color: #029800;font-weight: bold;'>Hoàn Thành</td>";
                                    }
                                    ?>
                            </tr>
                            <?php }
                            } ?>
                        </table>

                    </div>
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('ul.pagination li a').click(function(e) {
        e.preventDefault();
        var link = jQuery(this).get(0).href;
        var value = link.substring(link.lastIndexOf('/') + 1);
        jQuery("#searchList").attr("action", baseURL + "goods-issue/" + value);
        jQuery("#searchList").submit();
    });
});
</script>