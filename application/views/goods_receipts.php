<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-lists"></i> Quản Lý Nhập Kho
        </h1>
    </section>
    <section class="content">
        <?php if ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLAN ) {  ?>
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-success" href="<?php echo base_url(); ?>create-goods-receipt"><i
                            class="fa fa-plus"></i> Tạo
                        Phiếu Nhập Kho</a>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-purple">
                    <div class="box-header">
                        <h3 class="box-title">Danh Sách Phiếu Nhập Kho</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>goods-receipts" method="POST" id="searchList">
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
                                <th>Mã phiếu nhập</th>
                                <th>Mã Chứng Từ</th>
                                <th>Ngày Chứng Từ</th>
                                <th>Ngay dự kiến giao</th>
                                <th>Trạng thái</th>
                            </tr>
                            <?php
                            if (!empty($goodsReceipts)) {
                                foreach ($goodsReceipts as $record) {
                            ?>
                            <tr
                                onclick="window.location.href='<?php echo base_url() ?>goods-receipts/detail/<?php echo $record->id ?>'">
                                <td><?php echo htmlspecialchars($record->code); ?></td>
                                <td><?php echo htmlspecialchars($record->invoice_code); ?></td>
                                <td><?php echo $record->invoice_date; ?></td>
                                <td><?php echo $record->delivery_date; ?></td>
                                <?php
                                        if ($record->status == 0) {
                                            echo "<td style='color: #656565;font-weight: bold;'>Đang Chờ Kiểm Tra</td>";
                                        } elseif ($record->status == 1) {
                                            echo "<td style='color: #ff0000;font-weight: bold;'>Đang Kiểm Tra</td>";
                                        } elseif ($record->status == 2) {
                                            echo "<td style='color: #0014ff;font-weight: bold;'>Đã Kiểm Tra</td>";
                                        } elseif ($record->status == 3) {
                                            echo "<td style='color: #ff4d00;font-weight: bold;'>Đang Lưu Kho</td>";
                                        } elseif ($record->status == 4) {
                                            echo "<td style='color: #029800;font-weight: bold;'>Hoàn Thành</td>";
                                        }
                                        ?>
                            </tr>
                            <?php }
                            } ?>
                        </table>

                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div><!-- /.box -->
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
        jQuery("#searchList").attr("action", baseURL + "goods-receipts/" + value);
        jQuery("#searchList").submit();
    });
});
</script>