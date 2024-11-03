<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-lists"></i> Quản Lý Xuất Kiểm Kê
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-purple">
                    <div class="box-header">
                        <h3 class="box-title">Danh Sách Phiếu Kiểm</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>stocktaking" method="POST" id="searchList">
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
                                <th>Kho Kiểm Kê </th>
                                <th>Người Kiểm Kê</th>
                                <th>Ngày Kiểm Kê</th>
                                <th>Trạng thái</th>
                            </tr>
                            <?php
                            if (!empty($inventoryReports)) {
                                foreach ($inventoryReports as $in) {
                            ?>
                            <tr
                                onclick="window.location.href='<?php echo base_url() ?>stocktaking/detail/<?php echo $in->id ?>'">
                                <td>Kho Nguyên Vật Liệu</td>
                                <td><?php echo $in->name; ?></td>
                                <td><?php echo $in->stocktakingdtm; ?></td>
                                <?php
                                        if ($in->status == 0) {
                                            echo "<td style='color: #99022a;font-weight: bold;'>Đang Chờ Xác Nhận</td>";
                                        } elseif ($in->status == 1) {
                                            echo "<td style='color: #000da6;font-weight: bold;'>Chuẩn Bị Kiểm Kê</td>";
                                        } elseif ($in->status == 2) {
                                            echo "<td style='color: #337ab7;font-weight: bold;'>Đang Kiểm Kê</td>";
                                        } elseif ($in->status == 3) {
                                            echo "<td style='color: #e91e63;font-weight: bold;'>Hoàn Thành</td>";
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
        jQuery("#searchList").attr("action", baseURL + "stocktaking/" + value);
        jQuery("#searchList").submit();
    });
});
</script>